<?php

namespace Tests\AppBundle\Controller\API;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * @group Category
 * @group v1
 *
 * Class CategoryControllerTest
 * @package Tests\AppBundle\Controller
 */
class CategoryControllerTest extends WebAuthTestCase
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

	public function testCategoryList()
	{
		$this->loginAs($this->referenceRepository->getReference('adminusername'), 'api_private');
		$client = $this->makeClient(true);

		$client->request(
			Request::METHOD_GET,
			'/API/v1/Category' . $this->getUrl('category_list')
		);

		//$this->assertEquals('[{"id":1,"name":"var1"},{"id":2,"name":"var2"}]', $client->getResponse()->getContent());
		$res = json_decode($client->getResponse()->getContent(), true);
		$this->assertEquals('category1', $res[0]['name']);
		$this->assertEquals('category2', $res[1]['name']);
		$this->assertEquals(200, $client->getResponse()->getStatusCode());
	}

	public function testCategoryAdd()
	{
		$client = static::createAuthenticatedClient('adminusername', '123456');
		$client->request(
			Request::METHOD_POST,
			'/API/v1/Category' . $this->getUrl('category_add'),
			array(),
			array(),
			array(),
			json_encode(array(
				'name'			=>'category3'
			))
		);

		//$this->assertEquals('{"id":3,"name":"category3"}', $client->getResponse()->getContent());
		$res = json_decode($client->getResponse()->getContent(), true);
		$this->assertEquals('category3', $res['name']);
		$this->assertEquals(200, $client->getResponse()->getStatusCode());
	}
}