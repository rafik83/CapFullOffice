<?php

namespace CPA\OAuthServerBundle\DataFixtures\ORM;

//use AppBundle\Entity\Genus;
//use Doctrine\Common\DataFixtures\FixtureInterface;
//use Symfony\Bundle\FrameworkBundle\Test;
//use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
//use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use CPA\UserBundle\Entity\Correspendance;
use CPA\OAuthServerBundle\Entity\Client;
use CPA\UserBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Nelmio\Alice\Fixtures;
use OAuth2\OAuth2;

class LoadClientData implements FixtureInterface, ContainerAwareInterface {

    /**
     * @var ContainerInterface
     */
    private $container;

//
    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
        //ContainerInterface
        
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager) {
        //extends AbstractFixture implements OrderedFixtureInterface
//        require_once 'PHPUnit/Autoload.php';
        //ContainerAwareInterface
        $c = new Client();
        $c->setRedirectUris(array('http://www.example.com'));
        $c->setAllowedGrantTypes(array(
            'authorization_code',
//            Oauth2::GRANT_TYPE_AUTH_CODE,
            'password',
            'refresh_token',
            'token',
            'client_credentials'
        ));
        $manager->persist($c);
        $manager->flush();


        
        
        // php bin/console doctrine:fixtures:load --fixtures=src/CPA/OAuthServerBundle
        
        $u = new User();
        $u->setEmail('turki@intersa.fr');
        $u->setUsername('rturki');
        $u->setSalt(md5(uniqid()));
        $encoder = $this->container->get('security.password_encoder');
        $password = $encoder->encodePassword($u, '0000');
        $u->setPassword($password);
//        $u->setEnabled(1);
        $u->setIsActive(1);
        $u->setRoles(array('ROLE_ADMIN'));

        $manager->persist($u);
        $manager->flush();
        

        
        $u = new User();
        $u->setEmail('moussaoui@intersa.fr');
        $u->setUsername('amoussamoui');
        $u->setSalt(md5(uniqid()));
        $encoder = $this->container->get('security.password_encoder');
        $password = $encoder->encodePassword($u, '1234');
        $u->setPassword($password);
//        $u->setEnabled(1);
        $u->setIsActive(1);
        $u->setRoles(array('ROLE_ADMIN'));

        $manager->persist($u);
        $manager->flush();
        
        
        
        $c11 = new Correspendance();
        $c11->setUsername('admin');// pwd kCBRmZIO
        $c11->setNomBdd('3cconsultants');

        $manager->persist($c11);
        $manager->flush();

        $c1 = new Correspendance();
        $c1->setUsername('OGUNE');//pwd = OGUNE
        $c1->setNomBdd('3cexternpaie_fulloffice_db');

        $manager->persist($c1);
        $manager->flush();

       $c2 = new Correspendance();
        $c2->setUsername('admin');//pwd : mOR9NyTw
        $c2->setNomBdd('foyerduromarin_fulloffice_db');

        $manager->persist($c2);
        $manager->flush();

        
        $c3 = new Correspendance();
        $c3->setUsername('admin');// pwd bjdE3U29
        $c3->setNomBdd('groupebaila_fulloffice_db');

        $manager->persist($c3);
        $manager->flush();

        $c4 = new Correspendance();
        $c4->setUsername('admin');// pwd: YP07SG38
        $c4->setNomBdd('groupseaowl_fulloffice_db');

        $manager->persist($c4);
        $manager->flush();

        $c5 = new Correspendance();
        $c5->setUsername('admin');//pwd mZpeoc5i
        $c5->setNomBdd('keolis_fulloffice_db');

        $manager->persist($c5);
        $manager->flush();

        $c6 = new Correspendance();
        $c6->setUsername('admin');//pwd Q9PjF5al
        $c6->setNomBdd('jeanjean_fulloffice_db');

        $manager->persist($c6);
        $manager->flush();
        
        
        
        $c7 = new Correspendance();
        $c7->setUsername('2840299422026');
        $c7->setNomBdd('keolis_test');

        $manager->persist($c7);
        $manager->flush();
    }

}
