<?php

namespace Tests\AppBundle\Controller\API;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * @group User
 * Class AbstractAuthControllerTest
 * @package Tests\AppBundle\Controller
 */
class WebAuthTestCase extends WebTestCase
{
	/**
	 * Create a client with a default Authorization header.
	 *
	 * @param string $username
	 * @param string $password
	 *
	 * @return \Symfony\Bundle\FrameworkBundle\Client
	 */
	protected function createAuthenticatedClient($username = 'user', $password = 'password', $mimeType='application/json')
	{
		$client = static::createClient();
		$client->setServerParameter('HTTP_Accept', $mimeType);
		$client->request(
			Request::METHOD_POST,
			'/API/Basic/login_check',
			array(
				'_username'=>$username,
				'_password'=>$password,
			)
		);
		$data = json_decode($client->getResponse()->getContent(), true);

		$client = static::createClient(array(), array(
			'HTTP_CONTENT_TYPE' => $mimeType,
			'HTTP_ACCEPT' 		=> $mimeType,
			'HTTP_Content-Type' => $mimeType,
			'HTTP_Accept' 		=> $mimeType,
			'HTTP_Authorization'=> sprintf('Bearer %s', $data['token'])
		));
		/*$client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $data['token']));
		$client->setServerParameter('HTTP_Accept', $mimeType);*/

		return $client;
	}
}