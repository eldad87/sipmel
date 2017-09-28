<?php

namespace AppBundle\Controller\API;


use AppBundle\Entity\Category;
use AppBundle\Entity\User;
use AppBundle\Entity\Content;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
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
	 * @Rest\Post(path="Category/{category}", name="content_add_to_category")
	 *
	 * @SWG\Parameter(
	 * 		name="version",
	 *      in="path",
	 *      description="Version",
	 *      type="string",
	 *      required=true,
	 *      enum={"v1"}
	 * )
	 * @SWG\Parameter(
	 * 		name="category_id",
	 *      in="path",
	 *      description="Category Id",
	 *      type="integer",
	 *      required=true
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
	 * @ParamConverter("category", options={"id" = "category"})
	 */
	public function addAction(Content $content, Category $category)
	{
		$content->setCategory($category);
		$validator = $this->get('validator');
		$validationErrors = $validator->validate($content);

		if (count($validationErrors)) {
			return $this->view($validationErrors, Response::HTTP_BAD_REQUEST);
		}

		if($category->getCompany() != $this->getUser()->getCompany()) {
			return $this->view([['property_path' => 'category_id', 'message' => 'Category is invalid']], 401); //TODO: constrain or something more 2017..
		}

		$em = $this->getDoctrine()->getManager();
		$em->persist($content);
		$em->flush();

		return $this->view($content, 200);

		//TODO: use annotation if="" to check if both content.companye = category.company
		//You can use @accessor
		//@MaxDepth
		//@map=true comma ceparated ids
	}

	/**
	 * @Rest\Get(path="Category/{category}", name="content_list_by_categoty")
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
	 * 		name="category_id",
	 *      in="path",
	 *      description="Category Id",
	 *      type="integer",
	 *      required=true
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
	 * @ParamConverter("category", options={"id" = "category"})
	 */
	public function listAction(Category $category=null)
	{
		/** @var User $user */
		$user = $this->getUser();

		$criteria = array('company'=>$user->getCompany());
		if($category) {
			$criteria['category'] = $category;
		}

		$contents = $this->getDoctrine()->getRepository(Content::class)
			->findBy($criteria);
		return $this->view($contents, 200);
	}
}