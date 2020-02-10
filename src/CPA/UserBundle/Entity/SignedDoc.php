<?php

namespace CPA\UserBundle\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="signed_doc")
 * @ORM\Entity(repositoryClass="CPA\UserBundle\Repository\SignedDocRepository")
 */
class SignedDoc {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="record", type="string", length=255)
     */
    private $record;

    /**
     * @ORM\Column(name="signature", type="string", length=255)
     */
    private $signature;

    /**
     * @ORM\Column(name="doc", type="string", length=255)
     */
    private $doc;

    /**
     * @var integer
     * 
     * @ORM\Column(name="docSigne_fromBase", type="integer", length=11)
     */
    private $docSigne_fromBase;

    /**
     * @var integer
     * 
     * @ORM\Column(name="idSalary_fromBase", type="integer", length=11)
     */
    private $idSalary_fromBase;

    /**
     * @ORM\Column(name="ext", type="string", length=255, nullable=true)
     */
    private $ext;

    /**
     * @ORM\ManyToOne(targetEntity="CPA\UserBundle\Entity\Salary", cascade={"remove"})
     * @ORM\JoinColumn(name="salary_id", nullable=false)
     */
    private $salary;

    /**
     * @ORM\Column(name="month", type="integer")
     */
    private $month;

    /**
     * @ORM\Column(name="year", type="integer")
     */
    private $year;

    /**
     * @ORM\Column(name="size", type="integer")
     */
    private $size;

    /**
     * @ORM\Column(name="obsolete", type="boolean", nullable=true)
     */
    private $obsolete;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(name="date_debut", type="datetime")
     */
    private $date_debut;

    /**
     * @ORM\Column(name="date_fin", type="datetime")
     */
    private $date_fin;

    public function __construct() {
        $this->obsolete = 0;
        $this->createdAt = new \Datetime();
        $this->date_debut = new \Datetime();
        $this->date_fin = new \Datetime();
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
     * Set record
     *
     * @param string $record
     *
     * @return SignedDoc
     */
    public function setRecord($record)
    {
        $this->record = $record;

        return $this;
    }

    /**
     * Get record
     *
     * @return string
     */
    public function getRecord()
    {
        return $this->record;
    }

    /**
     * Set signature
     *
     * @param string $signature
     *
     * @return SignedDoc
     */
    public function setSignature($signature)
    {
        $this->signature = $signature;

        return $this;
    }

    /**
     * Get signature
     *
     * @return string
     */
    public function getSignature()
    {
        return $this->signature;
    }

    /**
     * Set doc
     *
     * @param string $doc
     *
     * @return SignedDoc
     */
    public function setDoc($doc)
    {
        $this->doc = $doc;

        return $this;
    }

    /**
     * Get doc
     *
     * @return string
     */
    public function getDoc()
    {
        return $this->doc;
    }

    /**
     * Set docSigneFromBase
     *
     * @param integer $docSigneFromBase
     *
     * @return SignedDoc
     */
    public function setDocSigneFromBase($docSigneFromBase)
    {
        $this->docSigne_fromBase = $docSigneFromBase;

        return $this;
    }

    /**
     * Get docSigneFromBase
     *
     * @return integer
     */
    public function getDocSigneFromBase()
    {
        return $this->docSigne_fromBase;
    }

    /**
     * Set idSalaryFromBase
     *
     * @param integer $idSalaryFromBase
     *
     * @return SignedDoc
     */
    public function setIdSalaryFromBase($idSalaryFromBase)
    {
        $this->idSalary_fromBase = $idSalaryFromBase;

        return $this;
    }

    /**
     * Get idSalaryFromBase
     *
     * @return integer
     */
    public function getIdSalaryFromBase()
    {
        return $this->idSalary_fromBase;
    }

    /**
     * Set ext
     *
     * @param string $ext
     *
     * @return SignedDoc
     */
    public function setExt($ext)
    {
        $this->ext = $ext;

        return $this;
    }

    /**
     * Get ext
     *
     * @return string
     */
    public function getExt()
    {
        return $this->ext;
    }

    /**
     * Set month
     *
     * @param integer $month
     *
     * @return SignedDoc
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * Get month
     *
     * @return integer
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Set year
     *
     * @param integer $year
     *
     * @return SignedDoc
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set size
     *
     * @param integer $size
     *
     * @return SignedDoc
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return integer
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set obsolete
     *
     * @param boolean $obsolete
     *
     * @return SignedDoc
     */
    public function setObsolete($obsolete)
    {
        $this->obsolete = $obsolete;

        return $this;
    }

    /**
     * Get obsolete
     *
     * @return boolean
     */
    public function getObsolete()
    {
        return $this->obsolete;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return SignedDoc
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return SignedDoc
     */
    public function setDateDebut($dateDebut)
    {
        $this->date_debut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->date_debut;
    }

    /**
     * Set dateFin
     *
     * @param \DateTime $dateFin
     *
     * @return SignedDoc
     */
    public function setDateFin($dateFin)
    {
        $this->date_fin = $dateFin;

        return $this;
    }

    /**
     * Get dateFin
     *
     * @return \DateTime
     */
    public function getDateFin()
    {
        return $this->date_fin;
    }

    /**
     * Set salary
     *
     * @param \CPA\UserBundle\Entity\Salary $salary
     *
     * @return SignedDoc
     */
    public function setSalary(\CPA\UserBundle\Entity\Salary $salary)
    {
        $this->salary = $salary;

        return $this;
    }

    /**
     * Get salary
     *
     * @return \CPA\UserBundle\Entity\Salary
     */
    public function getSalary()
    {
        return $this->salary;
    }
}
