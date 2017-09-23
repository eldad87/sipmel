<?php

namespace AppBundle\Controller\API;

use AppBundle\Entity\Company;
use AppBundle\Event\EmailForgotPasswordEvent;
use AppBundle\Form\API\UserRegisterType;
use AppBundle\Form\ForgotPasswordType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpKernel\Exception\HttpException;
use AppBundle\Form\ChangePasswordType;
use AppBundle\Entity\User;
use AppBundle\Utils\PasswordGenerator;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Delete;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Controller\Annotations\QueryParam;

use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Validator\Constraints as Constraint;

/**
 * @Rest\Version("v1")
 *
 * Manage User
 * @package AppBundle\Controller\API
 */
class UserController extends FOSRestController
{




	/**
	 * Register a new user
	 * @param Request $request
	 * @param ParamFetcher $paramFetcher
	 * @return View
	 *
	 * @Rest\View(serializerGroups={"register"})
	 * @Rest\RequestParam(name="email", requirements=@Constraint\Email(), description="max 50", nullable=false, allowBlank=false)
	 * @Rest\RequestParam(name="username", requirements=@Constraint\Length(min="5", max="25"), description="min 5, max 25", nullable=false, allowBlank=false)
	 * @Rest\RequestParam(name="first_name", requirements=@Constraint\Length(min="2", max="25"), description="min 2, max 25", nullable=false, allowBlank=false)
	 * @Rest\RequestParam(name="last_name", requirements=@Constraint\Length(min="2", max="25"), description="min 2, max 25", nullable=false, allowBlank=false)
	 * @Rest\RequestParam(name="password", description="Password", nullable=false, allowBlank=false)
	 * @Rest\RequestParam(name="company.name", description="Company Name", nullable=false, allowBlank=false)
	 *
	 *
	 * @Rest\Post(path="/Register", name="registration")
	 *
	 * @ApiDoc(
	 *     resource=true,
	 *     description="Register a new User",
	 *     views = {"v1"},
	 *     section="User",
	 *     output={
	 *     	"class"="AppBundle\Entity\User\User",
	 *     	"groups"={"register"},
	 *     	"parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParse"}
	 *     },
	 *     statusCodes={
	 *         201="Returned when successful",
	 *         401="Returned when trying to create non authenticated",
	 *         422="Returned when the profile validation failed"
	 *      }
	 * )
	 *
	 *
	 * Disclaimer:
	 * I know, using "company_name" paramter, I could have just used "company[name]".
	 * 	Well, I tried. ParamFetcher use the literal key to look it's value in the request:
	 * 		request[company[name]] -> Doesn't find anything..
	 * 		Instead, it could have worked if it will be PATCHed into request[company][name]
	 * Anyway, I can use:
	 * 	Add annotation: "@ParamConverter("user", converter="fos_rest.request_body")"
	 *  Add User $user to action: public function RegisterAction(Request $request, ParamFetcher $paramFetcher, User $user)
	 * 	Change "@Rest\RequestParam(name="company[name]", key="company.name", description="Company Name", nullable=false, allowBlank=false)"
	 *		With: strict=false, nullable=true, allowBlank=true
	 * 	    But.. it won't pass any validation.
	 * 		Yes.. I could have used the validator in the controller and add annotations in the User/Company's entities -> Its just too much :)
	 *
	 * So I took the easiers solution.
	 */
	public function RegisterAction(Request $request, ParamFetcher $paramFetcher)
	{

		$user = new User();
		$user->setRoles(['ROLE_ADMIN']);
		$user->setIsEnabled(true);
		$user->setEmail($paramFetcher->get('email'));
		$user->setUsername($paramFetcher->get('username'));
		$user->setFirstName($paramFetcher->get('first_name'));
		$user->setLastName($paramFetcher->get('last_name'));

		$password = $this->get('security.password_encoder')
			->encodePassword($user, $paramFetcher->get('password'));
		$user->setPassword($password);

		$company = new Company();
		$company->setIsEnabled(true);
		$company->setName($paramFetcher->get('company.name'));
		$company->addUser($user);

		$em = $this->getDoctrine()->getManager();
		$em->persist($company);
		$em->flush();

		return new JsonResponse(['status' => 'ok']);
		echo 2;

		$a = 1;

		$a++;
		die;

		$user = new User();
		$form = $this->createForm(UserRegisterType::class, $user);
		$form->handleRequest($request);

		if ($form->isValid()) {
			$password = $this->get('security.password_encoder')
				->encodePassword($user, $user->getPassword());
			$user->setPassword($password);
			$user->setRoles(['ROLE_ADMIN']);
			$em = $this->getDoctrine()->getManager();
			$em->persist($user);
			$em->flush();

			return new JsonResponse(['status' => 'ok']);
		}

		throw new HttpException(400, "Invalid data");
	}

