<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Company;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class LoadFixtures implements FixtureInterface, ContainerAwareInterface
{
	/** @var ContainerInterface */
    private $container;

	public function load(ObjectManager $manager)
	{
		$company = $this->generateCompany('cName');
		$user = $this->generateUser($company, 'email@local.local', 'username');
		$manager->persist($user);
		$manager->flush();

		return true;
	}

	/**
	 * Generate a Company object
	 * @param $name
	 * @return Company
	 */
    private function generateCompany($name='cName')
	{
		$company = new Company();
		$company->setName($name);

		return $company;
	}

	/**
	 * Generate a User object
	 * @param Company $company
	 * @param $email
	 * @param $username
	 * @param $firstName
	 * @param $lastName
	 * @param int $password
	 * @param array $roles
	 * @return User
	 */
    private function generateUser(Company $company, $email, $username, $firstName='fName', $lastName='lName', $password=123456, $roles=['ROLE_ADMIN'])
	{
		$user = new User();
		$user->setCompany($company);
		$user->setEmail($email);
		$user->setUsername($username);
		$user->setFirstName($firstName);
		$user->setLastName($lastName);
		$user->setRoles($roles);

		/** @var PasswordEncoderInterface $securityPasswordEncoder */
		$securityPasswordEncoder = $this->container->get('security.password_encoder');
		$encodedPassword = $securityPasswordEncoder->encodePassword($user, $password);
		$user->setPassword($encodedPassword);

		return $user;
	}

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
