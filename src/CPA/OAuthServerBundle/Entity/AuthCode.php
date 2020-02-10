<?php
// src/Acme/ApiBundle/Entity/AuthCode.php

namespace CPA\OAuthServerBundle\Entity;

use FOS\OAuthServerBundle\Entity\AuthCode as BaseAuthCode;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="auth_code")
 * @ORM\Entity(repositoryClass="CPA\OAuthServerBundle\Repository\AuthCodeRepository")
 */

class AuthCode extends BaseAuthCode
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $client;

    /**
     * @ORM\ManyToOne(targetEntity="CPA\UserBundle\Entity\User")
     */
    protected $user;
    
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
     * Get user
     *
     * @return \CPA\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

}