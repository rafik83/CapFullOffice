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


/**
 * @DI\Inject("debug.stopwatch", required=false)
 */
class RedirectionListener {

    /**
     * @var \Symfony\Component\HttpFoundation\Session\Session
     */
    protected $session;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected $container;

    /**
     * @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
     */
    protected $tokenStorage;

    /**
     * @var \Symfony\Component\Routing\Router
     */
    protected $router;
    protected $em;
//  
    protected $requestStack;

    public function __construct(TokenStorageInterface $tokenstorage, EntityManager $em, ContainerInterface $container, Session $session, RequestStack $request, RouterInterface $router) {
//        $this->session = $session;
//        var_dump($session);
//        die('event listner');
//        $state = $this->get('request')
        $this->container = $container;
        $this->em = $em;
        $this->router = $router; //RouterInterface $router,
        $this->tokenStorage = $tokenstorage;
        $this->requestStack = $request;
        $current_request = $this->requestStack->getCurrentRequest();
        $this->session = $session; //$session->set('foo', 'bar');
//        $user = $this->tokenStorage->getToken()->getUser();
        // token is null voir ce ci car user non authentificated :
        //http://stackoverflow.com/questions/25525279/symfony-service-configurator-gettoken-with-security-context-is-null
//        $this->router = $this->container->get('router');
//        $this->accessDecisionManager = $accessDecisionManager;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onkernelRequest(GetResponseEvent $event) {

//        dump($event->getRequest());
//        $request = $event->getRequest();
//        dump($request);
//        die('onkernelRequest');


//        if ($request->getPathInfo() == '/logouts') {

//            $request->getLocale();
//            $request->setLocale($locale);

//            dump($request);
//            die('enter path info + onkernelRequest');
//            $md5 = md5(uniqid(mt_rand(), true));
//            $state = $md5; //$request->getSession()->get('oauth.state');
//            $first8 = substr($state, 0, 8);
//            $state2 = substr($state, 8);
//            $first4 = substr($state2, 0, 4);
//            $state3 = substr($state2, 4);
//            $first42 = substr($state3, 0, 4);
//            $state4 = substr($state3, 4);
//            $first43 = substr($state4, 0, 4);
//            $therest = substr($state, 20, 12);
//            $state_finale = $first8 . '-' . $first4 . '-' . $first42 . '-' . $first43 . '-' . $therest;
//            $redirect_uri = 'http://knpsf3-test.dev/app_dev.php/authorize';
//
//            $url = $request->getUriForPath('/oauth/v2/auth_login') . '?' . http_build_query(array(
//                        'client_id' => "23_1zuq8svg8gqsos0wc8woc00k4g8s44k8s0ogsw0sk4k800008o",
////                    'secret' => "2e1ou61geby808ckog8woowo88w0ccs4o8gc0g8kgoc0scc40o",
//                        'response_type' => 'code',
////            'grant_type' => 'client_credentials',
//                        'redirect_uri' => $redirect_uri,
////                'grant_type' => 'refresh_token',
//                        'scope' => 'read',
//                        'state' => $state_finale
//            ));
//
//           dump($request);
//            dump($url);
//            die('url');
//            return new RedirectResponse($url);
//            return $this->redirect($this->generateUrl('redirect_oauth_server_auth_login')) ;
//            return $this->generateUrl('acme_oauth_server_auth_login');
//            $requestredirect = Request::create($request->getUriForPath('/oauth/v2/auth_login'), 'POST', $parameters);
//            $url2 = $requestredirect->getUri();
//            dump($url2);
//            die('$url2');
//            $this->redirect($url2);
//        }








//        $headers = ['Authorization' => 'Bearer' . $accessToken];
//        require '../vendor/autoload.php';
//        $client = new \GuzzleHttp\Client();
//        $url = 'http://knpsf3-test.dev/app_dev.php/oauth/v2/token?client_id=23_1zuq8svg8gqsos0wc8woc00k4g8s44k8s0ogsw0sk4k800008o&client_secret=2e1ou61geby808ckog8woowo88w0ccs4o8gc0g8kgoc0scc40o&grant_type=client_credentials';
//        $response = $client->request('GET', $url);
//        $data = json_decode($response->getBody()->getContents(), true);
//        $accessToken = $data['access_token'];
//        $expires_in = $data['expires_in'];
//        $expiresAt = new \DateTime('+' . $expires_in . ' seconds');
//        $expires_in = $expiresAt;
//        $token_type = $data['token_type'];
//        $scope = $data['scope'];
//        $refresh_token = $data['refresh_token'];
//        die('onkernelRequest');
////        
//        $headers = ['Authorization' => 'Bearer'.$accessToken];
//        $this->requestStack->getCurrentRequest()->headers->add($headers);
//        
//         $this->requestStack->getCurrentRequest()->headers->add('Authorization', 'Bearer' . $accessToken);
//         dump($accessToken);
//         die('onkernelRequest');
//        $current_request = $this->requestStack->getCurrentRequest();
//        dump($current_request);
//        die('onkernelRequest');
//        dump($event);
//        $pathinfo = $event->getRequest()->getPathInfo();
//        dump($this->session);
//        if ($pathinfo == '/logouts') {
//            if ($this->session->get('_username')) {
////                dump($this->session->get('_username'));
//                die('enter for remove username from listner');
//                $this->session->remove('_username');
//                
//            }
//             if ($this->session->get('_password')) {
//                die('enter for remove _password from listner');
//                 $this->session->remove('_password');
//            }
//        }
//        dump($this->requestStack->getCurrentRequest());
//        die('redirection_listener');
//        $query = "client_id=6_2qqlwedph6yosks0kw8sog0cgggw44gg48kogs404s4g44kw8";
//        $host = "http://knpsf3-test.dev/app_dev.php/oauth/v2/auth_login";
//        $url2 = $host."?".$query ;
//        return new RedirectResponse($url2);
//        var_dump($url2);
//        die('url');
        //header('location: domain.com'."?".$query);
//        $url = 'http://knpsf3-test.dev/app_dev.php/oauth/v2/auth_login?client_id=6_2qqlwedph6yosks0kw8sog0cgggw44gg48kogs404s4g44kw8g&amp;redirect_uri=http%3A%2F%2Fwww.example.com%2F&amp;response_type=code&amp;scope=read&amp;state=484c5bdc-fa3b-3218-6545-a7a96394713b';
//        return new RedirectResponse($url);
//        $request = $this->requestStack->getCurrentRequest();
//        $client = $this->em->getRepository('OAuthServerBundle:Client')->find(4);
//        $id = $client->getId();
//        $randomId = $client->getRandomId();
//        $client_id = $id . '_' . $randomId;
//        $secret = $client->getSecret();
//        $data = $client->getRedirectUris();
//        $redirectUris = $data[0];
//        $md5 = md5(uniqid(mt_rand(), true));
//        if (!$this->session->get('oauth.state')) {
//            $this->session->set('oauth.state', $md5);
//        }
//        $state = $this->session->get('oauth.state');
//        $first8 = substr($state, 0, 8);
//        $state2 = substr($state, 8);
//        $first4 = substr($state2, 0, 4);
//        $state3 = substr($state2, 4);
//        $first42 = substr($state3, 0, 4);
//        $state4 = substr($state3, 4);
//        $first43 = substr($state4, 0, 4);
//        $therest = substr($state, 20, 12);
//        $state_finale = $first8 . '-' . $first4 . '-' . $first42 . '-' . $first43 . '-' . $therest;
//
//        $parameters = [
//            'response_type' => 'code',
//            'client_id' => $client_id,
//            'redirect_uri' => $redirectUris,
//            'scope' => 'read',
//            'state' => $state_finale,
//        ];
//        $url = Request::create($request->getUriForPath('/oauth/v2/auth'), 'GET', $parameters)->getUri();
////         $redirection = $url;
////        header('Location: ' . filter_var($redirection, FILTER_SANITIZE_URL));
////        exit;
//        var_dump($url);
//        die('hounni');
//        return new RedirectResponse($url);
//        die('herre');
//        die('redirection_listener');
//        $session = new Session();
//        $session->start();
//        $session->set('loginUserId', $user['user_id']);
    }

}
