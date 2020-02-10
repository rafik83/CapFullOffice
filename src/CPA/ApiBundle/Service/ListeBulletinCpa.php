<?php

namespace CPA\ApiBundle\Service;

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
//use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
//use AppBundle\Entity\User;
//use AppBundle\Entity\Correspendance;
use OAuthServerBundle\Entity\AccessToken;
use CPA\UserBundle\Entity\User;
use CPA\OAuthServerBundle\Entity\AuthCode;
use CPA\OAuthServerBundle\Entity\RefreshToken;
use CPA\UserBundle\Entity\Salary;
use CPA\UserBundle\Entity\SignedDoc;
use Symfony\Component\Validator\Constraints\DateTime;

//use CPA\DoctrineExtensions\DBAL\Types\UTCDateTimeType;

class ListeBulletinCpa {

    use TargetPathTrait;

//    private $formFactory;
    private $em;
    private $router;
    private $passwordEncoder;
    private $csrfTokenManager;
    private $doctrine;
    private $requestStack;
    private $session;

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
//        return $this->router->generate('test_redirect');
//        $this->RedirectUrl();
//        die('__construct2');
    }

    public function ListeBulletinCpa($tokenOAuth, $dateDebut, $dateFin) {


       
        
        $client = new \GuzzleHttp\Client(['verify' => false]);
        $url = 'https://cpa.fulloffice.fr/oauth/v2/token?client_id=8_59i5pcmanvwog4coows8gg84408ckwk8444w0occ00sg8kkgsw&client_secret=u0am06sua7ko40w4s404ok84g4goc88w048gsg8skgos8gc8c&grant_type=client_credentials';
        $response = $client->request('GET', $url);
        $data = json_decode($response->getBody()->getContents(), true);
        $accessToken = $data['access_token'];
           return new Response('$accessToken', $accessToken);
//        $expires_in = $data['expires_in'];
//        $expiresAt = new \DateTime('+' . $expires_in . ' seconds');
//        $expires_in = $expiresAt;
        $refresh_token = $data['refresh_token'];
        $em = $this->doctrine->getManager('default');
        if ($accessToken) {
            if ($accessToken != $tokenOAuth) {
                return new Response('Non Autorisé', 401);
            } else {

                $ObjectAccessToken = $this->findUserIdByAccessToken($accessToken, $em);
                if ($ObjectAccessToken) {
                    $user_id = $ObjectAccessToken[0]['user_id'];
                    $user = $em->getRepository('UserBundle:User')->findUserById($user_id);
                    $array_find_all_salary = array();
                    $index_find_all_salary = 0;
                    if ($user) {
                        $Salary = $em->getRepository('UserBundle:Salary')->findSalarybyUser($user);
                        if ($Salary) {

                            if (count($Salary) == 1) {
//                    $bulletins = $em->getRepository('UserBundle:SignedDoc')->findBseBySalary($Salary);
                                foreach ($Salary as $key => $value) {
                                    $array_find_all_salary[$index_find_all_salary] = $this->findBulletinBySalaryArray($dateDebut, $dateFin, $value['id'], $em);
                                    $index_find_all_salary ++;
                                }
                                if ($array_find_all_salary) {
                                    return $array_find_all_salary;
                                } else {
                                    return new Response("Temps d'attente écoulé", 504);
                                }
                            }
                            if (count($Salary) > 1) {
                                foreach ($Salary as $key => $value) {
                                    $array_find_all_salary[$index_find_all_salary] = $this->findBulletinBySalaryArray($dateDebut, $dateFin, $value['id'], $em);
                                    $index_find_all_salary ++;
                                }

                                if ($array_find_all_salary) {
                                    return $array_find_all_salary;
                                } else {
                                    return new Response("Temps d'attente écoulé", 504);
                                }
                            }
                        } else {

                            return new Response("Pas de Documents pour l'tilisateur ou les critéres", 404);
                        }
                    } else {
                        throw new AccessDeniedException();
                    }
                }
            }
        }
        if (!$accessToken) {
            if ($refresh_token) {
                if ($refresh_token != $tokenOAuth) {
                    return new Response('Non Autorisé', 401);
                } else {

                    $ObjectRefreshToken = $this->findUserIdByRefreshToken($refresh_token, $em);
                    if ($ObjectRefreshToken) {
                        $user_id = $ObjectRefreshToken[0]['user_id'];
                        $user = $em->getRepository('UserBundle:User')->findUserById($user_id);
                        $array_find_all_salary = array();
                        $index_find_all_salary = 0;
                        if ($user) {
                            $Salary = $em->getRepository('UserBundle:Salary')->findSalarybyUser($user);
                            if ($Salary) {

                                if (count($Salary) == 1) {
//                    $bulletins = $em->getRepository('UserBundle:SignedDoc')->findBseBySalary($Salary);
                                    foreach ($Salary as $key => $value) {
                                        $array_find_all_salary[$index_find_all_salary] = $this->findBulletinBySalaryArray($dateDebut, $dateFin, $value['id'], $em);
                                        $index_find_all_salary ++;
                                    }
                                    if ($array_find_all_salary) {
                                        return $array_find_all_salary;
                                    } else {
                                        return new Response("Temps d'attente écoulé", 504);
                                    }
                                }
                                if (count($Salary) > 1) {
                                    foreach ($Salary as $key => $value) {
                                        $array_find_all_salary[$index_find_all_salary] = $this->findBulletinBySalaryArray($dateDebut, $dateFin, $value['id'], $em);
                                        $index_find_all_salary ++;
                                    }

                                    if ($array_find_all_salary) {
                                        return $array_find_all_salary;
                                    } else {
                                        return new Response("Temps d'attente écoulé", 504);
                                    }
                                }
                            } else {

                                return new Response("Pas de Documents pour l'tilisateur ou les critéres", 404);
                            }
                        } else {
                            throw new AccessDeniedException();
                        }
                    }
                }
            }
        }
    }

    public function findAuthCodeByToken($accesstoken, $em) {
//        $sql = "SELECT * FROM auth_code";//db_cpa_fulloffice.auth_code
        $sql = "SELECT * FROM db_cpa_fulloffice.auth_code";
        $sql .= " ";
        $sql .= "where token ='" . $accesstoken . "'";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findUserIdByAccessToken($accesstoken, $em) {
//        $sql = "SELECT * FROM auth_code";//db_cpa_fulloffice.auth_code
        $sql = "SELECT * FROM db_cpa_fulloffice.access_token";
        $sql .= " ";
        $sql .= "where token ='" . $accesstoken . "'";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findUserIdByRefreshToken($refresh_token, $em) {
//        $sql = "SELECT * FROM auth_code";//db_cpa_fulloffice.auth_code
        $sql = "SELECT * FROM db_cpa_fulloffice.refresh_token";
        $sql .= " ";
        $sql .= "where token ='" . $refresh_token . "'";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findBulletinBySalaryArray($date_debut, $date_fin, $IdSalary, $em) {
        //SELECT id, salary_id,  ,   date_debut, date_fin FROM signed_doc WHERE 1
        //, c.salary_id as salary_id 
//        $date_debut = '2017-01-01';
//        $date_fin = '2017-12-31';
//        $sql = "SELECT c.id as identifiantBse, c.date_debut as dateDebut, c.date_fin as dateFin, s.company_name as employeur FROM signed_doc c  left join salary s on c.salary_id = s.id";
        $sql = "SELECT c.id as identifiantBse, c.date_debut as dateDebut, c.date_fin as dateFin, s.company_name as employeur FROM db_cpa_fulloffice.signed_doc c  left join db_cpa_fulloffice.salary s on c.salary_id = s.id";
        $sql .= " ";
        $sql .= "where c.salary_id ='" . $IdSalary . "'";
        $sql .= " ";
        $sql .= "AND";
        $sql .= " ";
        $sql .= " c.date_debut BETWEEN '" . $date_debut . "'   AND   '" . $date_fin . "'   ";
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        //date_concert BETWEEN '2016-01-01' AND '2016-12-31'

        return $stmt->fetchAll();
    }

}
