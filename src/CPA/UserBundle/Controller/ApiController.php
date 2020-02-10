<?php

//namespace CPA\CodeReviewRestBundle\Controller;

namespace CPA\UserBundle\Controller;

//class ApiController extends FOSRestController


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use CPA\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
//use OAuth2\HttpFoundationBridge\Request;
use OAuth2\ServerBundle\Controller\AuthorizeController;
use Symfony\Component\HttpFoundation\JsonResponse;
use OAuthServerBundle\Entity\AccessToken;

class ApiController extends FOSRestController {

    //class ApiController extends Controller
    public function articlesAction(Request $request) {
//        die('articlesAction');
//        
//        /        $tt = $this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY');
//        var_dump($tt);
//        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
//            throw $this->createAccessDeniedException();
//        }
//        die('articlesAction');
        $articles = array('article1', 'article2', 'article3');
//        return $this->render('CodeReviewRestBundle:Api:index.html.twig');
        return new JsonResponse($articles);
//        if (true === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
////            die('true');
//            $articles = array('article1', 'article2', 'article3');
//            return new JsonResponse($articles);
//        }
//        die('articlesAction');
    }

//    public function profileUserAction(Request $request) {
//    
//    }

    public function userAction(Request $request) {
        if (true === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $user = $this->container->get('security.token_storage')->getToken()->getUser();
            if ($user) {
                return new JsonResponse(array(
                    'id' => $user->getId(),
                    'username' => $user->getUsername()
                ));
            }

            return new JsonResponse(array(
                'message' => 'User is not identified'
            ));
        }
    }

    public function listeBulletinAction(Request $request) {

        $accessToken = $_GET['access_token'];
        $headers = ['Authorization' => 'Bearer' . $accessToken];
        $request->headers->add($headers);
        $username = $request->getSession()->get('_username');
        dump($username);
        die('listeBulletinAction');

//        $user = $this->container->get('security.token_storage')->getToken()->getUser();
    }

    public function downloadBulletinAction($identifiantBse) {
        
    }

}
