<?php

namespace AppBundle\Controller\API;


use AppBundle\Entity\User;
use AppBundle\Entity\Content;
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
 * Manage Contents
 * @package AppBundle\Controller\API
 */
class ContentController extends FOSRestController
{

	/**
	 * @Rest\Post(path="", name="content_add")
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
	 * 		name="content",
	 *      in="body",
	 *      description="JSON Content object",
	 *      type="json",
	 *      required=true,
	 *      @Model(type=Content::class, groups={"save"})
	 * )
	 *
	 * @SWG\Response(
	 *      response="200",
	 *      description="Returned when successful.",
	 *      @Model(type=Content::class, groups={"save_response"}),
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
	 * @SWG\Tag(name="content.add", description="Add Content")
	 *
	 * @return View
	 * @ParamConverter("content", converter="fos_rest.request_body", options={"validator"={"groups"="save"}, "deserializationContext"={"CompanyAware"=true,"groups"={"save"}}})
	 */
	public function addAction(Content $content, ConstraintViolationList $validationErrors)
	{
		if (count($validationErrors)) {
			return $this->view($validationErrors, Response::HTTP_BAD_REQUEST);
		}

		$em = $this->getDoctrine()->getManager();
		$em->persist($content);
		$em->flush();

		return $this->view($content, 200);
	}

	/**
	 * @Rest\Get(path="", name="content_list")
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
	 *      	@Model(type=Content::class, groups={"list_response"})
	 *	 	)
	 * )
	 *
	 * @SWG\Tag(name="content.list", description="List Contents")
	 *
	 * @return View
	 */
	public function listAction()
	{
		/** @var User $user */
		$user = $this->getUser();

		$contents = $this->getDoctrine()->getRepository(Content::class)
			->findByCompany($user->getCompany());
		return $this->view($contents, 200);
	}
}