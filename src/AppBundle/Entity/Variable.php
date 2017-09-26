<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation as JMS;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity()
 * @ORM\Table(name="variable", uniqueConstraints={
 *     @ORM\UniqueConstraint(columns={"company_id", "name"})
 * })
 * @UniqueEntity(fields={"company", "name"}, groups={"save"})
 *
 * @JMS\ExclusionPolicy("all")
 */
class Variable
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
	 *
	 * @ORM\ManyToOne(targetEntity="Company", inversedBy="variables", cascade={"persist"})
	 * @ORM\JoinColumn(name="company_id", referencedColumnName="id")
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
	 * @return mixed
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
	 * @param Company $company
	 * @return Variable
	 */
	public function setCompany(Company $company): Variable
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
	 * @return Variable
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
