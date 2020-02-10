<?php

namespace CPA\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use OAuthServerBundle\Entity\AccessToken;
use CPA\UserBundle\Entity\User;
use CPA\OAuthServerBundle\Entity\AuthCode;
use CPA\OAuthServerBundle\Entity\RefreshToken;
use CPA\UserBundle\Entity\Salary;
use CPA\UserBundle\Entity\SignedDoc;
use CPA\UserBundle\Form\SearchForm;
use Symfony\Component\Validator\Constraints\DateTime;

//use CPA\DoctrineExtensions\DBAL\Types\UTCDateTimeType;

class DefaultController extends Controller {

    public function indexAction(Request $request) {
//        $tt = $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN');
//        $session = $request->getSession();
//        die('indexAction');
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


//        $clientManager = $this->container->get('fos_oauth_server.client_manager.default');
//        $client = $clientManager->createClient();
//        $client->setRedirectUris(array('https://www.moncompteactivite.gouv.fr/idp/obs/intersa/callback'));
////        $client->setAllowedGrantTypes(array('token', 'authorization_code'));
//        $client->setAllowedGrantTypes(array('token', 'authorization_code', 'client_credentials', 'refresh_token', 'password'));
//        $clientManager->updateClient($client);
//
//        return $this->redirect($this->generateUrl('fos_oauth_server_authorize', array(
//                            'client_id' => $client->getPublicId(),
//                            'redirect_uri' => 'https://www.moncompteactivite.gouv.fr/idp/obs/intersa/callback',
//                            'response_type' => 'code',
//                            'scope' => 'read',
//                            'state' => $state_finale
//        )));


        $client_id = '8_59i5pcmanvwog4coows8gg84408ckwk8444w0occ00sg8kkgsw';
        $secret = 'u0am06sua7ko40w4s404ok84g4goc88w048gsg8skgos8gc8c';
        $redirect_uri = 'https://www.moncompteactivite.gouv.fr/idp/obs/intersa/callback';
        return $this->redirect($this->generateUrl('fos_oauth_server_authorize', array(
                            //'client_id' => $client->getPublicId(),
                            'client_id' => $client_id,
                            'secret' => $secret, 
                            'redirect_uri' => $redirect_uri,
                            'response_type' => 'code',
                            'scope' => 'read',
                            'state' => $state_finale
        )));













//        $username = '1570899353073'; //$request->getSession()->get('_username');
//        $plainpassword = '000000'; //$request->getSession()->get('_password');
//        dump($username);
//        dump($plainpassword);
//        die('authentification cpa2');
//        $this->get('app.security.authentification.cpa')->AuthentificationCpaUser($username, $plainpassword);
//        var_dump($state_finale);
//        die('indexAction2');
//        die('heeerre');
//        $redirect_uri = 'http://cpa-fulloffice.dev/app_dev.php/authorize';
//        $redirect_uri = 'https://www.moncompteactivite.gouv.fr/idp/obs/intersa/callback';
//        $redirect_uri = 'http://www.example.com/';
//        $redirect_uri = 'https%3A%2F%2Fwww.moncompteactivite.gouv.fr%2Fidp%2Fobs%2Fintersa%2Fcallback';
//        $redirect_uri = 'http://knpsf3-test.dev/app_dev.php/api/articles';
//        $redirect_uri = 'http%3A%2F%2Fknpsf3-test.dev/app_dev.php/api/articles';
//            $redirect_uri = 'http%3A%2F%2Fwww.example.com';
        // mon code
//        $redirect_uri = "http://www.example.com";
//        $params = array(
//            'client_id' => "28_1uf7wjmgsmskw4sww00kskk8808s88oowwoskc4ks4kc0s0wo0",
//            'secret' => "1kfgbo43h5ess48sook800sggc004kgc4480sookogw08wg880",
//            'response_type' => 'code',
////            'grant_type' => 'client_credentials',
//            'redirect_uri' => $redirect_uri,
////                'grant_type' => 'refresh_token',
//            'scope' => 'read',
//            'state' => $state_finale
//        );
        // end mon code
//        $url = $this->generateUrl('fos_oauth_server_authorize', $params);
//        var_dump($url);
//        die('url');
//        $tt = $this->generateUrl('fos_oauth_server_token', $params);
//        $tt='http://knpsf3-test.dev/app_dev.php/oauth/v2/token?client_id=16_ont49rbhy800ow8k0kgww804k00sksk08wc4ksw0wowkw48s4&secret=miu0rngmrpc400k4s4s0g08s44kcog080c4csogcco4k4wso4&grant_type=client_credentials';
//        var_dump($tt);
//        die('tt');
//        return $this->redirect($tt);
//        return $this->redirect($this->generateUrl('fos_oauth_server_token', $params));
//        $url = $this->generateUrl('fos_oauth_server_authorize', $params);
//        var_dump($url);
//        die('url');
//        return $this->redirect($this->generateUrl('fos_oauth_server_authorize', $params));
//         if (true === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
//             
//            
//         }
//        return $this->render('UserBundle:Default:index.html.twig');
    }

