<?php

// src/Acme/ApiBundle/Entity/Client.php

namespace CPA\OAuthServerBundle\Entity;

use FOS\OAuthServerBundle\Entity\Client as BaseClient;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="client")
 */
class Client extends BaseClient {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    

    public function __construct() {
        parent::__construct();
//         $this->users= new ArrayCollection();
        // your own logic
    }

    

}
