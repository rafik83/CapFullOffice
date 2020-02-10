<?php

namespace CPA\UserBundle\Provider;

use CPA\UserBundle\Service\WebserviceUser;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\NoResultException;

class WebserviceUserProvider implements UserProviderInterface {

    protected $userRepository;

    public function __construct(ObjectRepository $userRepository) {
        $this->userRepository = $userRepository;
//        var_dump($userRepository);
//        die('WebserviceUserProvider + __construct');
    }

    public function loadUserByUsername($username) {
        // make a call to your webservice here
//        $userData = ...
        // pretend it returns an array on success, false if there is no user
//        dump($username);
//        die('WebserviceUserProvider + loadUserByUsername');
        $q = $this->userRepository
                ->createQueryBuilder('u')
                ->where('u.username = :username')
                ->setParameter('username', $username)
                ->getQuery();







        try {
            $userData = $q->getSingleResult();
//            die('WebserviceUserProvider + loadUserByUsername');
        } catch (NoResultException $e) {
            $message = sprintf(
                    'Unable to find an active admin CPAUserBundle:User object identified by "%s".', $username
            );
            throw new UsernameNotFoundException($message, 0, $e);
        }


//        die('apres try + catch');

        if ($userData) {
            $username = $userData->getUsername();
            $password = $userData->getPassword();
            $salt = $userData->getSalt();
            $roles = $userData->getRoles();

//            die('avant return');
            return new WebserviceUser($username, $password, $salt, $roles);
        }

        throw new UsernameNotFoundException(
        sprintf('Username "%s" does not exist.', $username)
        );
    }

    public function refreshUser(UserInterface $user) {
        die('WebserviceUserProvider + refreshUser');
        if (!$user instanceof WebserviceUser) {
            throw new UnsupportedUserException(
            sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class) {
        die('WebserviceUserProvider + supportsClass');
        return WebserviceUser::class === $class;
    }

}
