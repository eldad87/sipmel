<?php

namespace AppBundle\Controller\API;


use AppBundle\Entity\Bucket;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;

use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Validator\ConstraintViolationList;

use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

/**
 * @Rest\Version("v1")
 *
 * Manage Bucket
 * @package AppBundle\Controller\API
 */
class BucketController extends FOSRestController
{

	/**
	 * @Rest\Post(path="", name="bucket_add")
	 *
	 * @SWG\Parameter(
	 * 		name="version",
	 *      in="path",
	 *      description="Version",
	 *      type="string",
	 *      required=true,
	 *      enum={"v1"}
	 * )
	 *
	 * @SWG\Parameter(
	 * 		name="bucket",
	 *      in="body",
	 *      description="JSON Bucket object",
	 *      type="json",
	 *      required=true,
	 *      @Model(type=Bucket::class, groups={"save"})
	 * )
	 *
	 * @SWG\Response(
	 *      response="200",
	 *      description="Returned when successful.",
	 *      @Model(type=Bucket::class, groups={"save_response"}),
	 * )
	 *
	 * @SWG\Response(
	 *      response="400",
	 *      description="Validation Error",
	 *      @SWG\Schema(
	 *     		type="array",
	 *          @SWG\Schema(
	 *          	type="object",
	 *              @SWG\Property(property="property_path", type="string"),
	 *              @SWG\Property(property="message", type="string"),
	 *          ),
	 *      )
	 * )
	 *
	 * @SWG\Tag(name="bucket.add", description="Add Bucket")
	 *
	 * @return View
	 * @ParamConverter("bucket", converter="fos_rest.request_body", options={"validator"={"groups"="save"}, "deserializationContext"={"CompanyAware"=true,"groups"={"save"}}})
	 */
	public function addAction(Bucket $bucket, ConstraintViolationList $validationErrors)
	{
		if (count($validationErrors)) {
			return $this->view($validationErrors, Response::HTTP_BAD_REQUEST);
		}

		$em = $this->getDoctrine()->getManager();
		$em->persist($bucket);
		$em->flush();

		return $this->view($bucket, 200);
	}

	/**
	 * @Rest\Get(path="", name="bucket_list")
	 *
	 * @SWG\Parameter(
	 * 		name="version",
	 *      in="path",
	 *      description="Version",
	 *      type="string",
	 *      required=true,
	 *      enum={"v1"}
	 * )
	 *
	 * @SWG\Response(
	 *      response="200",
	 *      description="Returned when successful.",
	 *     	@SWG\Schema(
	 *     		type="array",
	 *      	@Model(type=Bucket::class, groups={"list_response"})
	 *	 	)
	 * )
	 *
	 * @SWG\Tag(name="bucket.list", description="List Buckets")
	 *
	 * @return View
	 */
	public function listAction()
	{
		/** @var User $user */
		$user = $this->getUser();

		$buckets = $this->getDoctrine()->getRepository(Bucket::class)
			->findByCompany($user->getCompany());
		return $this->view($buckets, 200);
	}
}