<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\LoginForm;
use CPA\OAuthServerBundle\Entity\Client;

class SecurityController extends Controller {

    
//    public function loginAction(Request $request) {
////        $this->redirectUrl($request);
////        $this->generateUrl('redirect_url');
////        die('here');
//        $authenticationUtils = $this->get('security.authentication_utils');
//
////        var_dump($authenticationUtils);
////        die('here');
//        //oauth/v2
//        //  /login
//        // get the login error if there is one
//        $error = $authenticationUtils->getLastAuthenticationError();
////         var_dump($error);
////        die('$error');
//        // last username entered by the user
//        $lastUsername = $authenticationUtils->getLastUsername();
//
//        $form = $this->createForm(LoginForm::class, [
//            '_username' => $lastUsername,
//        ]);
////        var_dump($error);
////        die('$form');
//
//        return $this->render(
//                        'security/login.html.twig', array(
//                    'form' => $form->createView(),
//                    'error' => $error,
//                        )
//        );
//    }

   

}
