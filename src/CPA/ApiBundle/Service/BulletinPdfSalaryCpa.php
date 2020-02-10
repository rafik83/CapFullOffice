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
//use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\DependencyInjection\ContainerInterface;

//use CPA\DoctrineExtensions\DBAL\Types\UTCDateTimeType;

class BulletinPdfSalaryCpa {

    use TargetPathTrait;

//    private $formFactory;
    private $em;
    private $router;
    private $passwordEncoder;
    private $csrfTokenManager;
    private $doctrine;
    private $requestStack;
    private $session;
    private $container;

    public function __construct(ContainerInterface $container, Session $session, Registry $doctrine, EntityManager $em, RouterInterface $router, UserPasswordEncoder $passwordEncoder, CsrfTokenManagerInterface $csrfTokenManager, RequestStack $request) {

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
        $this->container = $container;
        $this->session = $session; //$session->set('foo', 'bar');
//        return $this->router->generate('test_redirect');
//        $this->RedirectUrl();
//        die('__construct2');
    }

    public function getPdfSalary($tokenOAuth, $identifiantBse) {
        $em = $this->doctrine->getManager('default');
        $IdSignedDocCpa = $identifiantBse;
        require '../vendor/autoload.php';
        $client = new \GuzzleHttp\Client(['verify' => false]);
        $url = 'https://cpa.fulloffice.fr/oauth/v2/token?client_id=8_59i5pcmanvwog4coows8gg84408ckwk8444w0occ00sg8kkgsw&client_secret=u0am06sua7ko40w4s404ok84g4goc88w048gsg8skgos8gc8c&grant_type=client_credentials';
        $response = $client->request('GET', $url);
        $data = json_decode($response->getBody()->getContents(), true);
        $accessToken = $data['access_token'];
//        $expires_in = $data['expires_in'];
//        $expiresAt = new \DateTime('+' . $expires_in . ' seconds');
//        $expires_in = $expiresAt;
        $refresh_token = $data['refresh_token'];
        if ($accessToken) {
            if ($accessToken != $tokenOAuth) {
                return new Response('Non Autorisé', 401);
            } else {
                $ObjectAccessToken = $this->findUserIdByAccessToken($accessToken, $em);
                if ($ObjectAccessToken) {
                    $user_id = $ObjectAccessToken[0]['user_id'];
                    $user = $em->getRepository('UserBundle:User')->findUserById($user_id);
                    $array_pdf_salary = array();
                    if ($user) {
                        $sdoc = $em->getRepository('UserBundle:SignedDoc')->getBsebyId($identifiantBse);

                        if ($sdoc) {
                            $signature = $sdoc[0]['signature'];

                            $record = $sdoc[0]['record'];
                            $dataSignedDoc = $this->container->get('cert_sign.server')->getDocument($signature, $record);
                            if ($dataSignedDoc) {
                                return $dataSignedDoc;
                            } else {
                                return new Response("Temps d'attente écoulé", 504);
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
                        $array_pdf_salary = array();
                        if ($user) {
                            $sdoc = $em->getRepository('UserBundle:SignedDoc')->getBsebyId($identifiantBse);

                            if ($sdoc) {
                                $signature = $sdoc[0]['signature'];

                                $record = $sdoc[0]['record'];
                                $dataSignedDoc = $this->container->get('cert_sign.server')->getDocument($signature, $record);
                                if ($dataSignedDoc) {
                                    return $dataSignedDoc;
                                } else {
                                    return new Response("Temps d'attente écoulé", 504);
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
//        $sql = "SELECT * FROM auth_code"; 
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

}
