<?php

namespace CPA\UserBundle\listener;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use CPA\OAuthServerBundle\Entity\Client;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\KernelEvents;

use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class KernelTerminateListener  { //implements EventSubscriberInterface

    private $kernel;
    
     public function __construct($kernel) {
//       die('onKernelTerminate + __construct');
         $this->kernel = $kernel;
    }
    
    public function onKernelTerminate(PostResponseEvent $event)
    {
//        dump($event);
//       die('onKernelTerminate');
    }

   

}