	/**
	 * @View()
	 *
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Generate access token"
	 * )
	 *
	 * @ RequestParam(name="username", requirements={@Constraint\NotBlank()}, description="Usernmae", nullable=false)
	 * @ RequestParam(name="password", requirements={"range"=@Constraint\Range(min="10", max=40),"len"=@Constraint\Length(min="10", max=40)}, description="Password", nullable=false)
	 * @ Post("/Token/New", name="new_token", options={ "method_prefix" = false })
	 */
	public function newTokenAction(ParamFetcher $paramFetcher, Request $request)
	{
		$paramFetcher->all();

		$user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['username'=> $request->request->get('username')]);
		if (!$user) {
			throw $this->createNotFoundException();
		}

		$isValid = $this->get('security.password_encoder')
			->isPasswordValid($user, $request->request->get('password'));

		if (!$isValid) {
			throw new BadCredentialsException();
		}

		$token = $this->get('lexik_jwt_authentication.encoder')
			->encode([
				'username' => $user->getUsername(),
				'exp' => time() + 3600 // 1 hour expiration
			]);

		return new JsonResponse(['token' => $token]);
	}

	/**
	 * @View()
	 *
	 * @ApiDoc(
	 *  resource=true,
	 *  description="Forgot Paasowrd"
	 * )
	 *
	 * @ RequestParam(
	 *	name="email", requirements="@Constraint\Email", description="Email", nullable=false
	 * )
	 * @ Post(path="ForgotPassword", name="forgot_password", options={ "method_prefix" = false })
	 */
	public function forgotPasswordAction(Request $request/*, ParamFetcher $paramFetcher*/)
	{
		$user = new User();
		$passwordGenerator = new PasswordGenerator();
		$form = $this->createForm(ForgotPasswordType::class, $user);
		$form->handleRequest($request);

		if ($form->isValid()) {
			$email = $request->request->get('email');
			$em = $this->getDoctrine()->getManager();
			/** @var User $userRepository */
			$userRepository = $em->getRepository(User::class)->findOneBy(['email' => $email]);
			$userRepository->setPassword($passwordGenerator->generatePassword());

			$event = new EmailForgotPasswordEvent($userRepository);
			$dispatcher = $this->get('event_dispatcher');
			$dispatcher->dispatch(EmailForgotPasswordEvent::NAME, $event);

			$em->persist($userRepository);
			$em->flush();

			return new JsonResponse(['status' => 'ok']);
		}

		throw new HttpException(400, "Invalid data");
	}

    /**
	 * @ RequestParam(
	 *	name="email", requirements="@Constraint\Email, @Constraint\NotBlank", description="Email", nullable=false
	 * )
	 * @ RequestParam(
	 *	name="password", requirements="", description="Password", nullable=false
	 * )
	 * @ Post("/ChangePassword", name="change_password", options={ "method_prefix" = false })
     */
    public function changePasswordAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $email = $request->request->get('email');
            $password = $form->getData()->getPassword();
            $passwordNew = $this->get('security.password_encoder')
                               ->encodePassword($user, $user->getPassword());
            $em = $this->getDoctrine()->getManager();
            $userRepository = $em->getRepository(User::class)->findOneBy(['email' => $email]);
            $userRepository->setPassword($passwordNew);
            $em->persist($userRepository);
            $em->flush();

            return new JsonResponse(['status' => 'ok']);
        }

        throw new HttpException(400, "Invalid data");
    }
}
