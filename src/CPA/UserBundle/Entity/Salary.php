<?php


namespace CPA\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
//use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="salary")
 * @ORM\Entity(repositoryClass="CPA\UserBundle\Repository\SalaryRepository")
 */
class Salary {

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var integer
     * 
     * @ORM\Column(name="salary_fromBase", type="integer", length=11)
     */
    private $salary_from_base;
    

    /**
     * @var string
     * 
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(name="company_name", type="string", length=255, nullable=true)
     */
    private $company;

   
    /**
     * @ORM\ManyToOne(targetEntity="CPA\UserBundle\Entity\User", cascade={"persist", "remove"})
     * @ORM\JoinColumn( name="user_id", nullable=false);
     */
    private $user;

    /**
     * @ORM\Column(name="matricule", type="string")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $matricule;

    /**
     * @ORM\Column(name="num_secu", type="string", length=255, nullable=true)
     */
    private $numSecu;
    
    
    /**
     * @ORM\Column(name="employeur", type="string", length=255, nullable=true)
     */
    private $employeur;


    public function __construct() {
//        $this->createdAt = new \Datetime();
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
     * Set salaryFromBase
     *
     * @param integer $salaryFromBase
     *
     * @return Salary
     */
    public function setSalaryFromBase($salaryFromBase)
    {
        $this->salary_from_base = $salaryFromBase;

        return $this;
    }

    /**
     * Get salaryFromBase
     *
     * @return integer
     */
    public function getSalaryFromBase()
    {
        return $this->salary_from_base;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Salary
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Salary
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set company
     *
     * @param string $company
     *
     * @return Salary
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Set matricule
     *
     * @param string $matricule
     *
     * @return Salary
     */
    public function setMatricule($matricule)
    {
        $this->matricule = $matricule;

        return $this;
    }

    /**
     * Get matricule
     *
     * @return string
     */
    public function getMatricule()
    {
        return $this->matricule;
    }

    /**
     * Set numSecu
     *
     * @param string $numSecu
     *
     * @return Salary
     */
    public function setNumSecu($numSecu)
    {
        $this->numSecu = $numSecu;

        return $this;
    }

    /**
     * Get numSecu
     *
     * @return string
     */
    public function getNumSecu()
    {
        return $this->numSecu;
    }

    /**
     * Set employeur
     *
     * @param string $employeur
     *
     * @return Salary
     */
    public function setEmployeur($employeur)
    {
        $this->employeur = $employeur;

        return $this;
    }

    /**
     * Get employeur
     *
     * @return string
     */
    public function getEmployeur()
    {
        return $this->employeur;
    }

    /**
     * Set user
     *
     * @param \CPA\UserBundle\Entity\User $user
     *
     * @return Salary
     */
    public function setUser(\CPA\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \CPA\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
