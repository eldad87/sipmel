<?php

namespace AppBundle\Controller\API;


use AppBundle\Entity\User;
use AppBundle\Entity\Variable;
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
 * Manage Variables
 * @package AppBundle\Controller\API
 */
class VariableController extends FOSRestController
{

	/**
	 * @Rest\Post(path="", name="variable_add")
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
	 * 		name="variable",
	 *      in="body",
	 *      description="JSON Variable object",
	 *      type="json",
	 *      required=true,
	 *      @Model(type=Variable::class, groups={"save"})
	 * )
	 *
	 * @SWG\Response(
	 *      response="200",
	 *      description="Returned when successful.",
	 *      @Model(type=Variable::class, groups={"save_response"}),
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
	 * @SWG\Tag(name="variable.add", description="Add Variable")
	 *
	 * @return View
	 * @ParamConverter("variable", converter="fos_rest.request_body", options={"validator"={"groups"="save"}, "deserializationContext"={"CompanyAware"=true,"groups"={"save"}}})
	 *
	 * TODO: Variable entity need to be Unique(company, name) - but it doesn't work.
	 * 		Meanwhile, there is a unique index in DB.
	 * 			Create a custom Constraint for unique check
	 */
	public function addAction(Variable $variable, ConstraintViolationList $validationErrors)
	{
		if (count($validationErrors)) {
			return $this->view($validationErrors, Response::HTTP_BAD_REQUEST);
		}

		/** @var User $user */
		$user = $this->getUser();
		$variable->setCompany($user->getCompany());

		$em = $this->getDoctrine()->getManager();
		$em->persist($variable);
		$em->flush();

		return $this->view($variable, 200);
	}

	/**
	 * @Rest\Get(path="", name="variable_list")
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
	 *      	@Model(type=Variable::class, groups={"save_response"})
	 *	 	)
	 * )
	 *
	 * @SWG\Tag(name="variable.list", description="List Variables")
	 *
	 * @return View
	 */
	public function listAction()
	{
		/** @var User $user */
		$user = $this->getUser();

		$variables = $this->getDoctrine()->getRepository(Variable::class)
			->findByCompany($user->getCompany());
		return $this->view($variables, 200);
	}
}