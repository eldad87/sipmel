<?php

namespace AppBundle\Controller\API;


use AppBundle\Form\ChangePasswordType;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\FOSRestController;

use AppBundle\Entity\User;
use AppBundle\Entity\Company;
use AppBundle\Form\API\UserRegisterType;

use FOS\RestBundle\Request\ParamFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use FOS\RestBundle\Controller\Annotations as Rest;



use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\ConstraintViolationList;


/**
 * @Rest\Version("v1")
 *
 * Manage User
 * @package AppBundle\Controller\API
 */
class UserController extends FOSRestController
{

	/**
	 * @Rest\Post(path="/Register", name="registration")
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
	 * 		name="user",
	 *      in="body",
	 *      description="JSON User object",
	 *      type="json",
	 *      required=true,
	 *      @Model(type=User::class, groups={"register"})
	 * )
	 *
	 * @SWG\Response(
	 *      response="200",
	 *      description="Returned when successful.",
	 *      @Model(type=User::class, groups={"register_response"}),
	 * )
	 *
	 * TODO: Response 400, validation structure
	 * @SWG\Response(
	 *      response="400",
	 *      description="Validation Error",
	 *      @SWG\schema(
	 *     		type="array",
	 *          @SWG\items(
	 *          	type="object",
	 *              @SWG\Property(property="property_path", type="string"),
	 *              @SWG\Property(property="message", type="string"),
	 *          ),
	 *      ),
	 *     examples={"Invalid Email and Username"="{""property_path"":""username"",""message"":""This value is too short. It should have 5 characters or more.""},{""email"":""3089"",""message"":""This value is not a valid email address.""}"}
	 * )
	 * @SWG\Tag(name="user.register", description="User registration")
	 *
	 * @return View
	 * @ParamConverter("user", converter="fos_rest.request_body", options={"validator"={"groups"="register"}, "deserializationContext"={"groups"={"register"}}})
	 */
	public function RegisterAction(User $user, ConstraintViolationList $validationErrors)
	{
		if (count($validationErrors)) {
			return $this->view($validationErrors, Response::HTTP_BAD_REQUEST);
		}

		$user->setRoles(['ROLE_ADMIN']);
		$user->setIsEnabled(true);
		$password = $this->get('security.password_encoder')
			->encodePassword($user, $user->getPassword());
		$user->setPassword($password);
		$user->getCompany()->setIsEnabled(true);


		$em = $this->getDoctrine()->getManager();
		$em->persist($user);
		$em->flush();

		return $this->view($user, 200);
	}

	/**
	 * @Rest\Post(path="/Token/New", name="new_token")
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
	 * 		name="user",
	 *      in="body",
	 *      description="JSON User login object",
	 *      type="json",
	 *      required=true,
	 *      @Model(type=User::class, groups={"login"})
	 * )
	 *
	 * @SWG\Response(
	 *      response="200",
	 *      description="Access Token",
	 *      @SWG\Items(
	 *     		type="object",
	 *    	 	@SWG\Property(property="token", type="string")
	 * 		)
	 * )
	 * TODO: Response 400, validation structure
	 * @SWG\Tag(name="user.access.token", description="User Access Token")
	 *
	 * Param User $user
	 * param Request $request
	 * Param ConstraintViolationList $violations
	 * @return View
	 * @ParamConverter("user", converter="fos_rest.request_body", options={"validator"={"groups"="login"}, "deserializationContext"={"groups"={"login"}}})
	 */
	public function newTokenAction(User $user, ConstraintViolationList $validationErrors)
	{
		if (count($validationErrors)) {
			return $this->view($validationErrors, Response::HTTP_BAD_REQUEST);
		}

		$userDB = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username'=> $user->getUsername()]);
		if (!$userDB) {
			return $this->view([['property_path' => 'username', 'message' => 'username not found or password is invalid']], 400); //TODO: constrain or something more 2017..
		}

		$isValid = $this->get('security.password_encoder')
			->isPasswordValid($userDB, $user->getPassword());

		if (!$isValid) {
			return $this->view([['property_path' => 'username', 'message' => 'username not found or password is invalid']], 400); //TODO: constrain or something more 2017..
		}

		$token = $this->get('lexik_jwt_authentication.encoder')
			->encode([
				'username' => $userDB->getUsername(),
				'exp' => time() + 3600 // 1 hour expiration
			]);

		return $this->view(['token' => $token], 200);
	}


	/**
	 * @Rest\Post(path="/ChangePassword", name="change_password")
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
	 * 		name="password",
	 *      in="body",
	 *      description="Password",
	 *      type="string",
	 *      required=true,
	 *
	 *     	@SWG\Schema(
	 *     		type="object",
	 *    	 	@SWG\Property(property="password_current", type="string"),
	 *    	 	@SWG\Property(property="password_new", type="string"),
	 *    	 	@SWG\Property(property="password_new_confirm", type="string")
	 * 		)
	 * )
	 *
	 * @SWG\Response(
	 *      response="200",
	 *      description="Reset Password",
	 *      @SWG\Items(
	 *     		type="object",
	 *    	 	@SWG\Property(property="status", type="string", enum={"OK", "Error"}),
	 *    	 	@SWG\Property(property="message", type="string")
	 * 		)
	 * )
	 * TODO: Response 400, validation structure
	 * @SWG\Tag(name="user.change.forgot", description="Change Password")
	 *
	 * @return View
	 */
	public function changePasswordAction(Request $request)
	{
		if($request->request->get('password_new') != $request->request->get('password_new_confirm')) {
			return $this->view([['property_path' => 'password_new', 'message' => 'Error']], 401); //TODO: constrain or something more 2017..
		}

		$user = $this->getUser();

		$isValid = $this->get('security.password_encoder')
			->isPasswordValid($user, $request->request->get('password_current'));
		if (!$isValid) {
			return $this->view([['property_path' => 'password_new', 'message' => 'Error']], 401); //TODO: constrain or something more 2017..
		}

		$passwordNew = $this->get('security.password_encoder')
			->encodePassword($user, $request->request->get('password_new'));

		$em = $this->getDoctrine()->getManager();
		$user->setPassword($passwordNew);
		$em->persist($user);
		$em->flush();

		return new JsonResponse(['status' => 'ok', 'message'=>'Success']);
	}
}
