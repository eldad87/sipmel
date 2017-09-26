<?php

namespace AppBundle\Entity;

use AppBundle\Request\API\CompanyAwareInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation as JMS;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity()
 * @ORM\Table(name="recipient_list", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"company_id", "name"})
 * })
 * @UniqueEntity(fields={"company", "name"}, groups={"save"})
 *
 * @JMS\ExclusionPolicy("all")
 */
class RecipientBucket implements CompanyAwareInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
	 *
	 * @JMS\Expose()
	 * @JMS\Groups({"save_response"})
	 * @JMS\Type(name="integer")
     */
    private $id;

	/**
	 * @var Company
	 * @Assert\NotBlank(groups={"save"})
	 *
	 * @ORM\ManyToOne(targetEntity="Company", inversedBy="recipientBuckets")
	 * @ORM\JoinColumn(name="company_id", referencedColumnName="id", nullable=false)
	 *
	 * @JMS\Type("AppBundle\Entity\Company")
	 */
	private $company;

    /**
	 * @Assert\Length(min="2", max="25", groups={"save"})
	 *
	 * @ORM\Column(type="string", length=25, nullable=false)
	 *
	 * @JMS\Expose()
	 * @JMS\Groups({"save", "save_response"})
	 * @JMS\Type(name="string")
     */
    private $name;

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
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return Company|Null
	 */
	public function getCompany()
	{
		return $this->company;
	}

	/**
	 * @param Company $company
	 * @return $this
	 */
	public function setCompany(Company $company)
	{
		$this->company = $company;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param mixed $name
	 * @return $this
	 */
	public function setName($name)
	{
		$this->name = $name;
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
