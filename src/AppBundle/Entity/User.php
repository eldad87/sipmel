<?php

namespace AppBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation as JMS;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="user")
 *
 * @UniqueEntity("email", groups={"register"})
 * @UniqueEntity("username", groups={"register"})
 *
 * @JMS\ExclusionPolicy("all")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

	/**
	 * @var Company
	 *
	 * @ORM\ManyToOne(targetEntity="Company", inversedBy="users", cascade={"persist"})
	 * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
	 *
	 * @JMS\Expose()
	 * @JMS\Groups({"register", "register_response"})
	 * @JMS\Type("AppBundle\Entity\Company")
	 */
	private $company;

	/**
	 * @ORM\Column(type="boolean", nullable=false)
	 *
	 * @JMS\Type("boolean")
	 */
	private $isEnabled;

    /**
	 * @Assert\Length(min="2", max="25", groups={"register"})
	 *
	 * @ORM\Column(type="string", length=25, nullable=false)
	 *
	 * @JMS\Expose()
	 * @JMS\Groups({"register", "register_response"})
	 * @JMS\Type(name="string")
     */
    private $firstName;

    /**
	 * @Assert\Length(min="2", max="25", groups={"register"})
	 *
	 * @ORM\Column(type="string", length=25, nullable=false)
	 *
	 * @JMS\Expose()
	 * @JMS\Groups({"register", "register_response"})
	 * @JMS\Type(name="string")
     */
    private $lastName;

    /**
	 *
	 * @Assert\Length(min="5", max="25", groups={"register"})
	 *
	 * @ORM\Column(type="string", unique=true, length=25, nullable=false)
	 *
	 * @JMS\Expose()
	 * @JMS\Groups({"register", "register_response", "login"})
	 * @JMS\Type(name="string")
     */
    private $username;

    /**
	 * @Assert\Length(min="5", max="50", groups={"register"})
	 * @Assert\Email(groups={"register"})
	 *
	 * @ORM\Column(type="string", unique=true, length=50, nullable=false)
	 *
	 * @JMS\Expose()
	 * @JMS\Groups({"register", "register_response"})
	 * @JMS\Type(name="string")
     */
    private $email;

    /**
	 * @ORM\Column(type="string", length=72, nullable=false)
	 *
	 * @JMS\Expose()
	 * @JMS\Groups({"register", "login"})
	 * @JMS\Type(name="string")
     */
    private $password;

    /**
     * @ORM\Column(type="json_array")
     */
    private $roles;

	/**
	 * @var \DateTime $created
	 *
	 * @Gedmo\Timestampable(on="create")
	 * @ORM\Column(type="datetime")
	 */
	private $created;

	/**
	 * @var \DateTime $updated
	 *
	 * @Gedmo\Timestampable(on="update")
	 * @ORM\Column(type="datetime")
	 */
	private $updated;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

	/**
	 * @return Company
	 */
	public function getCompany(): Company
	{
		return $this->company;
	}

	/**
	 * @param Company $Company
	 * @return User
	 */
	public function setCompany(Company $Company): User
	{
		$this->company = $Company;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getIsEnabled()
	{
		return $this->isEnabled;
	}

	/**
	 * @param boolean $isEnabled
	 * @return User
	 */
	public function setIsEnabled($isEnabled)
	{
		$this->isEnabled = $isEnabled;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getFirstName()
	{
		return $this->firstName;
	}

	/**
	 * @param string $firstName
	 * @return User
	 */
	public function setFirstName($firstName)
	{
		$this->firstName = $firstName;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getLastName()
	{
		return $this->lastName;
	}

	/**
	 * @param string $lastName
	 * @return User
	 */
	public function setLastName($lastName)
	{
		$this->lastName = $lastName;
		return $this;
	}

    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Returns the roles or permissions granted to the user for security.
     */
    public function getRoles()
    {
        $roles = $this->roles;
        // guarantees that a user always has at least one role for security
        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }
        return array_unique($roles);
    }

	/**
	 * @param $roles
	 * @return User
	 */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

	public function getCreated()
	{
		return $this->created;
	}

	public function getUpdated()
	{
		return $this->updated;
	}

	/**
     * Returns the salt that was originally used to encode the password.
     */
    public function getSalt()
    {
        // See "Do you need to use a Salt?" at http://symfony.com/doc/current/cookbook/security/entity_provider.html
        // we're using bcrypt in security.yml to encode the password, so
        // the salt value is built-in and you don't have to generate one
        return;
    }

    /**
     * Removes sensitive data from the user.
     */
    public function eraseCredentials()
    {
        // if you had a plainPassword property, you'd nullify it here
        // $this->plainPassword = null;
    }
}
