<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity()
 * @ORM\Table(name="company")
 */
class Company
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

	/**
	 * @var User[]
	 * @ORM\OneToMany(targetEntity="User", mappedBy="company", cascade={"persist", "remove"})
	 * @JMS\Type("ArrayCollection<AppBundle\Entity\User>"))
	 */
	private $users;

    /**
     * @ORM\Column(type="string", length=25, nullable=false)
     */
    private $name;

    /**
     *
     * @ORM\Column(type="boolean")
     */
    private $isEnabled;

	public function __construct()
	{
		$this->users = new ArrayCollection();
	}

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
	 * Get users
	 * @return User[]
	 */
	public function getUsers()
	{
		return $this->users;
	}

	/**
	 * @param User $user
	 * @return Company
	 */
	public function addUser($user)
	{
		$this->users->add($user);
		return $this;
	}

	/**
	 * @param User $user
	 * @return Company
	 */
	public function removeUser($user)
	{
		$this->users->removeElement($user);
		return $this;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return Company
	 */
	public function setName($name)
	{
		$this->name = $name;
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
	 * @return Company
	 */
	public function setIsEnabled($isEnabled)
	{
		$this->isEnabled = $isEnabled;
		return $this;
	}
}
