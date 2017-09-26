<?php

namespace AppBundle\Controller\API;


use AppBundle\Entity\RecipientBucket;
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
 * Manage RecipientBucket
 * @package AppBundle\Controller\API
 */
class RecipientBucketController extends FOSRestController
{

	/**
	 * @Rest\Post(path="", name="recipient_bucket_add")
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
	 * 		name="recipientBucket",
	 *      in="body",
	 *      description="JSON Recipient Bucket object",
	 *      type="json",
	 *      required=true,
	 *      @Model(type=RecipientBucket::class, groups={"save"})
	 * )
	 *
	 * @SWG\Response(
	 *      response="200",
	 *      description="Returned when successful.",
	 *      @Model(type=RecipientBucket::class, groups={"save_response"}),
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
	 * @SWG\Tag(name="recipient.bucket.add", description="Add Recipient Bucket")
	 *
	 * @return View
	 * @ParamConverter("recipientBucket", converter="fos_rest.request_body", options={"validator"={"groups"="save"}, "deserializationContext"={"CompanyAware"=true,"groups"={"save"}}})
	 */
	public function addAction(RecipientBucket $recipientBucket, ConstraintViolationList $validationErrors)
	{
		if (count($validationErrors)) {
			return $this->view($validationErrors, Response::HTTP_BAD_REQUEST);
		}

		$em = $this->getDoctrine()->getManager();
		$em->persist($recipientBucket);
		$em->flush();

		return $this->view($recipientBucket, 200);
	}

	/**
	 * @Rest\Get(path="", name="recipient_bucket_list")
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
	 *      	@Model(type=RecipientBucket::class, groups={"save_response"})
	 *	 	)
	 * )
	 *
	 * @SWG\Tag(name="recipient.bucket.list", description="List Recipient Buckets")
	 *
	 * @return View
	 */
	public function listAction()
	{
		/** @var User $user */
		$user = $this->getUser();

		$recipientBuckets = $this->getDoctrine()->getRepository(RecipientBucket::class)
			->findByCompany($user->getCompany());
		return $this->view($recipientBuckets, 200);
	}
}