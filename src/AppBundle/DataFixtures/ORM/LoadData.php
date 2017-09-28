<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Category;
use AppBundle\Entity\Company;
use AppBundle\Entity\Content;
use AppBundle\Entity\Bucket;
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
		$company2 = $this->generateCompany('cNamew');
		$manager->persist($company);
		$manager->persist($company2);
		$manager->flush();

		$user = $this->generateUser($company, 'adminusername@local.local', 'adminusername', 123456);
		$manager->persist($user);

		$variable = $this->generateVariable($company, 'var1');
		$variable2 = $this->generateVariable($company, 'var2');
		$manager->persist($variable);
		$manager->persist($variable2);

		$bucket = $this->generateBucket($company, 'bucket1');
		$bucket2 = $this->generateBucket($company, 'bucket2');
		$manager->persist($bucket);
		$manager->persist($bucket2);

		$category = $this->generateCategory($company, 'category1');
		$category2 = $this->generateCategory($company, 'category2');
		$category3 = $this->generateCategory($company2, 'category3_customer2');
		$manager->persist($category);
		$manager->persist($category2);
		$manager->persist($category3);
		$manager->flush();

		$content = $this->generateContent($category, 'content1_en', 'en');
		$content2 = $this->generateContent($category2, 'content2_fr', 'fr');
		$content3 = $this->generateContent($category3, 'content3_customer2', 'fr');
		$manager->persist($content);
		$manager->persist($content2);
		$manager->persist($content3);

		$this->setReference($user->getUsername(), $user);

		$manager->flush();

		return true;
	}

    private function generateContent(Category $category, $name='content', $fallbackLanguage='en')
	{
		$content = new Content();
		$content->setName($name);
		$content->setFallbackLanguage($fallbackLanguage);
		$content->setCompany($category->getCompany());
		$content->setCategory($category);
		return $content;
	}
    private function generateCategory(Company $company, $name='category')
	{
		$category = new Category();
		$category->setCompany($company);
		$category->setName($name);
		return $category;
	}

    private function generateBucket(Company $company, $name='buck')
	{
		$bucket = new Bucket();
		$bucket->setCompany($company);
		$bucket->setName($name);
		return $bucket;
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
    private function generateCompany($name='company', $isEnabled=true)
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
