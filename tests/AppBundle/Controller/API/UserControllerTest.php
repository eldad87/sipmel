<?php

namespace Tests\AppBundle\Controller;

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
 * Class UserControllerTest
 * @package Tests\AppBundle\Controller
 */
class UserControllerTest extends WebTestCase
{
	public function testChangePassword()
	{

		//$client = $this->client = static::createClient();
		/*$session = $this->client->getContainer()->get('session');

		// the firewall context defaults to the firewall name
		$firewallContext = 'secured_area';

		$token = new UsernamePasswordToken('admin', null, $firewallContext, array('ROLE_API'));
		$session->set('_security_'.$firewallContext, serialize($token));
		$session->save();

		$cookie = new Cookie($session->getName(), $session->getId());
		$this->client->getCookieJar()->set($cookie);*/
		//

		/*$client = static::createClient();

		$user = new User();
		$user->setUsername('username')
			->setEmail('email@local.local')
			->setRoles(['ROLE_API'])
			->setPassword(123456);

		$password = $client->getContainer()->get('security.password_encoder')
			->encodePassword($user, $user->getPassword());
		$user->setPassword($password);

		$token = new UsernamePasswordToken($user, $user->getPassword(), 'api_private', $user->getRoles());


		$client->getContainer()->get('security.token_storage')->setToken($token);


		$client->request( Request::METHOD_GET, '/API/v1/User/Access/Check');*/


		/*$client->request(Request::METHOD_GET, '/API/v1/User/Access/Check.json', array(), array(), array(
			'PHP_AUTH_USER' => 'api',
			'PHP_AUTH_PW'   => 'api',
		));*/

		/*$client = new Client(['base_uri'=>'http://localhost:80']);
		$response = $client->get('/API/v1/User/Access/Check');


		echo $response->getStatusCode();
		echo $response->getBody();*/

		/*$client = static::createClient(array(), array('HTTP_content-type' => 'application/json','HTTP_accept' => 'application/json'));
		$client->request(
			Request::METHOD_POST,
			'/API/v1/User/Access/Token',
			array(),
			array(),
			array('HTTP_content-type' => 'application/json','HTTP_accept' => 'application/json'),
			\GuzzleHttp\json_encode(array(
				'username'=>'username',
				'password'=>'123456',
			))
		);*/


/*		//{"code":401,"message":"Bad credentials"}
		$client->request(Request::METHOD_POST, '/API/v1/login_check', array(), array(), array(
			'PHP_AUTH_USER' => 'username',
			'PHP_AUTH_PW'   => '123456',
		));*/



/*//Work!! Generate token
$client = static::createClient(array(), array('HTTP_content-type' => 'application/json','HTTP_accept' => 'application/json'));
$client->request(Request::METHOD_POST, '/API/Basic/login_check',
	array(
		'_username'=>'username',
		'_password'=>'123456',
	)
);*/

//Work!!! Check something
$client = $this->client = static::createClient(array(), array(
	'HTTP_CONTENT_TYPE' => 'application/json', 'HTTP_Accept' => 'application/json',
	'HTTP_Authorization'=>'Bearer eyJhbGciOiJSUzI1NiJ9.eyJyb2xlcyI6WyJST0xFX1NVUEVSX0FETUlOIiwiUk9MRV9BUEkiXSwidXNlcm5hbWUiOiJ1c2VybmFtZSIsImlhdCI6MTUwNjM0MjEwOCwiZXhwIjoxNTA2MzQ1NzA4fQ.sJOx-sa5N5JxTQlKs1ub3lGcv7JRc88zHt4mq4fagV_t54JCJ7QMf_PrTzD7NqeZyZs1NHF8xwrbF_YtTOjtFlTjZMMohLsqAsKoL0OqXxp7sf0G5m7AZX-Nk_4mI920nSc9ya8KKbtOB886EBYkju4uVZagVKBoOKxyOkYo_eYp8MnMUGiU3a6ifuDoP5l1DTi7XlveD85pppW4MuIwkf4Bzar674joE4CP6CHn08Hvpxs08DkNoRXuiIgyq8Bv-sz-nL7gA-UDEGqHCnXpIEO2scfuo71RbZ2ZPeqAq0aEBmh7XTWBSc4hVk9moxn4UV9gtcnDTZZlp7slO_KNDAEF8LneEdD60fwaEX-T0LDEovu3hI0AKcufjvogIGIjnNClPub9emftAMWk2c3ZQFb0q4JnnND4A8KxGOYLHl0I0GfbZevQcToFDb-ZgETFRttODCPax--2ysPKfrDvXauyi5qnBIJaDH3HaSpGy5JH3BgvnoFT482oWGaq11hyEkOx3usIDCrlehhbnqg3HIBap3-cN0dQle8LdvI94_39HW_MI9IXtj_EpM8Bgp1RjjlK45ItyyB1NT6-kE4U6M4Mu3oH4erjpaxuzJcIt5yxM9EPHyelokVaD69zWDAwHuzr3DdsEtx2pFjW9Nb-xPxJ-J9kM9iERb3QTmqgDT0'));
$client->request(
	'POST',
	'/API/v1/User/Access/Check.json'
);

//Work!!! Check something
/*$client = $this->client = static::createClient(array(), array(
	'HTTP_CONTENT_TYPE' => 'application/json', 'HTTP_Accept' => 'application/json',
	'HTTP_Authorization'=>'Bearer eyJhbGciOiJSUzI1NiJ9.eyJyb2xlcyI6WyJST0xFX1NVUEVSX0FETUlOIiwiUk9MRV9BUEkiXSwidXNlcm5hbWUiOiJ1c2VybmFtZSIsImlhdCI6MTUwNjM0MDg2MiwiZXhwIjoxNTA2MzQ0NDYyfQ.NcjX8K01P4u0yI8_MAs0sNrUg7poWxAV1iSuMjo88qHBSxezx-ayHUbjFGxYbvdtvEhm_1WQ61aOOfCYC0fN3un45MmOteMZyphK0lSiU3HK_A9JKHatYdG10RkSFtVTDD1P1rXWPgvAJm3z8iEeGhwTKzdudqOS11a_E6AT1PjQ1FIkiYycnNwWzS6ooQW9-J12aswyOvJ9UzlMswM6JoWYS9FOEmPihKZrrg9nxUIgTGZ-6wvcrdxQiDJi2ZwdXsWNtBZ5gVriFdlCSh2wDPt-u3Ba5oZY6oI-sozA0f1EZa6Zj7A-jiXYw9vDxhp2UZOUzdaZkyovde11x-62XI6uZUSks4h0CJfahrhzREcZVo1QBHprHrm-Q0IzsSh80y1N2HKOU9nj2637daliyEaCAMZgqJSkyNXFeE1fg0-MSb9zBfd6_xI7NrsuMjQJj56nRAZLg6fKvPc0-85g6VbKYRC50Y7WIxQ2nvoDjgBmY-ya4Dq6MdAXa8sF0qf_OXaRCKRvulHlH4U8zDMRSD83AWXh1nGbZx5t7j8kpYMQHxdxN2UFm4CVW0krT4PhMrXUWjftv8Ps2Zs3cmDo0aAcTlN2a_MKryrAvE-Fmwv0TgQaU_vNbNngdKrofnq2ewl_d74_8TKxrOWjG8_O8qavwL_TmdEiYYtmj0N_k-4'));

$client->request(
	'POST',
	'/API/v1/User/ChangePassword.json',
	array(),
	array(),
	array('HTTP_content-type' => 'application/json','HTTP_accept' => 'application/json'),
	\GuzzleHttp\json_encode(array(
		'password_new'=>'123456',
		'password_new_confirm'=>'123456',
		'password_current'=>'123456',
	))
);*/


		//echo $client->getResponse()->getStatusCode(); die;
		echo $client->getResponse()->getContent();
		die;


		// 	Unable to find template


		/*$client = $this->client = static::createClient(array(), array('Authorization'=>'Bearer eyJhbGciOiJSUzI1NiJ9.eyJyb2xlcyI6WyJST0xFX1NVUEVSX0FETUlOIl0sInVzZXJuYW1lIjoidXNlcm5hbWUiLCJpYXQiOjE1MDYzMzIyMjgsImV4cCI6MTUwNjMzNTgyOH0.YVOoN9_k61uwUqOdqurAnM0szBjQpEFC1uyRNuTGRH9a--BA8ad-q-F88K-EadS1NttXLXV_BqJQpDIWNB7IDCoKv_iBXEXPhpnQhK-NevLuWOHPUyDSiIgpnjPjxe8bPSxBUVhQA-1H9IVoMZ0JYYwhIhL-oiEDuCGl2lx3_CnF51GJRbdzNSIVkH9gfkUZIu4O4e6Vz3dSNUIzeor3bqB3IyO7pRmLKZkrQLZ3m2PEMZzj13uE6RS1NkZZWc2e2Od_BuIfUf0VJLao5zR5Gu27nfk9ccCasg3cRI6lzUUF4jbMBwRjKJ44rOncCn-73on7y8vHt_j2ko5Cv_eX-YGLTsfPkjTJ1GZ25xGD9HBjUA7HFFMT33eoqEWCLpsbUqa9RCf_z79JK2XXUAkBJzdRnrA6zIt2lA8wQ6SIDalpDmj85U_OoKdYB03GuS3_R1Oy23EsN2bHURjU4gFaiUFx5LCHTj4VCk5xNcXlsE1SvgSvjwsPGIn630vTS2o0ohcdZoCUzqpLSo5GBtcgJctI9yqRi_0guJAFPhrSqpJPQNkAR3qQlhl7Muhx8uulUBmRZaUgUaiciRiccbLRCiZWmQ4-R6SQnHPHqC_IaZIjOZDdmEgiVfVMxNigMa4u84kfrf777jWeTBNg4QBSKxY2eYABqOG7l3M5wzjAZ8A'));
		$client->request(
			Request::METHOD_GET,
			'/API/v1/User/Access/Check',
			array('Authorization'=>'Bearer eyJhbGciOiJSUzI1NiJ9.eyJyb2xlcyI6WyJST0xFX1NVUEVSX0FETUlOIl0sInVzZXJuYW1lIjoidXNlcm5hbWUiLCJpYXQiOjE1MDYzMzIyMjgsImV4cCI6MTUwNjMzNTgyOH0.YVOoN9_k61uwUqOdqurAnM0szBjQpEFC1uyRNuTGRH9a--BA8ad-q-F88K-EadS1NttXLXV_BqJQpDIWNB7IDCoKv_iBXEXPhpnQhK-NevLuWOHPUyDSiIgpnjPjxe8bPSxBUVhQA-1H9IVoMZ0JYYwhIhL-oiEDuCGl2lx3_CnF51GJRbdzNSIVkH9gfkUZIu4O4e6Vz3dSNUIzeor3bqB3IyO7pRmLKZkrQLZ3m2PEMZzj13uE6RS1NkZZWc2e2Od_BuIfUf0VJLao5zR5Gu27nfk9ccCasg3cRI6lzUUF4jbMBwRjKJ44rOncCn-73on7y8vHt_j2ko5Cv_eX-YGLTsfPkjTJ1GZ25xGD9HBjUA7HFFMT33eoqEWCLpsbUqa9RCf_z79JK2XXUAkBJzdRnrA6zIt2lA8wQ6SIDalpDmj85U_OoKdYB03GuS3_R1Oy23EsN2bHURjU4gFaiUFx5LCHTj4VCk5xNcXlsE1SvgSvjwsPGIn630vTS2o0ohcdZoCUzqpLSo5GBtcgJctI9yqRi_0guJAFPhrSqpJPQNkAR3qQlhl7Muhx8uulUBmRZaUgUaiciRiccbLRCiZWmQ4-R6SQnHPHqC_IaZIjOZDdmEgiVfVMxNigMa4u84kfrf777jWeTBNg4QBSKxY2eYABqOG7l3M5wzjAZ8A'),
			array(),
			array(
				'content-type' => 'application/json','accept' => 'application/json',
				'Authorization'=>'Bearer eyJhbGciOiJSUzI1NiJ9.eyJyb2xlcyI6WyJST0xFX1NVUEVSX0FETUlOIl0sInVzZXJuYW1lIjoidXNlcm5hbWUiLCJpYXQiOjE1MDYzMzIyMjgsImV4cCI6MTUwNjMzNTgyOH0.YVOoN9_k61uwUqOdqurAnM0szBjQpEFC1uyRNuTGRH9a--BA8ad-q-F88K-EadS1NttXLXV_BqJQpDIWNB7IDCoKv_iBXEXPhpnQhK-NevLuWOHPUyDSiIgpnjPjxe8bPSxBUVhQA-1H9IVoMZ0JYYwhIhL-oiEDuCGl2lx3_CnF51GJRbdzNSIVkH9gfkUZIu4O4e6Vz3dSNUIzeor3bqB3IyO7pRmLKZkrQLZ3m2PEMZzj13uE6RS1NkZZWc2e2Od_BuIfUf0VJLao5zR5Gu27nfk9ccCasg3cRI6lzUUF4jbMBwRjKJ44rOncCn-73on7y8vHt_j2ko5Cv_eX-YGLTsfPkjTJ1GZ25xGD9HBjUA7HFFMT33eoqEWCLpsbUqa9RCf_z79JK2XXUAkBJzdRnrA6zIt2lA8wQ6SIDalpDmj85U_OoKdYB03GuS3_R1Oy23EsN2bHURjU4gFaiUFx5LCHTj4VCk5xNcXlsE1SvgSvjwsPGIn630vTS2o0ohcdZoCUzqpLSo5GBtcgJctI9yqRi_0guJAFPhrSqpJPQNkAR3qQlhl7Muhx8uulUBmRZaUgUaiciRiccbLRCiZWmQ4-R6SQnHPHqC_IaZIjOZDdmEgiVfVMxNigMa4u84kfrf777jWeTBNg4QBSKxY2eYABqOG7l3M5wzjAZ8A'
			)
		);*/

		/*$client->request(Request::METHOD_POST, ,
			array(
				'username'=>'api',
				'password'=>'api',
			),
			array(),
			array(
				'PHP_AUTH_USER' => 'api',
				'PHP_AUTH_PW'   => 'api',
				'HTTP_ACCEPT' => 'application/json'
			)
		);*/

		echo $client->getResponse()->getContent();
		$this->assertEquals(200, $client->getResponse()->getStatusCode());
	}
}