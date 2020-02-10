<?php

namespace CPA\CodeReviewRestBundle\Controller;

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

//use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class DefaultController extends FOSRestController {

    public function functionName(Request $request) {

        $session = $request->getSession();
        $md5 = md5(uniqid(mt_rand(), true));
        $request->getSession()->set('oauth.state', $md5);
        $state = $request->getSession()->get('oauth.state');
        $first8 = substr($state, 0, 8);
        $state2 = substr($state, 8);
        $first4 = substr($state2, 0, 4);
        $state3 = substr($state2, 4);
        $first42 = substr($state3, 0, 4);
        $state4 = substr($state3, 4);
        $first43 = substr($state4, 0, 4);
        $therest = substr($state, 20, 12);
        $state_finale = $first8 . '-' . $first4 . '-' . $first42 . '-' . $first43 . '-' . $therest;


        $redirect_uri = 'http://www.example.com';
//        var_dump($redirect_uri);
//        die('$redirect_uri');
//         $redirect_uri = $request->getUriForPath('/demo/response_type/code');// ==> responseTypeCodeAction
//        var_dump($redirect_uri);
//        die('$redirect_uri');
//        $redirect_uri = ''; //$request->getUriForPath('/demo/response_type/code');
        $parameters = [
            'response_type' => 'code',
            'client_id' => 'authorization_code_grant',
//            'redirect_uri' => $request->getUriForPath('/demo/response_type/code'),
            'redirect_uri' => $redirect_uri,
//            'scope' => 'demoscope1 demoscope2 demoscope3',
            'scope' => 'read',
//            'state' => $session->getId(),
            'state' => $state_finale,
        ];
        //api/oauth2/authorize'
        $url = Request::create($request->getUriForPath('/api/authorize'), 'GET', $parameters)->getUri();
//        var_dump($url);
//        die('ici');
        return $this->redirect($url);
    }

    public function indexAction(Request $request) {
        //$this->KolisExisteUser($request);
//        die('indexAction2');
        $session = $request->getSession();
        $md5 = md5(uniqid(mt_rand(), true));
        $request->getSession()->set('oauth.state', $md5);
        $state = $request->getSession()->get('oauth.state');
        $first8 = substr($state, 0, 8);
        $state2 = substr($state, 8);
        $first4 = substr($state2, 0, 4);
        $state3 = substr($state2, 4);
        $first42 = substr($state3, 0, 4);
        $state4 = substr($state3, 4);
        $first43 = substr($state4, 0, 4);
        $therest = substr($state, 20, 12);
        $state_finale = $first8 . '-' . $first4 . '-' . $first42 . '-' . $first43 . '-' . $therest;
        if (false === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
//            $redirectUrl = 'http://www.example.com';
//            $url = 'http://http://knpsf3.dev/app_dev.php/authorize?' . http_build_query(array(
//                        'response_type' => 'code',
//                        'client_id' => 'TopCluck',
//                        'redirect_uri' => $redirectUrl,
//                        'scope' => 'read',
//                        'state' => $state_finale
//            ));
////            var_dump($url);
//            $clientManager = $this->container->get('fos_oauth_server.client_manager.default');
//            $client = $clientManager->createClient();
//            $client->setRedirectUris(array('http://www.example.com'));
//            $client->setAllowedGrantTypes(array('authorization_code', 'token', 'refresh_token', 'password', 'client_credentials'));
//            $clientManager->updateClient($client);
//             var_dump($client);
//             var_dump('public id');
//             
//             var_dump($client->getPublicId());//$client->getPublicId()
//             
//             
//            $redirect_uri = http_build_query(array('http://www.example.com'));
//            var_dump($redirect_uri);
            die('heeerre');
            $redirect_uri = 'http://www.example.com';
//            $redirect_uri = 'http%3A%2F%2Fwww.example.com';
            $params = array(
                'client_id' => "7_4rs0ia42v4e8wowcoco08w0ssg400ss08osk0wgo08c08k80wk",
                'response_type' => 'code',
                'redirect_uri' => $redirect_uri,
//                'grant_type' => 'refresh_token',
                'scope' => 'read',
                'state' => $state_finale
            );
            //
//            var_dump($params);
//            die('heeerre');
            //http_build_query($postParams)
            return $this->redirect($this->generateUrl('fos_oauth_server_authorize', $params));



            //fos_oauth_server_token  fos_oauth_server_authorize  https://www.example.com
//            return $this->redirect($this->generateUrl('fos_oauth_server_authorize', array(
//                                'client_id' => '7_4rs0ia42v4e8wowcoco08w0ssg400ss08osk0wgo08c08k80wk',//$client->getPublicId(),
//                                'response_type' => 'code',
//                                'redirect_uri' => $redirect_uri,
//                                'scope' => 'read',
//                                'state' => $state_finale
//            )));
            //secret
        }

        return $this->render('CodeReviewRestBundle:Default:index.html.twig');
    }

    
}