<?php

namespace Tests\AppBundle\Controller\API;

use Doctrine\Common\DataFixtures\ReferenceRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * @group User
 * @group v1
 *
 * Class UserControllerTest
 * @package Tests\AppBundle\Controller
 */
class UserControllerTest extends WebAuthTestCase
{
	/** @var ReferenceRepository */
	protected $referenceRepository;

	public function setUp()
	{
		//Drop and Create DB all over again
		$this->dropAndCreateDB();
		$this->createSchema();

		$this->referenceRepository = $this->loadFixtures(
			array(
				'AppBundle\DataFixtures\ORM\LoadData'
			)
		)->getReferenceRepository();
	}

	public function UserAccessCheck()
	{
		$this->loginAs($this->referenceRepository->getReference('adminusername'), 'api_private');
		$client = $this->makeClient(true);

		$client->request(
			Request::METHOD_GET,
			'/API/v1/User' . $this->getUrl('access_check')
		);

		$this->assertEquals('{"status":"ok","message":"Success"}', $client->getResponse()->getContent());
		$this->assertEquals(200, $client->getResponse()->getStatusCode());
	}

	public function testUserChangePassword()
	{
		/** @var User $user */
		$user = $this->referenceRepository->getReference('adminusername');
		$client = static::createAuthenticatedClient('adminusername', '123456');
		$client->request(
			Request::METHOD_POST,
			'/API/v1/User' . $this->getUrl('change_password'),
			array(),
			array(),
			array(),
			json_encode(array(
				'password_new'			=>'123456',
				'password_new_confirm'	=>'123456',
				'password_current'		=>'123456',
			))
		);

		$this->assertEquals('{"status":"ok","message":"Success"}', $client->getResponse()->getContent());
		$this->assertEquals(200, $client->getResponse()->getStatusCode());
	}
}