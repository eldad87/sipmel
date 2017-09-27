<?php

namespace Tests\AppBundle\Controller\API;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * @group Variable
 * @group v1
 *
 * Class VariableControllerTest
 * @package Tests\AppBundle\Controller
 */
class VariableControllerTest extends WebAuthTestCase
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

	public function testVariableList()
	{
		$this->loginAs($this->referenceRepository->getReference('adminusername'), 'api_private');
		$client = $this->makeClient(true);

		$client->request(
			Request::METHOD_GET,
			'/API/v1/Variable' . $this->getUrl('variable_list')
		);

		//$this->assertEquals('[{"id":1,"name":"var1"},{"id":2,"name":"var2"}]', $client->getResponse()->getContent());
		$res = json_decode($client->getResponse()->getContent(), true);
		$this->assertEquals('var1', $res[0]['name']);
		$this->assertEquals('var2', $res[1]['name']);
		$this->assertEquals(200, $client->getResponse()->getStatusCode());
	}

	public function testVariableAdd()
	{
		$client = static::createAuthenticatedClient('adminusername', '123456');
		$client->request(
			Request::METHOD_POST,
			'/API/v1/Variable' . $this->getUrl('variable_add'),
			array(),
			array(),
			array(),
			json_encode(array(
				'name'			=>'var3'
			))
		);

		//$this->assertEquals('{"id":3,"name":"var3"}', $client->getResponse()->getContent());
		$res = json_decode($client->getResponse()->getContent(), true);
		$this->assertEquals('var3', $res['name']);
		$this->assertEquals(200, $client->getResponse()->getStatusCode());
	}
}