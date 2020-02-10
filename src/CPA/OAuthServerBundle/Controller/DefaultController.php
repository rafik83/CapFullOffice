<?php

namespace CPA\OAuthServerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        
        die('ici');
        return $this->render('OAuthServerBundle:Default:index.html.twig');
    }
}
