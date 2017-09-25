<?php

namespace Tests\AppBundle\Controller\API;

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

	public function testUserAccessCheck()
	{
		$client = static::createAuthenticatedClient('username', '123456');

		$client->request(
			Request::METHOD_POST,
			'/API/v1/User/Access/Check.json'
		);

		$this->assertEquals('{"status":"ok","message":"Success"}', $client->getResponse()->getContent());
		$this->assertEquals(200, $client->getResponse()->getStatusCode());
	}

	public function testUserChangePassword()
	{
		$client = static::createAuthenticatedClient('username', '123456');
		$client->request(
			Request::METHOD_POST,
			'/API/v1/User/ChangePassword.json',
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