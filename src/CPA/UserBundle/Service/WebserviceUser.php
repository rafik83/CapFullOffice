<?php

namespace CPA\UserBundle\Service;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;

class WebserviceUser implements UserInterface, EquatableInterface {

    private $username;
    private $password;
    private $salt;
    private $roles;

    public function __construct($username, $password, $salt, array $roles) {
        $this->username = $username;
        $this->password = $password;
        $this->salt = $salt;
        $this->roles = $roles;
//        dump($this->username);
//        dump($this->password);
//        dump($this->salt );
//        dump($this->roles);
//
//        die('WebserviceUser + __construct');
    }

    public function getRoles() {
        return $this->roles;
    }

    public function getPassword() {
        return $this->password;
        die('WebserviceUser + getPassword');
    }

    public function getSalt() {
        return $this->salt;
        die('WebserviceUser + getSalt');
    }

    public function getUsername() {
        return $this->username;
        die('WebserviceUser + getUsername');
    }

    public function eraseCredentials() {
          die('WebserviceUser + eraseCredentials');
    }

    public function isEqualTo(UserInterface $user) {
       die('isEqualTo');
        if (!$user instanceof WebserviceUser) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->salt !== $user->getSalt()) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        return true;
    }

}
