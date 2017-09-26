<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Company;
use AppBundle\Entity\User;
use AppBundle\Entity\Variable;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

use Doctrine\Common\DataFixtures\AbstractFixture;

class LoadData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface
{
	/** @var ContainerInterface */
    private $container;

	public function load(ObjectManager $manager)
	{

		$company = $this->generateCompany('cName');
		$manager->persist($company);
		$manager->flush();

		$user = $this->generateUser($company, 'adminusername@local.local', 'adminusername', 123456);
		$manager->persist($user);
		$manager->flush();

		$variable = $this->generateVariable($company, 'var1');
		$variable2 = $this->generateVariable($company, 'var2');
		$manager->persist($variable);
		$manager->persist($variable2);
		$manager->flush();

		$this->setReference($user->getUsername(), $user);

		return true;
	}



    private function generateVariable(Company $company, $name='var')
	{
		$variable = new Variable();
		$variable->setCompany($company);
		$variable->setName($name);
		return $variable;
	}

	/**
	 * Generate a Company object
	 * @param string $name
	 * @param boolean $isEnabled
	 * @return Company
	 */
    private function generateCompany($name='cName', $isEnabled=true)
	{
		$company = new Company();
		$company->setName($name);
		$company->setIsEnabled($isEnabled);
		return $company;
	}

	/**
	 * Generate a User object
	 * @param Company $company
	 * @param $email
	 * @param $username
	 * @param int $password
	 * @param $firstName
	 * @param $lastName
	 * @param array $roles
	 * @return User
	 */
    private function generateUser(Company $company, $email, $username, $password=123456, $firstName='fName', $lastName='lName', $roles=['ROLE_ADMIN'], $isEnabled=true)
	{
		$user = new User();
		$user->setCompany($company);
		$user->setEmail($email);
		$user->setUsername($username);
		$user->setFirstName($firstName);
		$user->setLastName($lastName);
		$user->setRoles($roles);
		$user->setIsEnabled($isEnabled);

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
