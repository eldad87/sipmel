<?php

namespace Tests\AppBundle\Controller\API;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * @group Content
 * @group v1
 *
 * Class ContentControllerTest
 * @package Tests\AppBundle\Controller
 */
class ContentControllerTest extends WebAuthTestCase
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

	public function testContentList()
	{
		$this->loginAs($this->referenceRepository->getReference('adminusername'), 'api_private');
		$client = $this->makeClient(true);

		$client->request(
			Request::METHOD_GET,
			'/API/v1/Content' . $this->getUrl('content_list')
		);

		//$this->assertEquals('[{"id":1,"name":"content1_en","fallback_language":"en"},{"id":2,"name":"content2_fr","fallback_language":"fr"}]', $client->getResponse()->getContent());
		$res = json_decode($client->getResponse()->getContent(), true);
		$this->assertEquals('content1_en', $res[0]['name']);
		$this->assertEquals('en', $res[0]['fallback_language']);
		$this->assertEquals('content2_fr', $res[1]['name']);
		$this->assertEquals('fr', $res[1]['fallback_language']);
		$this->assertEquals(200, $client->getResponse()->getStatusCode());
	}

	public function testContentAdd()
	{
		$client = static::createAuthenticatedClient('adminusername', '123456');
		$client->request(
			Request::METHOD_POST,
			'/API/v1/Content' . $this->getUrl('content_add'),
			array(),
			array(),
			array(),
			json_encode(array(
				'name'			=>'content3',
				'fallback_language'		=>'de'
			))
		);

		//$this->assertEquals('{"id":3,"name":"content3","fallback_language":"de"}', $client->getResponse()->getContent());
		$res = json_decode($client->getResponse()->getContent(), true);
		$this->assertEquals('content3', $res['name']);
		$this->assertEquals('de', $res['fallback_language']);
		$this->assertEquals(200, $client->getResponse()->getStatusCode());
	}
}