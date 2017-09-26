<?php

namespace Tests\AppBundle\Controller\API;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\ReferenceRepository;
use Symfony\Component\HttpFoundation\Request;

/**
 * @group RecipientBucket
 * @group v1
 *
 * Class RecipientBucketControllerTest
 * @package Tests\AppBundle\Controller
 */
class RecipientBucketControllerTest extends WebAuthTestCase
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

	public function testRecipientBucketList()
	{
		$this->loginAs($this->referenceRepository->getReference('adminusername'), 'api_private');
		$client = $this->makeClient(true);

		$client->request(
			Request::METHOD_GET,
			'/API/v1/Recipient/Bucket' . $this->getUrl('recipient_bucket_list')
		);

		//$this->assertEquals('[{"id":1,"name":"bucket1"},{"id":2,"name":"bucket2"}]', $client->getResponse()->getContent());
		$res = json_decode($client->getResponse()->getContent(), true);
		$this->assertEquals('bucket1', $res[0]['name']);
		$this->assertEquals('bucket2', $res[1]['name']);
		$this->assertEquals(200, $client->getResponse()->getStatusCode());
	}

	public function testRecipientBucketAdd()
	{
		$client = static::createAuthenticatedClient('adminusername', '123456');

		/** @var User $user */
		$user = $this->referenceRepository->getReference('adminusername');

		$client->request(
			Request::METHOD_POST,
			'/API/v1/Recipient/Bucket' . $this->getUrl('recipient_bucket_add'),
			array(),
			array(),
			array(),
			json_encode(array(
				'name'			=>'bucket3'
			))
		);

		//$this->assertEquals('{"id":3,"name":"bucket3"}', $client->getResponse()->getContent());
		$res = json_decode($client->getResponse()->getContent(), true);
		$this->assertEquals('bucket3', $res['name']);
		$this->assertEquals(200, $client->getResponse()->getStatusCode());
	}
}