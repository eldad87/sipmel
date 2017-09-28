<?php

namespace AppBundle\Controller\API;


use AppBundle\Entity\User;
use AppBundle\Entity\Category;
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
 * Manage Categorys
 * @package AppBundle\Controller\API
 */
class CategoryController extends FOSRestController
{

	/**
	 * @Rest\Post(path="", name="category_add")
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
	 * 		name="category",
	 *      in="body",
	 *      description="JSON Category object",
	 *      type="json",
	 *      required=true,
	 *      @Model(type=Category::class, groups={"save"})
	 * )
	 *
	 * @SWG\Response(
	 *      response="200",
	 *      description="Returned when successful.",
	 *      @Model(type=Category::class, groups={"save_response"}),
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
	 * @SWG\Tag(name="category.add", description="Add Category")
	 *
	 * @return View
	 * @ParamConverter("category", converter="fos_rest.request_body", options={"validator"={"groups"="save"}, "deserializationContext"={"CompanyAware"=true,"groups"={"save"}}})
	 */
	public function addAction(Category $category, ConstraintViolationList $validationErrors)
	{
		if (count($validationErrors)) {
			return $this->view($validationErrors, Response::HTTP_BAD_REQUEST);
		}

		$em = $this->getDoctrine()->getManager();
		$em->persist($category);
		$em->flush();

		return $this->view($category, 200);
	}

	/**
	 * @Rest\Get(path="", name="category_list")
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
	 *      	@Model(type=Category::class, groups={"list_response"})
	 *	 	)
	 * )
	 *
	 * @SWG\Tag(name="category.list", description="List Categorys")
	 *
	 * @return View
	 */
	public function listAction()
	{
		/** @var User $user */
		$user = $this->getUser();

		$categorys = $this->getDoctrine()->getRepository(Category::class)
			->findByCompany($user->getCompany());
		return $this->view($categorys, 200);
	}
}