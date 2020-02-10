<?php

//namespace CPA\UserBundle\Controller;
namespace CPA\CodeReviewRestBundle\Controller;

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
use OAuth2;
//use GuzzleHttp\Client;
//use OAuth2\Client ;
//require 'vendor/autoload.php';
//use GuzzleHttp\Client;

class AuthController extends Controller {

    public function authAction(Request $request) {
        die('authAction');
//        //https://github.com/adoy/PHP-OAuth2
//        //https://causeyourestuck.io/2016/07/19/oauth2-explained-part-3-using-oauth2-bare-hands/
//        //http://docs.guzzlephp.org/en/stable/
//        //https://codereviewvideos.com/course/beginner-friendly-hands-on-symfony-3-tutorial/video/getting-real-data-from-github-with-guzzle
        //https://stackoverflow.com/questions/34696406/curl-error-60-see-http-curl-haxx-se-libcurl-c-libcurl-errors-html?rq=1
        require '../vendor/autoload.php';

        //https://api.github.com/users/codereviewvideos
        $client = new \GuzzleHttp\Client();
        $url = 'http://knpsf3-test.dev/app_dev.php/oauth/v2/token?client_id=23_1zuq8svg8gqsos0wc8woc00k4g8s44k8s0ogsw0sk4k800008o&client_secret=2e1ou61geby808ckog8woowo88w0ccs4o8gc0g8kgoc0scc40o&grant_type=client_credentials';
//        $response = $client->request('GET', 'https://api.github.com/users/codereviewvideos');
        //http%3A%2F%2Fwww.moncompteactivite.gouv.fr
        $response = $client->request('GET', $url);
        $data = json_decode($response->getBody()->getContents(), true);
        $accessToken = $data['access_token'];
        $expires_in = $data['expires_in'];
        $expiresAt = new \DateTime('+' . $expires_in . ' seconds');
        $expires_in = $expiresAt;
        $token_type = $data['token_type'];
        $scope = $data['scope'];
        $refresh_token = $data['refresh_token'];
        $parameters = [
            'access_token' => $accessToken,
        ];
        $headers = ['HTTP_AUTHORIZATION' => 'Bearer' . '     ' . $accessToken];
        $request->headers->add($headers);
//        dump($request);
//        die('$request');
        $request->getUriForPath('/api/articles');
//         dump($request->getUriForPath('/api/articles'));
//        die('$request');
        //http://knpsf3-test.dev/app_dev.php/oauth/v2/token/api/articles?access_token=NmVjMmQ3Y2U1YzZjNjRmYjkyN2U3NGYyNDBhMTdmYzEzNTllYmQ1NjM5MjExMzcyNjRiZGRiYTA2MTk1M2E4ZA
        //NmVjMmQ3Y2U1YzZjNjRmYjkyN2U3NGYyNDBhMTdmYzEzNTllYmQ1NjM5MjExMzcyNjRiZGRiYTA2MTk1M2E4ZA
        $requestredirect = Request::create($request->getUriForPath('/api/articles'), 'GET', $parameters);
        $requestredirect->headers->add($headers);
        $url2 = $requestredirect->getUri();
//        dump($url2);
//        die('ici');
       return $this->redirect($url2);


        
    }
    
    
}
