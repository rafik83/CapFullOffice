<?php

namespace CPA\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;
use Symfony\Component\Security\Core\Exception\AuthenticationExpiredException;
use FOS\OAuthServerBundle\Security\Authentication\Token\OAuthToken;
use Symfony\Component\HttpFoundation\JsonResponse;
use OAuthServerBundle\Entity\AccessToken;
use OAuthServerBundle\Entity\Client;
use OAuthServerBundle\Entity\AuthCode;
use OAuthServerBundle\Entity\RefreshToken;
use UserBundle\Entity\User;
use OAuth2;

//use GuzzleHttp\Client;
//use OAuth2\Client ;
//require 'vendor/autoload.php';
//use GuzzleHttp\Client;

class AuthController extends Controller {

    public function authAction(Request $request) {

//        var_dump('Authorisation');
//        die('Authorisation');

//        $state = $_GET['state'];
//        //iici il faut enleveler dev.php en prod et verifier state
//        $action_annuler = "/app_dev.php/authorize?error=access_denied&error_description=The+user+denied+access+to+your+application";
//        $action_annuler .= "&state=" . $state;
//
//        dump($request->getRequestUri());
//        die('authAction');
//        if ($request->getRequestUri() == $action_annuler) {
//            return $this->redirect($this->generateUrl('acme_oauth_server_auth_login_check'));
////             return $this->generateUrl('acme_oauth_server_auth_login_check');
//        }
//        $code = $_GET['code'];
//        //iici il faut enleveler dev.php en prod et verifier state
//        //redirect_uri=https%3A%2F%2Fwww.moncompteactivite.gouv.fr%2Fidp%2Fobs%2Fcoffreo%2Fcallback
//        $action_authoriser = "/app_dev.php/authorize?";
//        $action_authoriser .= "state=" . $state;
//        $action_authoriser .= "&code=" . $code;
//        if ($action_authoriser == $request->getRequestUri()) {
//            require '../vendor/autoload.php';
//            $client = new \GuzzleHttp\Client();
////            $url = 'http://cpa-fulloffice.dev/app_dev.php/oauth/v2/token?client_id=25_53k9os99flgc8wog8sooc088gwwgkswcks44ksgos8s8gowg4s&client_secret=5hnacoswx8g0k00gc88wo0gwggwg44os04cwwckkgw4k4408s4&grant_type=client_credentials';
//            $url = 'http://cpa-fulloffice.dev/oauth/v2/token?client_id=26_4rxophg45f288o4kgk4cgkosgkks0o40c8ko00g0s0ow44c0w0&client_secret=56k035ymp5ogggkwcoosswcock800w4wk4c84sc8sgckgkcsg0&grant_type=client_credentials';
//            $em = $this->getDoctrine()->getManager();
//            $response = $client->request('GET', $url);
//            $data = json_decode($response->getBody()->getContents(), true);
//            $accessToken = $data['access_token'];
//            $expires_in = $data['expires_in'];
//            $expiresAt = new \DateTime('+' . $expires_in . ' seconds');
//            $expires_in = $expiresAt;
//            $token_type = $data['token_type'];
//            $scope = $data['scope'];
//            $refresh_token = $data['refresh_token'];
//            //set Object OAuth2
////            $token = $data['access_token'];
//
//            $token_auth = $_GET['code']; // AuthCode  User
//            $aut_object = $em->getRepository('OAuthServerBundle:AuthCode')->findBy(array('token' => $token_auth));
//            $user_id = $aut_object[0]->getUser()->getId();
//            $user_auth = $em->getRepository('UserBundle:User')->findBy(array('id' => $user_id));
//            $username = $user_auth[0]->getUsername();
//            $this->get('app.security.authentification.cpa')->AuthentificationCpaUser($username);
//            $access_token_for_update = $em->getRepository('OAuthServerBundle:AccessToken')->findBy(array('token' => $accessToken));
//            if ($access_token_for_update) {
//                $sql = "UPDATE access_token";
//                $sql .= " ";
//                $sql .= "set token='" . $token_auth . "'";
//                $sql .= " ";
//                $sql .= "where token ='" . $accessToken . "'";
//                $stmt = $em->getConnection()->prepare($sql);
//                $stmt->execute();
//            }
////            $accessToken = $token_auth;
//            $refresh_token_for_update = $em->getRepository('OAuthServerBundle:RefreshToken')->findBy(array('token' => $refresh_token));
//            if ($refresh_token_for_update) {
//                $sql = "UPDATE refresh_token";
//                $sql .= " ";
//                $sql .= "set token='" . $token_auth . "'";
//                $sql .= " ";
//                $sql .= "where token ='" . $refresh_token . "'";
//                $stmt = $em->getConnection()->prepare($sql);
//                $stmt->execute();
//            }
//
//            return new Response('', 200);
//            return $this->redirect($this->generateUrl('api_user_profile'));
//        }


//        die('authAction');
    }

}
