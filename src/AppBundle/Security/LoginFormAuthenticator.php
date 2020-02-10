<?php

namespace AppBundle\Security;

use AppBundle\Form\LoginForm;
use Doctrine\ORM\EntityManager;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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
use Symfony\Component\Security\Http\Util\TargetPathTrait;
//use Symfony\Component\HttpFoundation\ParameterBag;
//use AppBundle\Entity\User;
//use AppBundle\Entity\Correspendance;
use CPA\UserBundle\Entity\User;
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

    public function __construct(Registry $doctrine, EntityManager $em, RouterInterface $router, UserPasswordEncoder $passwordEncoder, CsrfTokenManagerInterface $csrfTokenManager, RequestStack $request) {

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
//        return $this->router->generate('test_redirect');
       
//        $this->RedirectUrl();
//        die('__construct2');
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
//         var_dump($user);
//        die('$user');
//        var_dump($entity);
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
                die('count + entity = 1');
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
                                $user->setEnabled($active3);
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
                            $user->setEnabled($active3);
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

            die('else');
            if (count($entity) == 1) {
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
//                var_dump($result);
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
                            die('enter bool');
                        }

                        if ($bool) {
                            // insert new user
                            if ($username3 != 'admin') {
                                $u = new User();
                                $u->setEmail($username3 . '@' . $nom_bdd . '.' . 'fr');
                                $u->setUsername($username3);
                                $u->setSalt($salt3);
                                $u->setPassword($password3);
                                $u->setEnabled($active3);
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
                            $u->setEnabled($active3);
                            $db->persist($u);
                            $db->flush();
//                             die('success');
                            break;
                        }
                    }
                }
            }
        }// end else
//        die('fin if + else');
    }

    public function getCredentials(Request $request) {
//       $tt = $request->getPathInfo();
//        var_dump($tt);
//        var_dump($request->isMethod('POST'));
//        die('getCredentials');
//        return $this->router->generate('test_redirect');
        
//        die('getCredentials');
        //return new RedirectResponse($targetPath);
        //?client_id=7_4rs0ia42v4e8wowcoco08w0ssg400ss08osk0wgo08c08k80wk&response_type=code&redirect_uri=http%253A%252F%252Fwww.example.com&scope=read&state=6b00285a-f490-a75f-dfd8-40e5e00bf844
        //?client_id=cpa_by_intersa&response_type=code&redirect_uri=http%253A%252F%252Fwww.example.com&scope=read&state=6b00285a-f490-a75f-dfd8-40e5e00bf844
//        $isLoginSubmit = $request->getPathInfo() == '/login' && $request->isMethod('POST');
        $isLoginSubmit = $request->getPathInfo() == '/oauth/v2/auth' && $request->isMethod('POST');
        if (!$isLoginSubmit) {
            // skip authentication
//             die('skip authentication');
            return;
        }
//        die('getCredentials2');
        //soit aBG976smb77 soit 1234 soit 0000
//        $username = $request->request->get('_username');
//        $password = $request->request->get('_password');
//        $csrfToken = $request->request->get('_csrf_token');

        $data1 = $request->request->all();
        $csrfToken = $data1['_csrf_token'];
//        var_dump($data1);
//        var_dump($csrfToken);
//        die('getCredentials');
        $data2 = $data1['login_form'];
//        var_dump($data2);
//        die('getCredentials');
        $username = $data2['_username'];
        $password = $data2['_password'];


//        var_dump($username);
//        var_dump($password);
//        var_dump($csrfToken);
//        die('getCredentials');
        if ($isLoginSubmit) {
//            die('KeolisExisteUser + getCredentials + if');

            $this->KeolisExisteUser($username, $password);
        }


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
//        var_dump($tt);
        die('getUser');
        $username = $credentials['_username'];

//        $tt = $this->em->getRepository('UserBundle:User')
//            ->findOneBy(['username' => $username]);
//        var_dump($tt);
//        die('getUser');
        return $this->em->getRepository('UserBundle:User')
                        ->findOneBy(['username' => $username]);
    }

    public function checkCredentials($credentials, UserInterface $user) {
//       $tt = $credentials['_password'];
//        var_dump($tt);
        die('checkCredentials');
        $password = $credentials['_password']; //iliketurtles
        $plainpassword = $password; // //soit aBG976smb77 soit 1234 soit 0000
//        var_dump($password);
//        die('checkCredentials');



        if (hash('sha512', $user->getSalt() . $plainpassword) == $user->getPassword()) {
//            die('true');
            return true;
        }
        die('false');
        return false;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey) {
//        var_dump($token);
//        var_dump($providerKey);
        die('onAuthenticationSuccess');
        $targetPath = null;

        // if the user hit a secure page and start() was called, this was
        // the URL they were on, and probably where you want to redirect to
        $targetPath = $this->getTargetPath($request->getSession(), $providerKey);
//        var_dump($targetPath);
//         die('onAuthenticationSuccess');


        if (!$targetPath) {//
//            die('enter target path');//aBG976smb77
//            $targetPath = $this->router->generate('code_review_rest_homepage'); 
            $targetPath = $this->router->generate('api_articles');
//            $targetPath = $this->router->generate('api_ouath_authorize');
        }
//        die('onAuthenticationSuccess');
        return new RedirectResponse($targetPath);
    }

    protected function getLoginUrl() {
//        die('getLoginUrl');
//        return $this->router->generate('security_login');//
//        return $this->router->generate('api_articles');
        return $this->router->generate('security_logout');
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
