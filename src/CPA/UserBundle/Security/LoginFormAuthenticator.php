<?php

namespace CPA\UserBundle\Security;

use CPA\UserBundle\Form\LoginForm;
use Doctrine\ORM\EntityManager;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
//use Symfony\Component\HttpFoundation\ParameterBag;
//use AppBundle\Entity\User;
//use AppBundle\Entity\Correspendance;
use CPA\UserBundle\Entity\User;
use CPA\UserBundle\Entity\Salary;
use CPA\UserBundle\Entity\SignedDoc;
use CPA\UserBundle\Entity\Correspendance;
use CPA\OAuthServerBundle\Entity\Client;

// CPA\UserBundle

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator {

    use TargetPathTrait;

//    private $formFactory;
    private $em;
    private $router;
    private $passwordEncoder;
    private $csrfTokenManager;
    private $doctrine;
    private $requestStack;
    private $session;
    private $bool_auh;

//    public function __construct(FormFactoryInterface $formFactory, Registry $doctrine, EntityManager $em, RouterInterface $router, UserPasswordEncoder $passwordEncoder, CsrfTokenManagerInterface $csrfTokenManager) {
//         
////        $this->formFactory = $formFactory;
//        $this->csrfTokenManager = $csrfTokenManager;
//        $this->em = $em;
//        $this->router = $router;
//        $this->passwordEncoder = $passwordEncoder;
//        $this->doctrine = $doctrine;
////        die('__construct2');
//    }

    public function __construct(Session $session, Registry $doctrine, EntityManager $em, RouterInterface $router, UserPasswordEncoder $passwordEncoder, CsrfTokenManagerInterface $csrfTokenManager, RequestStack $request) {

//        $this->formFactory = $formFactory;
        $this->csrfTokenManager = $csrfTokenManager;
//        var_dump($csrfTokenManager);
//        die('__construct2');
        $this->em = $em;
        $this->router = $router;
        $this->passwordEncoder = $passwordEncoder;
        $this->doctrine = $doctrine;
        $this->requestStack = $request;
        $current_request = $this->requestStack->getCurrentRequest();
        $this->session = $session; //$session->set('foo', 'bar');
        $this->bool_auh = false;
//        return $this->router->generate('test_redirect');
//        $this->RedirectUrl();
//        die('__construct2');
    }

    public function RedirectionBeforeAuthentification() {
        $request = $this->requestStack->getCurrentRequest();
        $client = $this->em->getRepository('OAuthServerBundle:Client')->find(23);
        $id = $client->getId();
        $randomId = $client->getRandomId();
        $client_id = $id . '_' . $randomId;
        $secret = $client->getSecret();
        $data = $client->getRedirectUris();
        $redirectUris = $data[0];
        $md5 = md5(uniqid(mt_rand(), true));

//        var_dump($this->session->get('oauth.state'));
//        die('ssession');

        if (!$this->session->get('oauth.state')) {
            $this->session->set('oauth.state', $md5);
        }
        $state = $this->session->get('oauth.state');
//         var_dump($client_id);
//         var_dump($redirectUris);
//        die('url');
        $first8 = substr($state, 0, 8);
        $state2 = substr($state, 8);
        $first4 = substr($state2, 0, 4);
        $state3 = substr($state2, 4);
        $first42 = substr($state3, 0, 4);
        $state4 = substr($state3, 4);
        $first43 = substr($state4, 0, 4);
        $therest = substr($state, 20, 12);
        $state_finale = $first8 . '-' . $first4 . '-' . $first42 . '-' . $first43 . '-' . $therest;

        $parameters = [
            'response_type' => 'code',
            'client_id' => $client_id,
            'redirect_uri' => $redirectUris,
            'scope' => 'read',
            'state' => $state_finale,
        ];
        $url = Request::create($request->getUriForPath('/oauth/v2/auth_login'), 'GET', $parameters)->getUri();
        return $url;
        dump($url);
//        $this->session->set('before', $url);
//        var_dump($url);
        die('url');
    }

    public function KeolisExisteUser($username, $plainpassword) {

        $db = $this->doctrine->getManager('default');
        $Alldb = $this->doctrine->getManager('fulloffice');
        $nom_bdd = "";
        $keolisTestEm = ''; //$this->doctrine->getManager('keolisTest');
        $externpaieEm = ''; //$this->doctrine->getManager('externpaie');
        $consultantsEm = ''; //$this->doctrine->getManager('consultants');
        $romarainEm = ''; //$this->doctrine->getManager('romarain');
        $bailaEm = ''; //$this->doctrine->getManager('baila');
        $seawolEm = ''; //$this->doctrine->getManager('seawol');
        $keolisEm = ''; //$this->doctrine->getManager('keolis');
        $jeanjeanEm = ''; //$this->doctrine->getManager('jeanjean');
//        $user = $this->em->getRepository('UserBundle:User')->findOneBy(['username' => $username]);nom_bdd

        $user = $db->getRepository('UserBundle:User')->findOneBy(['username' => $username]);
        $entity = $db->getRepository('UserBundle:Correspendance')->byUserName($username);
//         dump($user);
//        die('$user');
//        dump($entity);
//        die('$entity');
        if ($entity) {
            if (count($entity) == 1) {
                foreach ($entity as $key => $value) {
                    $nom_bdd = $value->getNomBdd();
                }
            }
        }

        if ($user) {
//            var_dump($user);
//            die('user');
            //php bin/console doctrine:schema:update --force --em=keolisTest
            //user existe + serach user in all data base + get user
            // 3cexternpaie OGUNE OGUNE
            if (count($entity) == 1) {
//                die('count + entity = 1');
                if ($nom_bdd != "") {
                    $sql = "SELECT * FROM"; //keolis_test // 28402994220260 1234 
                    $sql .= " ";
                    $sql .= $nom_bdd;
                    $sql .= ".user";
                    $sql .= " ";
                    $sql .= " where username ='" . $username . "'";
                    $stmt = $Alldb->getConnection()->prepare($sql);
                    $stmt->execute();
                    $result2 = array();
                    $result2 = $stmt->fetchAll();
                    if (count($result2) == 1) {
                        //traittement 
                        foreach ($result2 as $key => $value) {
                            $id3 = $value['id'];
                            $username3 = $value['username'];
                            $password3 = $value['password'];
                            $salt3 = $value['salt'];
                            $active3 = $value['is_active'];
                        }
                        $bool = false;
                        if (hash('sha512', $salt3 . $plainpassword) == $password3) {
                            $bool = true;
                        }
                        if ($bool) {
                            // insert new user
                            if ($username3 != 'admin') {
                                $user->setEmail($username3 . '@' . $nom_bdd . '.' . 'fr');
                                $user->setUsername($username3);
                                $user->setSalt($salt3);
                                $user->setPassword($password3);
                                $user->setIsActive($active3);
                                $user->setRoles(array('ROLE_USER'));
                                $db->persist($user);
                                $db->flush();
                            }
                        }
                    }
                }
            }

            if (count($entity) > 1) {
                die('count + entity > 1');
                foreach ($entity as $key => $value) {

//                    var_dump($value);
//                    die('ici');
                    $username = $value->getUsername();
                    $nom_bdd = $value->getNomBdd();
                    $sql = "SELECT * FROM"; //keolis_test // 28402994220260 1234 
                    $sql .= " ";
                    $sql .= $nom_bdd;
                    $sql .= ".user";
                    $sql .= " ";
                    $sql .= " where username ='" . $username . "'";

                    $stmt = $Alldb->getConnection()->prepare($sql);
                    $stmt->execute();
                    $result = array();
                    $result = $stmt->fetchAll();
                    foreach ($result as $key => $value) {
                        $id3 = $value['id'];
                        $username3 = $value['username'];
                        $password3 = $value['password'];
                        $salt3 = $value['salt'];
                        $active3 = $value['is_active'];
                    }

                    $bool = false;
                    if (hash('sha512', $salt3 . $plainpassword) == $password3) {
                        $bool = true;
                    }
                    if ($bool) {
                        // insert new user

                        if ($username3 != 'admin') {
//                            var_dump($nom_bdd);

                            $user->setEmail($username3 . '@' . $nom_bdd . '.' . 'fr');
                            $user->setUsername($username3);
                            $user->setSalt($salt3);
                            $user->setPassword($password3);
                            $user->setIsActive($active3);
                            $user->setRoles(array('ROLE_USER'));
                            $db->persist($user);
//                            die('re enter');
                            $db->flush();
//                            die('success');
                            break;
                        }
                    }
                }
            }
        } // end if
        else {

//            dump($nom_bdd);
            die('else');
            if ($nom_bdd != "") {
                if (count($entity) == 1) {
                    //insertion User
                    if ($nom_bdd != "") {  //3cconsultant_test // 28402994220260 1234 
                        $sql = "SELECT * FROM"; //keolis_test // 28402994220260 1234 
                        $sql .= " ";
                        $sql .= $nom_bdd;
                        $sql .= ".user";
                        $sql .= " ";
                        $sql .= " where username ='" . $username . "'";
                        $stmt = $Alldb->getConnection()->prepare($sql);
                        $stmt->execute();
                        $result = array();
                        $result = $stmt->fetchAll();
//                    dump($result);
//                    die('else');
                        if (count($result) == 1) {
                            //traittement 
                            foreach ($result as $key => $value) {
                                $id3 = $value['id'];
                                $username3 = $value['username'];
                                $password3 = $value['password'];
                                $salt3 = $value['salt'];
                                $active3 = $value['is_active'];
//                            var_dump($value);
//                            die('val');
                            }
                            $bool = false;
                            if (hash('sha512', $salt3 . $plainpassword) == $password3) {
                                $bool = true;
//                            die('enter bool');
                            }

//                        dump($bool);
//                        die('bool');
//                         dump($entity);
//                        die('count + $entity');
                            //insertion User
                            if ($bool) {
                                // insert new user
                                if ($username3 != 'admin') {
                                    $u = new User();
                                    $u->setEmail($username3 . '@' . $nom_bdd . '.' . 'fr');
                                    $u->setUsername($username3);
                                    $u->setSalt($salt3);
                                    $u->setPassword($password3);
                                    $u->setIsActive($active3);
                                    $u->setRoles(array('ROLE_USER'));
                                    $db->persist($u);
                                    $db->flush();
                                    die('insertion user');
                                    // Insertion Salary
                                    die('enter bool Salary');
                                    dump($id3);
                                    die('insert Salary');
                                    if ($nom_bdd != "" && $id3 != "") {
                                        $sql2 = "SELECT * FROM";
                                        $sql2 .= " ";
                                        $sql2 .= $nom_bdd;
                                        $sql2 .= ".salary";
                                        $sql2 .= " ";
                                        $sql2 .= "s";
                                        $sql2 .= " ";
                                        $sql2 .= "left  join";
                                        $sql2 .= " ";
                                        $sql2 .= $nom_bdd;
                                        $sql2 .= ".company";
                                        $sql2 .= " ";
                                        $sql2 .= "c";
                                        $sql2 .= " ";
                                        $sql2 .= "on";
                                        $sql2 .= " ";
                                        $sql2 .= "s.company_id = c.id";
                                        $sql2 .= " ";
                                        $sql2 .= " where s.user_id ='" . $id3 . "'";
                                        $sql2 .= " ";
                                        $sql2 .= "and";
                                        $sql2 .= " ";
                                        $sql2 .= "s.is_paper = 0";
                                        $sql2 .= " ";
                                        $stmt2 = $Alldb->getConnection()->prepare($sql);
                                        $stmt2->execute();
                                        $resultSalary = array();
                                        $resultSalary = $stmt2->fetchAll();
                                        //and s.is_paper = 0
                                    }
                                    if (count($resultSalary) > 0) {
                                        foreach ($resultSalary as $key => $value) {

                                            dump($value);
                                            die('value');
                                            // Salary
                                        }
                                    }
//                                
                                }
                            }
//                            dump($bool);
//                            die('insert Salary');
                            //insertion salary
//                         dump(count($entity));
//                        die('count + $entity');
                        }// end if
                    }
                }
//            $_array_object = array();
//            $index_array_object = 0;
                if (count($entity) > 1) {

                    foreach ($entity as $key => $value) {

//                    var_dump($value);
//                    die('ici');
                        $username = $value->getUsername();
                        $nom_bdd = $value->getNomBdd();
                        $sql = "SELECT * FROM"; //keolis_test // 28402994220260 1234 
                        $sql .= " ";
                        $sql .= $nom_bdd;
                        $sql .= ".user";
                        $sql .= " ";
                        $sql .= " where username ='" . $username . "'";

                        $stmt = $Alldb->getConnection()->prepare($sql);
                        $stmt->execute();
                        $result = array();
                        $result = $stmt->fetchAll();
                        foreach ($result as $key => $value) {
                            $id3 = $value['id'];
                            $username3 = $value['username'];
                            $password3 = $value['password'];
                            $salt3 = $value['salt'];
                            $active3 = $value['is_active'];
                        }

                        $bool = false;
                        if (hash('sha512', $salt3 . $plainpassword) == $password3) {
                            $bool = true;
                        }
                        if ($bool) {
                            // insert new user

                            if ($username3 != 'admin') {
//                           var_dump($nom_bdd);
                                $u = new User();
                                $u->setEmail($username3 . '@' . $nom_bdd . '.' . 'fr');
                                $u->setUsername($username3);
                                $u->setSalt($salt3);
                                $u->setPassword($password3);
                                $u->setIsActive($active3);
                                $u->setRoles(array('ROLE_USER'));
                                $db->persist($u);
                                $db->flush();
//                             die('success');
                                break;
                            }
                        }
                    }
                }
            }
        }// end else
//        die('fin if + else');
    }

    public function AuthentificationCpaUser($username, $plainpassword) {

//        dump('AuthentificationCpaUser');
//        dump($username);
//        dump($plainpassword);
//        die('AuthentificationCpaUser');
        $boolean_return = false;
        $db = $this->doctrine->getManager('default');
        $Alldb = $this->doctrine->getManager('fulloffice');
        $nom_bdd = "";
        $keolisTestEm = ''; //$this->doctrine->getManager('keolisTest');
        $externpaieEm = ''; //$this->doctrine->getManager('externpaie');
        $consultantsEm = ''; //$this->doctrine->getManager('consultants');
        $romarainEm = ''; //$this->doctrine->getManager('romarain');
        $bailaEm = ''; //$this->doctrine->getManager('baila');
        $seawolEm = ''; //$this->doctrine->getManager('seawol');
        $keolisEm = ''; //$this->doctrine->getManager('keolis');
        $jeanjeanEm = ''; //$this->doctrine->getManager('jeanjean');
//        $user = $this->em->getRepository('UserBundle:User')->findOneBy(['username' => $username]);nom_bdd

        $user = $db->getRepository('UserBundle:User')->findOneBy(['username' => $username]);
//        var_dump($user);
        $entity = $db->getRepository('UserBundle:Correspendance')->byUserName($username);
//        var_dump($entity);
//        die('AuthentificationCpaUser');
//        if (!$entity) {
//            $boolean_return = false;
//        }
//        if (!$user) {
//            $boolean_return = false;
//        }
//         dump($user);
//        die('$user');
//        dump($entity);
//        die('$entity');
        if ($entity) {
            if (count($entity) == 1) {
                foreach ($entity as $key => $value) {
                    $nom_bdd = $value->getNomBdd();
                    var_dump($nom_bdd);
                }
            }
        }

//        var_dump($nom_bdd);
        
        
        if ($user) {
//            var_dump($user);
//            var_dump($nom_bdd);
//            die('user');
            //php bin/console doctrine:schema:update --force --em=keolisTest
            //user existe + serach user in all data base + get user
            // 3cexternpaie OGUNE OGUNE
            if (count($entity) == 1) {
//                die('count + entity = 1');
                if ($nom_bdd != "") {
                    $sql = "SELECT * FROM"; //keolis_test // 28402994220260 1234 
                    $sql .= " ";
                    $sql .= $nom_bdd;
                    $sql .= ".user";
                    $sql .= " ";
                    $sql .= " where username ='" . $username . "'";
                    
                    $stmt = $Alldb->getConnection()->prepare($sql);
                    $stmt->execute();
                    $result2 = array();
                    $result2 = $stmt->fetchAll();
//                    var_dump($result2);
//                    die('$result2');
                    if (count($result2) == 1) {
                        //traittement 
                        foreach ($result2 as $key => $value) {
                            $id3 = $value['id'];
                            $username3 = $value['username'];
                            $password3 = $value['password'];
                            $salt3 = $value['salt'];
                            $active3 = $value['is_active'];
                        }
                        $bool = false;
                        if (hash('sha512', $salt3 . $plainpassword) == $password3) {
                            $bool = true;
                        } else {
//                            $boolean_return = false;
                        }
                        if ($bool) {
                            // update  user
                            if ($username3 != 'admin') {
                                $user->setEmail($username3 . '@' . $nom_bdd . '.' . 'fr');
                                $user->setUsername($username3);
                                $user->setSalt($salt3);
                                $user->setPassword($password3);
                                $user->setIsActive($active3);
                                $user->setRoles(array('ROLE_USER'));
//                                $var = "ici update + user";
//                                dump($var);
                                $db->persist($user);
                                $db->flush();
//                                die('update user');
//                                $boolean_return = true;
                            }
                        }
                    }
                }
            }

            if (count($entity) > 1) {
                die('count + entity > 1');
                foreach ($entity as $key => $value) {

//                    var_dump($value);
//                    die('ici');
                    $username = $value->getUsername();
                    $nom_bdd = $value->getNomBdd();
                    $sql = "SELECT * FROM"; //keolis_test // 28402994220260 1234 
                    $sql .= " ";
                    $sql .= $nom_bdd;
                    $sql .= ".user";
                    $sql .= " ";
                    $sql .= " where username ='" . $username . "'";

                    $stmt = $Alldb->getConnection()->prepare($sql);
                    $stmt->execute();
                    $result = array();
                    $result = $stmt->fetchAll();
                    foreach ($result as $key => $value) {
                        $id3 = $value['id'];
                        $username3 = $value['username'];
                        $password3 = $value['password'];
                        $salt3 = $value['salt'];
                        $active3 = $value['is_active'];
                    }

                    $bool = false;
                    if (hash('sha512', $salt3 . $plainpassword) == $password3) {
                        $bool = true;
                    } else {
//                        $boolean_return = false;
                    }
                    if ($bool) {
                        // insert new user

                        if ($username3 != 'admin') {
//                            var_dump($nom_bdd);

                            $user->setEmail($username3 . '@' . $nom_bdd . '.' . 'fr');
                            $user->setUsername($username3);
                            $user->setSalt($salt3);
                            $user->setPassword($password3);
                            $user->setIsActive($active3);
                            $user->setRoles(array('ROLE_USER'));
                            $db->persist($user);
//                            die('re enter');
                            $db->flush();
//                            $boolean_return = true;
//                            die('success');
                            break;
                        }
                    }
                }
            }
        } // end if
        else {

//            die('else');
//            dump($nom_bdd);
//            die('else');
            if ($nom_bdd != "") {
                if (count($entity) == 1) {
                    //insertion User
                    if ($nom_bdd != "") {  //3cconsultant_test // 28402994220260 1234 
                        $sql = "SELECT * FROM"; //keolis_test // 28402994220260 1234 
                        $sql .= " ";
                        $sql .= $nom_bdd;
                        $sql .= ".user";
                        $sql .= " ";
                        $sql .= " where username ='" . $username . "'";
                        $stmt = $Alldb->getConnection()->prepare($sql);
                        $stmt->execute();
                        $result = array();
                        $result = $stmt->fetchAll();
//                    dump($result);
//                    die('else');
                        if (count($result) == 1) {
                            //traittement 
                            foreach ($result as $key => $value) {
                                $id3 = $value['id'];
                                $username3 = $value['username'];
                                $password3 = $value['password'];
                                $salt3 = $value['salt'];
                                $active3 = $value['is_active'];
//                            var_dump($value);
//                            die('val');
                            }
                            $bool = false;
                            if (hash('sha512', $salt3 . $plainpassword) == $password3) {
                                $bool = true;
//                            die('enter bool');
                            } else {
//                                $boolean_return = false;
                            }

//                        dump($bool);
//                        die('bool');
//                         dump($entity);
//                        die('count + $entity');
                            //insertion User
                            if ($bool) {
                                // insert new user
                                if ($username3 != 'admin') {
                                    $u = new User();
                                    $u->setEmail($username3 . '@' . $nom_bdd . '.' . 'fr');
                                    $u->setUsername($username3);
                                    $u->setSalt($salt3);
                                    $u->setPassword($password3);
                                    $u->setIsActive($active3);
                                    $u->setRoles(array('ROLE_USER'));
//                                    $var = "ici insert + user";
//                                    dump($var);
//                                    dump($nom_bdd);
//                                    dump($id3);
//                                    die('icicicici');
                                    $db->persist($u);
                                    $db->flush();
                                }
                            }
                        }// end if
                    }
                }
//            $_array_object = array();
//            $index_array_object = 0;
                if (count($entity) > 1) {

                    foreach ($entity as $key => $value) {

//                    var_dump($value);
//                    die('ici');
                        $username = $value->getUsername();
                        $nom_bdd = $value->getNomBdd();
                        $sql = "SELECT * FROM"; //keolis_test // 28402994220260 1234 
                        $sql .= " ";
                        $sql .= $nom_bdd;
                        $sql .= ".user";
                        $sql .= " ";
                        $sql .= " where username ='" . $username . "'";

                        $stmt = $Alldb->getConnection()->prepare($sql);
                        $stmt->execute();
                        $result = array();
                        $result = $stmt->fetchAll();
                        foreach ($result as $key => $value) {
                            $id3 = $value['id'];
                            $username3 = $value['username'];
                            $password3 = $value['password'];
                            $salt3 = $value['salt'];
                            $active3 = $value['is_active'];
                        }

                        $bool = false;
                        if (hash('sha512', $salt3 . $plainpassword) == $password3) {
                            $bool = true;
                        } else {
//                            $boolean_return = false;
                        }
                        if ($bool) {
                            // insert new user

                            if ($username3 != 'admin') {
//                           var_dump($nom_bdd);
                                $u = new User();
                                $u->setEmail($username3 . '@' . $nom_bdd . '.' . 'fr');
                                $u->setUsername($username3);
                                $u->setSalt($salt3);
                                $u->setPassword($password3);
                                $u->setIsActive($active3);
                                $u->setRoles(array('ROLE_USER'));
                                $db->persist($u);
                                $db->flush();
//                                $boolean_return = true;
                                die('success + update + else');
                                break;
                            }
                        }
                    }
                }

                if (count($entity) == 0) {
//                    $boolean_return = false;
                }
            }
        }// end else
//        die('fin if + else');
//        return $boolean_return;
    }

    public function getCredentials(Request $request) {


//       $tt = $request->getPathInfo();
//        var_dump($tt);
//        var_dump($request->getMethod());
//        $this->RedirectionBeforeAuthentification();
//        $this->session->set('foo', 'bar');
//        $tt = $this->session->get('before');
//        dump($request->getMethod());
//            dump($request->getPathInfo());
//        die('getCredentialsss');
//        return $this->router->generate('test_redirect');
        //return new RedirectResponse($targetPath);
        //?client_id=7_4rs0ia42v4e8wowcoco08w0ssg400ss08osk0wgo08c08k80wk&response_type=code&redirect_uri=http%253A%252F%252Fwww.example.com&scope=read&state=6b00285a-f490-a75f-dfd8-40e5e00bf844
        //?client_id=cpa_by_intersa&response_type=code&redirect_uri=http%253A%252F%252Fwww.example.com&scope=read&state=6b00285a-f490-a75f-dfd8-40e5e00bf844
//        $isLoginSubmit = $request->getPathInfo() == '/login' && $request->isMethod('POST');
//        
//        $isLoginNonSubmit = $request->getPathInfo() == '/oauth/v2/auth_login' && $request->isMethod('GET');
//        if ($isLoginNonSubmit) {
////            $uri = $this->RedirectionBeforeAuthentification();
////            $response = new Response('');
////            return $response;
//           
//            dump($request->getPathInfo());
//            dump($request->getMethod());
//          die('getCredentials');
        $isLoginSubmit = $request->getPathInfo() == '/oauth/v2/auth_login' && $request->isMethod('POST'); //oauth/v2/auth
//        var_dump($isLoginSubmit);
//        var_dump($request->isMethod('POST'));
//        die('$isLoginSubmit');
        if (!$isLoginSubmit) {

            //die('skip authentication');
//             skip authentication
//            $this->RedirectionBeforeAuthentification();
//            die('skip authentication');
            return;
        }

        //soit aBG976smb77 soit 1234 soit 0000
//        $username = $request->request->get('_username');
//        $password = $request->request->get('_password');
//        $csrfToken = $request->request->get('_csrf_token');

        $data1 = $request->request->all();
//        dump($data1);
//        var_dump($csrfToken);
//        die('getCredentials');
        $csrfToken = $data1['_csrf_token'];
        $data2 = $data1['login_form'];
//        var_dump($data2);
//        die('getCredentials');
        $username = $data2['_username'];
        $password = $data2['_password'];
//        var_dump($username);
//        var_dump($password);
//        die('getCredentials2');
//        $this->session->set('_username', $username);
//        $this->session->set('_password', $password);
//        
//        dump($csrfToken);
//        dump($username);
//        dump($password);
//        die('avant $isLoginSubmit');
        if ($isLoginSubmit) {
//            die('enter authentificationcapuser');
            $this->AuthentificationCpaUser($username, $password);
//            $var = 'enter if AuthentificationCpaUser';
//            dump($var);
//            die(' fin AuthentificationCpaUser');
        }
//        die(' non enter authentificationcapuser');
//        var_dump($username);
//        var_dump($password);
//        var_dump($csrfToken);
//        die('getCredentials');
//        if ($isLoginSubmit) {
////            die('KeolisExisteUser + getCredentials + if');
//        }
//        die('getCredentials');
        if (false === $this->csrfTokenManager->isTokenValid(new CsrfToken('authenticate', $csrfToken))) {
            die('nooo');
            throw new InvalidCsrfTokenException('Invalid CSRF token.');
        }
//        die('yess');
        $request->getSession()->set(
                Security::LAST_USERNAME, $username
        );
//    die('getCredentials');
        return [
            '_username' => $username,
            '_password' => $password,
        ];
//        $form = $this->formFactory->create(LoginForm::class);
//        $form->handleRequest($request);
//
//        $data = $form->getData();
////        var_dump($data);
////        die('$data');
//        $request->getSession()->set(
//                Security::LAST_USERNAME, $data['_username']
//        );
//
//        return $data;
    }

    public function getUser($credentials, UserProviderInterface $userProvider) {
//        $tt = $credentials['_username'];
//        dump($tt);
//        die('getUser');
        $username = $credentials['_username'];
//        $this->session->set('_username', $username);
//
//        $tt = $this->em->getRepository('UserBundle:User')
//                ->findOneBy(['username' => $username]);
//        dump($tt);
//        die('getUser2');

        return $this->em->getRepository('UserBundle:User')
                        ->findOneBy(['username' => $username]);
    }

    public function checkCredentials($credentials, UserInterface $user) {
//       $tt = $credentials['_password'];
//        dump($tt);
//        die('checkCredentials');
        $password = $credentials['_password']; //iliketurtles
//        $this->session->set('_password', $password);
        $plainpassword = $password; // //soit aBG976smb77 soit 1234 soit 0000
//        var_dump($password);
//        die('checkCredentials');



        if (hash('sha512', $user->getSalt() . $plainpassword) == $user->getPassword()) {
//            die('true');
            return true;
        }
//        die('false');
        return false;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey) {
//        var_dump($token);
//        var_dump($providerKey);
//        die('onAuthenticationSuccess');
//         dump($this->session->get('_username'));
//        dump($this->session->get('_password'));
//        $targetPath = null;
        // if the user hit a secure page and start() was called, this was
        // the URL they were on, and probably where you want to redirect to
        $targetPath = $this->getTargetPath($request->getSession(), $providerKey);
//        var_dump($targetPath);
//         die('onAuthenticationSuccess');
        $targetPath = $this->router->generate('user_homepage');





//        dump($targetPath);
//        die('$targetPath');
//        if ($targetPath) {//
////            die('enter target path');//aBG976smb77
////            $targetPath = $this->router->generate('code_review_rest_homepage'); 
//            $targetPath = $this->router->generate('user_homepage');//aBG976smb77
////            $targetPath = $this->router->generate('authorisation_controller');
////            var_dump($targetPath);
////         die('onAuthenticationSuccess');
//        }
//        die('onAuthenticationSuccess2');
        return new RedirectResponse($targetPath);
    }

//    public function start(Request $request) {
//        
//        die('start');
//    }
    protected function getLoginUrl() {
//        die('getLoginUrl');
//        $this->session->remove('_username');
//        $this->session->remove('_password');
        //acme_oauth_server_auth_login_check
        //acme_oauth_server_auth_login
        //acme_oauth_server_auth_login
//        return $this->router->generate('acme_oauth_server_auth_login');
        return $this->router->generate('acme_oauth_server_auth_login_check');
//        return $this->router->generate('api_articles');
//        return $this->router->generate('security_logout');
    }

//    protected function getLoginUrl() {
//        die('getLoginUrl');
//        return $this->router->generate('security_login');
//    }
//    protected function getDefaultSuccessRedirectUrl() {
//        die('getDefaultSuccessRedirectUrl');
//        return $this->router->generate('homepage');
////        return $this->router->generate('code_review_rest_homepage');
//    }
//    public function functionName($param) {
//        //verif + pwd + bcrypt:
//        if ($this->passwordEncoder->isPasswordValid($user, $password)) {
//            return true;
//        }
//
//        return false;
//    }
}
