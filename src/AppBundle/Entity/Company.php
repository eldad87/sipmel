<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity()
 * @ORM\Table(name="company")
 *
 * @JMS\ExclusionPolicy("all")
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
	 * @var ArrayCollection<User>
	 *
	 * @ORM\OneToMany(targetEntity="User", mappedBy="company", cascade={"persist", "remove"})
	 *
	 * @JMS\Type("ArrayCollection<AppBundle\Entity\User>"))
	 */
	private $users;
	
	/**
	 * @var ArrayCollection<Variable>
	 *
	 * @ORM\OneToMany(targetEntity="Variable", mappedBy="company", cascade={"persist", "remove"})
	 *
	 * @JMS\Type("ArrayCollection<AppBundle\Entity\Variable>"))
	 */
	private $variables;	
	/**
	 * @var ArrayCollection<Content>
	 *
	 * @ORM\OneToMany(targetEntity="Content", mappedBy="company", cascade={"persist", "remove"})
	 *
	 * @JMS\Type("ArrayCollection<AppBundle\Entity\Content>"))
	 */
	private $contents;
	
	/**
	 * @var ArrayCollection<RecipientBucket>
	 *
	 * @ORM\OneToMany(targetEntity="Variable", mappedBy="company", cascade={"persist", "remove"})
	 *
	 * @JMS\Type("ArrayCollection<AppBundle\Entity\RecipientBucket>"))
	 */
	private $recipientBuckets;

    /**
	 * @Assert\Length(min="2", max="25", groups={"register"})
	 * @Assert\NotBlank(groups={"register"})
	 *
	 * @ORM\Column(type="string", length=25, nullable=false)
	 *
	 * @JMS\Expose()
	 * @JMS\Groups({"register", "register_response", "list_response"})
	 * @JMS\Type(name="string")
     */
    private $name;

    /**
     *
     * @ORM\Column(type="boolean")
     */
    private $isEnabled;

	/**
	 * @var \DateTime $created
	 *
	 * @Gedmo\Timestampable(on="create")
	 * @ORM\Column(type="datetime")
	 *
	 * @JMS\Expose()
	 * @JMS\Groups({"list_response"})
	 * @JMS\Type(name="DateTime<'Y-m-d H:i:s'>")
	 */
	private $created;

	/**
	 * @var \DateTime $updated
	 *
	 * @Gedmo\Timestampable(on="update")
	 * @ORM\Column(type="datetime")
	 *
	 * @JMS\Expose()
	 * @JMS\Groups({"list_response"})
	 * @JMS\Type(name="DateTime<'Y-m-d H:i:s'>")
	 */
	private $updated;

	public function __construct()
	{
		$this->users = new ArrayCollection();
		$this->variables = new ArrayCollection();
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
	 * @return ArrayCollection<User>
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
	 * Get variables
	 * @return ArrayCollection<Variable>
	 */
	public function getVariables()
	{
		return $this->variables;
	}

	/**
	 * @param Variable $variable
	 * @return Company
	 */
	public function addVariable($variable)
	{
		$this->variables->add($variable);
		return $this;
	}

	/**
	 * @param Variable $variable
	 * @return Company
	 */
	public function removeVariable($variable)
	{
		$this->variables->removeElement($variable);
		return $this;
	}

	/**
	 * Get contents
	 * @return ArrayCollection<Content>
	 */
	public function getContents()
	{
		return $this->contents;
	}

	/**
	 * @param Content $content
	 * @return Company
	 */
	public function addContent($content)
	{
		$this->contents->add($content);
		return $this;
	}

	/**
	 * @param Content $content
	 * @return Company
	 */
	public function removeContent($content)
	{
		$this->contents->removeElement($content);
		return $this;
	}

	/**
	 * Get recipientBuckets
	 * @return ArrayCollection<RecipientBucket>
	 */
	public function getRecipientBuckets()
	{
		return $this->recipientBuckets;
	}

	/**
	 * @param RecipientBucket $recipientBucket
	 * @return Company
	 */
	public function addRecipientBucket($recipientBucket)
	{
		$this->recipientBuckets->add($recipientBucket);
		return $this;
	}

	/**
	 * @param RecipientBucket $recipientBucket
	 * @return Company
	 */
	public function removeRecipientBucket($recipientBucket)
	{
		$this->recipientBuckets->removeElement($recipientBucket);
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

	public function getCreated()
	{
		return $this->created;
	}

	public function getUpdated()
	{
		return $this->updated;
	}
}
