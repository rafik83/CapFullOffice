<?php

namespace CPA\UserBundle\Service;

use \Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

class MyEncoderSha512 implements PasswordEncoderInterface {

    public function encodePassword($raw, $salt) {

//        dump(hash('sha512', $salt . $raw));
//        die('MyEncoderSha512 + encodePassword');
        return hash('sha512', $salt . $raw);
    }

    public function isPasswordValid($encoded, $raw, $salt) {

//        dump($encoded);
//        dump($raw);
//        dump($salt);
//        die('MyEncoderSha512 + isPasswordValid');
        return $encoded === $this->encodePassword($raw, $salt);
    }

}
