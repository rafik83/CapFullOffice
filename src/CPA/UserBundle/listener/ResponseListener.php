<?php

namespace CPA\UserBundle\listener;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use CPA\OAuthServerBundle\Entity\Client;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\ResponseHeaderBag as ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\HeaderBag;
//use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;
use Symfony\Component\Security\Core\Exception\AuthenticationExpiredException;
use FOS\OAuthServerBundle\Security\Authentication\Token\OAuthToken;
use Symfony\Component\HttpFoundation\JsonResponse;
use OAuthServerBundle\Entity\AccessToken;
use OAuthServerBundle\Entity\AuthCode;
use OAuthServerBundle\Entity\RefreshToken;
use UserBundle\Entity\User;

class ResponseListener {

    //put your code here

    protected $container;
    protected $em;

    public function __construct(EntityManager $em, ContainerInterface $container) {
        $this->container = $container;
        $this->em = $em;
    }

    public function onKernelResponse(FilterResponseEvent $event) {
        $array_parametres_index = 0;
        $route = '';
        //$array_parametres = $event->getRequest()->attributes;

        $isEndPointSubmit = $event->getRequest()->getPathInfo() == '/oauth/v2/auth' && $event->getRequest()->isMethod('POST');
        $array_parametres = array();
        $index_parametres = 0;
        $array_enpoint = array();
        $array_location = array();
        $index_enpoint = 0;
        $uri_end_point = '';
        $code = '';


        if ($isEndPointSubmit) {
            if ($event->getRequest()->request) {
                if (count($event->getRequest()->request) > 1) {

                    foreach ($event->getRequest()->request as $key => $value) {
                        $array_parametres[$index_parametres] = $value;
                        $index_parametres ++;
                    }
                }
            }
        }
        if (count($array_parametres) > 1) {
            if ($array_parametres[0] == 'Authoriser') {
                if ($event->getResponse()->headers) {
                    if (count($event->getResponse()->headers) > 1) {

                        foreach ($event->getResponse()->headers as $key => $value) {
                            $array_location[$key] = $value;
//                            dump($key);
                        }
                        if (count($array_location) > 1) {
                            $array_uri_enpoint = $array_location['location'];
                            if ($array_uri_enpoint) {
                                if (count($array_uri_enpoint) == 1) {
                                    $uri_end_point = $array_uri_enpoint[0];
                                    if ($uri_end_point != '') {
                                        $explode = explode('&code=', $uri_end_point);
                                        if (count($explode) > 1) {
                                            $code = $explode[1];
                                            if ($code != '') {

                                                $this->functionSetTokenForAccessRefreshToken($code);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    private function functionSetTokenForAccessRefreshToken($token) {

//        dump(__DIR__);
//        if(file_exists('../vendor/autoload.php')){
//            die('OK');
//        }
//        else {
//            die('KO');
//        }
        //var_dump($token);

        require '../vendor/autoload.php';
        //die('apres autoload');

        $client = new \GuzzleHttp\Client(['verify' => false]);
//        $url = 'http://cpa-fulloffice.dev/app_dev.php/oauth/v2/token?client_id=27_5rbn3uf4wc0skowkcgs4o0808gok80csg4kc8og04444ok4so8&client_secret=2udy64ndceucg8440kc40co0wwogw0wccks8cogcw4o48w0880&grant_type=client_credentials';
//        $url = 'http://cpa-fulloffice.dev/oauth/v2/token?client_id=28_1uf7wjmgsmskw4sww00kskk8808s88oowwoskc4ks4kc0s0wo0&client_secret=1kfgbo43h5ess48sook800sggc004kgc4480sookogw08wg880&grant_type=client_credentials';
//         die('ici');
        $url = 'https://cpa.fulloffice.fr/oauth/v2/token?client_id=8_59i5pcmanvwog4coows8gg84408ckwk8444w0occ00sg8kkgsw&client_secret=u0am06sua7ko40w4s404ok84g4goc88w048gsg8skgos8gc8c&grant_type=client_credentials';
//        $url = 'http://cpa-prod.dev/oauth/v2/token?client_id=8_59i5pcmanvwog4coows8gg84408ckwk8444w0occ00sg8kkgsw&client_secret=u0am06sua7ko40w4s404ok84g4goc88w048gsg8skgos8gc8c&grant_type=client_credentials';


        $em = $this->em;
        //$client->setHttpClient(new GuzzleHttp\Client(['verify'=>false]));
        $response = $client->request('GET', $url);


        $data = json_decode($response->getBody()->getContents(), true);
        //var_dump($data);
        //die('data');
        $accessToken = $data['access_token'];
        $expires_in = $data['expires_in'];
        $expiresAt = new \DateTime('+' . $expires_in . ' seconds');
        $expires_in = $expiresAt;
        $token_type = $data['token_type'];
        $scope = $data['scope'];
        $refresh_token = $data['refresh_token'];
        $token_auth = $token; //$_GET['code']; // AuthCode  User
//        exec("echo ".$token_auth." > ".__DIR__."/echo.txt");
        $aut_object = $em->getRepository('OAuthServerBundle:AuthCode')->findBy(array('token' => $token_auth));
        $user_id = $aut_object[0]->getUser()->getId();
        $user_auth = $em->getRepository('UserBundle:User')->findBy(array('id' => $user_id));
        $username = $user_auth[0]->getUsername();
        $this->container->get('app.security.authentification.cpa')->AuthentificationCpaUser($username);
        $Object_Id_Access_Token = $this->selectIdAccessToken($em);
        $Id_Access_Token = $Object_Id_Access_Token[0]['id'];
//        $Object_Access_Token = $this->getAccessTokenbyId($Id_Access_Token, $em);
//        $access_token_for_update = $Object_Access_Token[0]['token']; //$em->getRepository('OAuthServerBundle:AccessToken')->findBy(array('token' => $accessToken));
//        if ($access_token_for_update) {
//            $sql = "UPDATE access_token"; //db_cpa_fulloffice.access_token
       // $expiresAt_access_token = 3600;
        $sql = "UPDATE db_cpa_fulloffice.access_token";
        $sql .= " ";
        $sql .= "set user_id='" . $user_id . "'";
        $sql .= " ";
        $sql .= "where id ='" . $Id_Access_Token . "'";
	$sql .= " ";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
//        }
//            $accessToken = $token_auth;
//        $refresh_token_for_update = $em->getRepository('OAuthServerBundle:RefreshToken')->findBy(array('token' => $refresh_token));
//        if ($refresh_token_for_update) {
//            $sql = "UPDATE refresh_token";

        $Object_Id_Refresh_token_Token = $this->selectIdRefreshToken($em);
        $Id_Refresh_token_Token = $Object_Id_Refresh_token_Token[0]['id'];
        //$expiresAt_refresh_token = 1209600;
        $sqll = "UPDATE db_cpa_fulloffice.refresh_token";
        $sqll .= " ";
        $sqll .= "set user_id='" . $user_id . "'";
        $sqll .= " ";
        $sqll .= "where id ='" . $Id_Refresh_token_Token . "'";
	$sqll .= " ";
        $stmtl = $em->getConnection()->prepare($sqll);
        $stmtl->execute();
//        }
    }

    public function selectIdAccessToken($em) {
        $sql = "SELECT max(id)  as id FROM db_cpa_fulloffice.access_token";
        $sql .= " ";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function selectIdRefreshToken($em) {
        $sql = "SELECT max(id)   as id FROM db_cpa_fulloffice.refresh_token";
        $sql .= " ";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

//    public function getAccessTokenbyId($id_access_token, $em) {
//        $sql = "SELECT * FROM db_cpa_fulloffice.access_token";
//        $sql .= " ";
//        $sql .= "where id ='" . $id_access_token . "'";
//        $sql .= " ";
//        $stmt = $em->getConnection()->prepare($sql);
//        $stmt->execute();
//
//        return $stmt->fetchAll();
//    }
//
//    public function getRefreshTokenbyId($id_refresh_token, $em) {
//        $sql = "SELECT * FROM db_cpa_fulloffice.refresh_token";
//        $sql .= " ";
//        $sql .= "where id ='" . $id_refresh_token . "'";
//        $sql .= " ";
//        $stmt = $em->getConnection()->prepare($sql);
//        $stmt->execute();
//
//        return $stmt->fetchAll();
//    }

}
