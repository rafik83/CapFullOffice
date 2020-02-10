<?php

namespace CPA\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use CPA\UserBundle\Form\LoginForm;
use CPA\OAuthServerBundle\Entity\Client;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;

class SecurityController extends Controller {

    public function loginAction($token, Request $request) {
//        die('loginAction');
//        dump($request->getPathInfo());
//         dump($token);
//        die('loginAction');
        if ($request->getPathInfo() == '/oauth/login') {
//            die('enter path info');
            return $this->redirect($this->generateUrl('acme_oauth_server_auth_login_check')) ;
//            return $this->generateUrl('acme_oauth_server_auth_login_check');
//            $requestredirect = Request::create($request->getUriForPath('/oauth/v2/auth_login'), 'POST', $parameters);
//            $url2 = $requestredirect->getUri();
//            dump($url2);
//            die('$url2');
//            $this->redirect($url2);
        }
//          die('loginAction');

        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
//            $error = NULL;
//            $token = $this->get('security.token_storage')->getToken();
        $lastUsername = $authenticationUtils->getLastUsername();

        $form = $this->createForm(LoginForm::class, [
            '_username' => $lastUsername,
        ]);
//        var_dump($form);
//        die('$form');

        return $this->render(
                        'UserBundle:security:login.html.twig', array(
                    'form' => $form->createView(),
                    'error' => $error,
                    'token' => $token,
//                    'url' => $url,
                        )
        );



//                if ($error == NULL) {
//                    die('err null');
//                    $state = $_GET['state'];
//                    $redirect_uri = $_GET['redirect_uri'];
//                    $client_id = $_GET['client_id'];
//                    $scope = $_GET['scope'];
//                    $parse = $client_id;
//                    $explode = explode('_', $parse);
//
//                    $id_client_oauth = $explode[0];
//                    $em = $this->getDoctrine()->getManager();
//                    $client = $em->getRepository('OAuthServerBundle:Client')->find($id_client_oauth);
//                    $secret = $client->getSecret();
//                    $params = array(
//                        'client_id' => $client_id,
//                        'secret' => $secret,
//                        'response_type' => 'code',
////            'grant_type' => 'client_credentials',
//                        'redirect_uri' => $redirect_uri,
////                'grant_type' => 'refresh_token',
//                        'scope' => 'read',
//                        'state' => $state
//                    );
    }

    public function urlRedirectionLoginAction(Request $request) {
        die('urlRedirectionLoginAction');
    }

    public function generateUrlRedirection(Request $request) {
//        die('generateUrlRedirectionAction');
        
//        $this->redirect($this->generateUrl('acme_oauth_server_auth_login'));
        
        
        
        
//        $md5 = md5(uniqid(mt_rand(), true));
//        $state = $md5; //$request->getSession()->get('oauth.state');
//        $first8 = substr($state, 0, 8);
//        $state2 = substr($state, 8);
//        $first4 = substr($state2, 0, 4);
//        $state3 = substr($state2, 4);
//        $first42 = substr($state3, 0, 4);
//        $state4 = substr($state3, 4);
//        $first43 = substr($state4, 0, 4);
//        $therest = substr($state, 20, 12);
//        $state_finale = $first8 . '-' . $first4 . '-' . $first42 . '-' . $first43 . '-' . $therest;
//        $redirect_uri = 'http://www.example.com';
//
        $url = $request->getUriForPath('/oauth/v2/auth_login') . '?' . http_build_query(array(
                    'client_id' => "cpa_by_intersa",
//                    'secret' => "2e1ou61geby808ckog8woowo88w0ccs4o8gc0g8kgoc0scc40o",
//                    'response_type' => 'code',
//            'grant_type' => 'client_credentials',
//                    'redirect_uri' => $redirect_uri,
//                'grant_type' => 'refresh_token',
//                    'scope' => 'read',
//                    'state' => $state_finale
        ));
//        dump($url);
//        die('url');
        return $url;
    }

    public function loginCheckAction(Request $request) {
//        die('loginCheckAction');

//         $this->redirect($this->generateUrl('acme_oauth_server_auth_login'));
        $url = $this->generateUrlRedirection($request);
//         dump($url);
//         die('loginCheckAction');
        return $this->redirect($url);
//         die('loginCheckAction');
//        $this->generateUrl('acme_oauth_server_auth_login');
    }

}
