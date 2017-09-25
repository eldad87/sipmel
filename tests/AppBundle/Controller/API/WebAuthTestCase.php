<?php

namespace Tests\AppBundle\Controller\API;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Doctrine\ORM\Tools\SchemaTool;

/**
 * @group User
 * Class AbstractAuthControllerTest
 * @package Tests\AppBundle\Controller
 */
class WebAuthTestCase extends WebTestCase
{
	/**
	 * Create a client with a default Authorization header.
	 * Use a real user, useful for cases in which we need to update ourself (i.e change password etc.)
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
			$this->getUrl('api_basic_auth_login_check'),
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

		return $client;
	}

	/**
	 * @inheritdoc
	 */
	protected function makeClient($authentication = false, array $params = array(), $mimeType='application/json')
	{
		$client = parent::makeClient($authentication, $params);
		$client->setServerParameters(array(
				'HTTP_CONTENT_TYPE' => $mimeType,
				'HTTP_ACCEPT' 		=> $mimeType,
				'HTTP_Content-Type' => $mimeType,
				'HTTP_Accept' 		=> $mimeType
			)
		);

		return $client;
	}

	protected function dropAndCreateDB($emNam=null)
	{
		$command = array();
		if($emNam) {
			$command['--connection'] = $emNam;
		}
		$this->runCommand('doctrine:database:drop', array_merge($command, array(
			'--env'				=>'test',
			'--force'			=>true,
			'--no-interaction'	=>true
		)));
		$this->runCommand('doctrine:database:create', array_merge($command, array(
			'--env'				=>'test',
			'--no-interaction'	=>true
		)));

		/* Same as self::createSchema()
		$this->runCommand('doctrine:schema:update', array(
			'--env'				=>'test',
			'--force'			=>true,
			'--no-interaction'	=>true
		));*/
	}

	protected function createSchema(array $metadata=null, $emNam=null)
	{
		/** @var EntityManager $em */
		$em = $this->getContainer()->get('doctrine')->getManager($emNam);
		if (!isset($metadata)) {
			$metadata = $em->getMetadataFactory()->getAllMetadata();
		}

		$schemaTool = new SchemaTool($em);
		$schemaTool->dropDatabase();
		if (!empty($metadata)) {
			$schemaTool->createSchema($metadata);
		}
		$this->postFixtureSetup();
	}

	protected function getService($id)
	{
		return self::$kernel->getContainer()
			->get($id);
	}
}