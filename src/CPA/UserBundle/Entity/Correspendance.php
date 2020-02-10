<?php

namespace CPA\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CPA\UserBundle\Repository\CorrespendanceRepository")
 * @ORM\Table(name="corres_pendance")

 */
class Correspendance 
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="username", type="string", length=255, nullable=false)
     */
    private $username;

    /**
     * @ORM\Column(name="nom_bdd", type="string", length=255, nullable=false)
     */
    private $nomBdd;
    
   
    
    public function getId() {
        return $this->id;
    }

    public function __construct()
    {
       
       
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return Correspendace
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
     * Set nomBdd
     *
     * @param string $nomBdd
     *
     * @return Correspendace
     */
    public function setNomBdd($nomBdd)
    {
        $this->nomBdd = $nomBdd;

        return $this;
    }

    /**
     * Get nomBdd
     *
     * @return string
     */
    public function getNomBdd()
    {
        return $this->nomBdd;
    }
}