    public function profileUserAction(Request $request) {

        //$var = $this->get('security.authorization_checker')->isGranted('ROLE_USER');
//        dump($request);
//        $autHeader = $request->headers->get('HTTP_AUTHORIZATION');//HTTP_AUTHORIZATION  Authorization
//         dump($autHeader);
//        $autHeader = $request->headers->get('Authorization'); //HTTP_AUTHORIZATION  Authorization
//          dump($autHeader);
//        die('profileUserAction');
//        $em = $this->getDoctrine()->getManager();
////
//        $IdSalary = array(100, 101, 102, 103, 104);
//        $array_test = array();
//        $index_test = 0;
//        foreach ($IdSalary as $key => $value) {
//            $array_test[$index_test] = $this->findBulletinBySalaryArray($value, $em);
//            $index_test ++;
//        }
//
//
//
////        dump($array_test);
////        die('$array_test');
//        foreach ($array_test as $key => $value) {
//            if ($value) {
//                dump($value);
//            }
//        }
//        
//
//        //findBulletinBySalaryArray
////        dump($array_test);
//        die('$tt');




        die('profile user');
        $em = $this->getDoctrine()->getManager();
//        $var = $this->container->get('security.token_storage')->getToken()->getUser();
        $accessToken = $_GET['access_token'];
        $headers = ['Authorization' => 'Bearer' . $accessToken];
        $request->headers->add($headers);
        $auth = $em->getRepository('OAuthServerBundle:AuthCode')->findBy(array('token' => $accessToken));
        $user_id = $auth[0]->getUser()->getId();
        $user_auth = $em->getRepository('UserBundle:User')->findBy(array('id' => $user_id));
        $username = $user_auth[0]->getUsername();
        if ($username) {
            $entity_user = $em->getRepository('UserBundle:User')->findBy(array('username' => $username));
            $array_user = $entity_user;
            $User = $array_user[0];
//            dump($User);
//            die('$User');
            if ($User) {
                $Salary = $em->getRepository('UserBundle:Salary')->findBy(array('user' => $User));
                if ($Salary) {

                    $date_debut = new \Datetime();
                    $date_fin = new \Datetime();
                    $form = $this->createForm(SearchForm::class, [
                        'date_debut' => $date_debut,
                        'date_fin' => $date_fin,
                    ]);
                    return $this->render('UserBundle:Api:profile_user.html.twig', array(
                                'user' => $User,
                                'accesstoken' => $accessToken,
                                'form' => $form->createView(),
                                'salary' => $Salary
                    ));
                } else {
                    return $this->redirect($this->generateUrl('acme_oauth_server_auth_login_check'));
                }
            } else {
                return $this->redirect($this->generateUrl('acme_oauth_server_auth_login_check'));
            }
        }



//        return $this->render('Api/profile_user.html.twig', [
//             'user'=>$User
//        ]);
//        $token = $_GET['access_token'];
//        $entity = $this->GetClientBy($token) ;//$em->getRepository('OAuthServerBundle:AccessToken')->findBy(array('token'=>$token));
//        if ($entity) {
//            $client_id = $entity[0]['client_id'];
//        }
//        $var = $this->container->get('security.token_storage')->getToken()->getUser();
//        dump($request->getSession());
//        die('profileUserAction');
//        if (true === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
//            
//        }
    }

    public function listeBulletinAction(Request $request) {

        $date_debut = '01/2017';
        $date_fin = '03/2017';
//        $json = $request->getContent();
//        $data = json_decode($json, true);
//        $salary_id = $data['salary_id'];
        $em = $this->getDoctrine()->getManager();
        $accessToken = $_GET['access_token'];
        $headers = ['Authorization' => 'Bearer' . $accessToken];
        $request->headers->add($headers);
//        $auth = $em->getRepository('OAuthServerBundle:AuthCode')->findAuthCodeByToken($accessToken);
        $auth = $this->findAuthCodeByToken($accessToken, $em);
        $array_auth = array();
        $array_auth = $auth;
        $user_id = $auth[0]['user_id'];
        $user = $em->getRepository('UserBundle:User')->findUserById($user_id);

        $array_Salary_id = array();
        $index_salary_id = 0;
        if ($user) {
            $Salary = $em->getRepository('UserBundle:Salary')->findSalarybyUser($user);
            if ($Salary) {

                if (count($Salary) == 1) {
                    $bulletins = $em->getRepository('UserBundle:SignedDoc')->findBseBySalary($Salary);
                }
                if (count($Salary) > 1) {
                    foreach ($Salary as $key => $value) {


                        $array_Salary_id[$index_salary_id] = $this->findBulletinBySalaryArray($value['id'], $em);
                        $index_salary_id ++;
                    }

//                    $bulletins = $em->getRepository('UserBundle:SignedDoc')->findBseByArraySalary($array_Salary_id);
//                    $array_id_signeDoc = $em->getRepository('UserBundle:SignedDoc')->findIdDocSigneByArraySalary($array_Salary_id);
                    //findIdDocSigneByArraySalary


                    foreach ($array_Salary_id as $key => $value) {
                        if ($value) {
                            $JsonResponse = new JsonResponse(array("tab" => $value));
                            return $JsonResponse;
                        }
                    }
                }


//                $JsonResponse = new JsonResponse(array("tab" => $bulletins));
//                return $JsonResponse;
//                 $salary_id
//                 $bulletins = $em->getRepository('UserBundle:SignedDoc')->findBseBySalary($Salary);
            }
        }
        $JsonResponse = new JsonResponse(array("tab" => $array_id_signeDoc));
        return $JsonResponse;
    }

    public function findAuthCodeByToken($accesstoken, $em) {
        $sql = "SELECT * FROM auth_code";
        $sql .= " ";
        $sql .= "where token ='" . $accesstoken . "'";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findBulletinBySalaryArray($IdSalary, $em) {
        //SELECT id, salary_id,  ,   date_debut, date_fin FROM signed_doc WHERE 1
        $sql = "SELECT c.id as id, c.salary_id as salary_id , c.date_debut as date_debut, c.date_fin as date_fin, s.company_name as company_name FROM signed_doc c  left join salary s on c.salary_id = s.id";
        $sql .= " ";
        $sql .= "where c.salary_id ='" . $IdSalary . "'";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

}
