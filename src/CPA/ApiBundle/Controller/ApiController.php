<?php

namespace CPA\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use CPA\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
//use OAuth2\HttpFoundationBridge\Request;
use OAuth2\ServerBundle\Controller\AuthorizeController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use OAuthServerBundle\Entity\AccessToken;
use CPA\OAuthServerBundle\Entity\AuthCode;
use CPA\OAuthServerBundle\Entity\RefreshToken;
use CPA\UserBundle\Entity\Salary;
use CPA\UserBundle\Entity\SignedDoc;
use Symfony\Component\HttpFoundation\HeaderBag;
use Swagger\Annotations as SWG;
use swagger\definitions\BaseDefinition;
use Symfony\Component\Validator\Constraints\DateTime;





/**
 *  @SWG\Swagger(
 *   @SWG\Info(
 *     version="1.0.0",
 *     title="API Intersa"
 *   ),
 *   host ="cpa.fulloffice.fr",
 *   schemes={"https"},
 *   basePath ="/api",
 *   
 * ),
 *    @SWG\SecurityScheme(
 *        securityDefinition="access_token",
 *         type="oauth2",
 *         description="jeton generer par le Bundle OauthServerBundle développer en symfony2 qui est un serveur permetton de generer les jetons: access_token,refresh_token est qui permet de sécurisé Api REST",
 *         authorizationUrl="https://cpa.fulloffice.fr/oauth/v2/auth?client_id=8_59i5pcmanvwog4coows8gg84408ckwk8444w0occ00sg8kkgsw&response_type=code&redirect_uri=https://www.moncompteactivite.gouv.fr/idp/obs/intersa/callback&scope=read&state=8834e812-702d-7de3-95d7-2fbdafe6a823",
 *         tokenUrl="https://cpa.fulloffice.fr/oauth/v2/token?client_id=8_59i5pcmanvwog4coows8gg84408ckwk8444w0occ00sg8kkgsw&client_secret=u0am06sua7ko40w4s404ok84g4goc88w048gsg8skgos8gc8c&grant_type=client_credentials",
 *         flow="accessCode",
 *         scopes={
 *                   "list:bulletin": "Lister mes bulletins de paie",
 *                    "read:bulletin": "Accéder a  mes bulletins de paie"
 *                 }
 *        
 *  ),
 *  
 */
class ApiController extends FOSRestController {
    
    /**
     * @SWG\Get(
     *   path="/bse",
     *   produces={"application/json"},
     *   description="liste des documents correspondant aux critéres de recherche.",
     *      @SWG\Parameter(
     *         name="dateDebut",
     *         in="query",
     *         description="Date de Début de la recherche des documents (MM/yyyy)",
     *         required=true,
     *         type="string",
     *         format="date-time",
     *     ),
     * 
     *      @SWG\Parameter(
     *         name="dateFin",
     *         in="query",
     *         description="Date de fin de la  recherche des documents (MM/yyyy)",
     *         required=true,
     *         type="string",
     *         format="date-time",
     *     ),
     *   @SWG\Response(
     *     response=200,
     *     description="liste des documents",
     *        @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Document")
     *         ),
     *   ),
     *   @SWG\Response(
     *     response=401,
     *     description="Non autorisé"
     *   ),
     *   
     *   @SWG\Response(
     *     response=404,
     *     description="Pas de documents pour l'utilisateur ou les critéres"
     *   ),
     * 
     *    @SWG\Response(
     *     response=504,
     *     description="Temps d'attente écoulé"
     *   ),
     *   security={
     *         {
     *             "access_token": {"list:bulletin"}
     *         }
     *     }
     * )
     */
    public function listeBulletinAction(Request $request) {
        // $tt = $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN');
//        $em = $this->getDoctrine()->getManager();
        require '../vendor/autoload.php';
        $swagger = \Swagger\scan(__DIR__);
         //var_dump($swagger);
        //die('$swagger');
        header('Content-Type: application/json');
//        dump($swagger);


        $body = $request->getContent();
        $data = json_decode($body, TRUE);
        $tokenOAuth = $data['tokenOAuth'];
        $dateDebut = $data['dateDebut']; //'2017-01-01'
        $dateFin = $data['dateFin']; //'2017-12-31';
        $explode1 = explode('/', $dateDebut);
        $day_debut = '01';
        $month_debut = $explode1[0];
        $year_debut = $explode1[1];
        $dateDebut = $year_debut . '-' . $month_debut . '-' . $day_debut;
        $explode2 = explode('/', $dateFin);
        $day_fin = '';
        $month_fin = $explode2[0];
        $year_fin = $explode2[1];

        switch ($month_fin) {
            case '1':
                $day_fin = '31';
                break;
            case '2':
                $day_fin = '28';
                break;
            case '3':
                $day_fin = '31';
                break;
            case '4':
                $day_fin = '30';
                break;
            case '5':
                $day_fin = '31';
                break;
            case '6':
                $day_fin = '30';
                break;
            case '7':
                $day_fin = '31';
                break;
            case '8':
                $day_fin = '31';
                break;
            case '9':
                $day_fin = '30';
                break;
            case '10':
                $day_fin = '31';
                break;
            case '11':
                $day_fin = '30';
                break;
            case '12':
                $day_fin = '31';
                break;
        }

        $dateFin = $year_fin . '-' . $month_fin . '-' . $day_fin;
        //$explode[0] = 01
        ////$explode[1] = 2017
        $array_find_all_salary2 = array();
        //$array_find_all_salary = $this->container->get('app.liste.bulletin.cpa')->ListeBulletinCpa($tokenOAuth, $dateDebut, $dateFin);
        $em = $this->getDoctrine()->getManager();
        $accessToken = $tokenOAuth;
        $refreshToken = $tokenOAuth;
        $ObjectAccessToken = $this->findUserIdByAccessToken($accessToken, $em);
        $ObjectRefreshToken = $this->findUserIdByRefreshToken($refreshToken, $em);
	$date_courante = date("d/m/Y H:i:s");
	 if (($ObjectRefreshToken == NULL) && ($ObjectAccessToken == NULL)) {
           
         $JsonResponse = new JsonResponse(array("401" => "Unauthorized"));
           return $JsonResponse;
        }

	
	if ($ObjectAccessToken) {
            $user_id_access = $ObjectAccessToken[0]['user_id'];
            if ($user_id_access != null) {
                $expires_at_access = $ObjectAccessToken[0]['expires_at'];
                //convert timestamp en date
                $date_expire_at_access = date('d/m/Y H:i:s', $expires_at_access);
                $today_time = strtotime($date_courante);
                $expire_time = strtotime($date_expire_at_access);
                if ($expire_time < $today_time) {
                    $JsonResponse = new JsonResponse(array("invalid_request" => "access token expiré !"));
                    return $JsonResponse;
                }
            }
        }



	if ($ObjectRefreshToken) {
            $user_id_refresh = $ObjectRefreshToken[0]['user_id'];
            if ($user_id_refresh != null) {
                $expires_at_refresh = $ObjectRefreshToken[0]['expires_at'];
                //convert timestamp en date
                $date_expire_at_refresh = date('d/m/Y H:i:s', $expires_at_refresh);
                $today_time2 = strtotime($date_courante);
                $expire_time2 = strtotime($date_expire_at_refresh);
                if ($expire_time2 < $today_time2) {
                    $JsonResponse = new JsonResponse(array("invalid_request" => "Refresh token expiré !"));
                    return $JsonResponse;
                }
            }
        }



        if ($ObjectAccessToken) {
            $user_id_access = $ObjectAccessToken[0]['user_id'];
            if ($user_id_access != NULL) {
                $user = $em->getRepository('UserBundle:User')->findUserById($user_id_access);
                $array_find_all_salary = array();
                $index_find_all_salary = 0;
                if ($user) {
                    $Salary = $em->getRepository('UserBundle:Salary')->findSalarybyUser($user);
                    if ($Salary) {

                        if (count($Salary) == 1) {
                            foreach ($Salary as $key => $value) {
                                $array_find_all_salary[$index_find_all_salary] = $this->findBulletinBySalaryArray($dateDebut, $dateFin, $value['id'], $em);
                                $index_find_all_salary ++;
                            }


				foreach ($array_find_all_salary as $key => $value) {
				if ($value == NULL) {
                                    $JsonResponse = new JsonResponse(array("Pas de Documents pour l'utilisateur ou les critéres" => "404"));
                                    return $JsonResponse;
                                } 
                            }
				
                            if ($array_find_all_salary) {
                                $array_find_all_salary2 = $array_find_all_salary;
                            } else {
                                $JsonResponse = new JsonResponse(array("Temps d'attente écoulé" => "504"));
                                return $JsonResponse;
                            }
                        }
                        if (count($Salary) > 1) {
                            foreach ($Salary as $key => $value) {
                                $array_find_all_salary[$index_find_all_salary] = $this->findBulletinBySalaryArray($dateDebut, $dateFin, $value['id'], $em);
                                $index_find_all_salary ++;
                            }

                            if ($array_find_all_salary) {
                                $array_find_all_salary2 = $array_find_all_salary;
                            } else {
                                $JsonResponse = new JsonResponse(array("Temps d'attente écoulé" => "504"));
                                return $JsonResponse;
                            }
                        }
                    } else {

                        $JsonResponse = new JsonResponse(array("Pas de Documents pour l'utilisateur ou les critéres" => "404"));
                        return $JsonResponse;
                    }
                } else {
                    throw new AccessDeniedException();
                }
            }
        }
        if ($ObjectRefreshToken) {
            $user_id_refresh = $ObjectRefreshToken[0]['user_id'];
            if ($user_id_refresh != NULL) {
                 //$JsonResponse = new JsonResponse(array("user_id_refresh" => $user_id_refresh));
                 //return $JsonResponse;
		$user = $em->getRepository('UserBundle:User')->findUserById($user_id_refresh);
 		//$JsonResponse = new JsonResponse(array("user" => $user));
                 //return $JsonResponse;
                $array_find_all_salary = array();
                $index_find_all_salary = 0;
                if ($user) {
                    $Salary = $em->getRepository('UserBundle:Salary')->findSalarybyUser($user);
			//$JsonResponse = new JsonResponse(array("Salary" => $Salary));
               		//return $JsonResponse;
                    if ($Salary) {

                        if (count($Salary) == 1) {
                            foreach ($Salary as $key => $value) {
                                $array_find_all_salary[$index_find_all_salary] = $this->findBulletinBySalaryArray($dateDebut, $dateFin, $value['id'], $em);
                                $index_find_all_salary ++;
                            }



				foreach ($array_find_all_salary as $key => $value) {
				if ($value == NULL) {
                                    $JsonResponse = new JsonResponse(array("Pas de Documents pour l'utilisateur ou les critéres" => "404"));
                                    return $JsonResponse;
                                } 
                            }

                            if ($array_find_all_salary) {
                                $array_find_all_salary2 = $array_find_all_salary;
                            } else {
                                $JsonResponse = new JsonResponse(array("Temps d'attente écoulé" => "504"));
                                return $JsonResponse;
                            }
                        }
                        if (count($Salary) > 1) {
                            foreach ($Salary as $key => $value) {
                                $array_find_all_salary[$index_find_all_salary] = $this->findBulletinBySalaryArray($dateDebut, $dateFin, $value['id'], $em);
                                $index_find_all_salary ++;
                            }

                            if ($array_find_all_salary) {
                                $array_find_all_salary2 = $array_find_all_salary;
                            } else {
                                $JsonResponse = new JsonResponse(array("Temps d'attente écoulé" => "504"));
                                return $JsonResponse;
                            }
                        }
                    } else {

                        $JsonResponse = new JsonResponse(array("Pas de Documents pour l'utilisateur ou les critéres" => "404"));
                        return $JsonResponse;
                    }
                } else {
                    throw new AccessDeniedException();
                }
            }
        }
        
        foreach ($array_find_all_salary2 as $key => $value) {
            if ($value) {
//                            return new Response(dump($value));
                $JsonResponse = new JsonResponse(array("listeBulletin" => $value));
                return $JsonResponse;
            }
        }

           
    }

    /**
     * @SWG\Get(
     *   path="/bse/{docID}",
     *   produces={"application/octet-stream"},
     *   description="Téléchargement d'un document a partir de son ID.En réponse, le document au format PDF.",
     *      @SWG\Parameter(
     *         name="docID",
     *         in="path",
     *         description="Identifiant unique du document recherché",
     *         required=true,
     *         type="string",
     *         pattern="^.{1,32}$",
     *     ),
     * 
     *   @SWG\Response(
     *     response=200,
     *     description="Fichier PDF",
     *        @SWG\Schema(
     *            title="document",
     *            type="object",
     *                properties={@Swagger\Annotations\Property(type="string", property="file")}
     *            ),
     *       )
     *         ),
     *   ),
     *   @SWG\Response(
     *     response=401,
     *     description="Non autorisé"
     *   ),
     *   
     *   @SWG\Response(
     *     response=404,
     *     description="Pas de documents pour l'utilisateur ou les critéres"
     *   ),
     * 
     *    @SWG\Response(
     *     response=504,
     *     description="Temps d'attente écoulé"
     *   ),
     *   security={
     *         {
     *             "access_token": {"read:bulletin"}
     *         }
     *     }
     * ),
     *    @SWG\Definition(definition="Document",type="object",additionalProperties="false",
     *         @SWG\Property(property="identifiantBse", description="Identifiat du document", type="string", pattern="^.{1,32}$"),
     *         @SWG\Property(property="employeur", description="Employeur", type="string", pattern="^.{1,200}$"),
     *         @SWG\Property(property="dateDebut", description="Date début (MM/yyyy)", type="string", format="date-time"),
     *         @SWG\Property(property="dateFin", description="Date fin (MM/yyyy)", type="string", format="date-time"),
     *         @SWG\Property(property="salaireNet", description="Salaire net", type="number", format="double"),
     *         @SWG\Property(property="datePaiement", description="Date paiement (MM/yyyy)", type="string", format="date-time")
     * ),
     */
    public function downloadBulletinAction($identifiantBse, Request $request) {
        require '../vendor/autoload.php';
        $swagger = \Swagger\scan(__DIR__);
//         dump($swagger);
//        die('$swagger');
        header('Content-Type: application/json');
        $body = $request->getContent();
        $data = json_decode($body, TRUE);
        $tokenOAuth = $data['tokenOAuth'];
        $requestUri = $request->getRequestUri();
        $explode = explode('/api/bse/', $requestUri);
        $identifiantBse = $explode[1];
        //$dataSignedDoc = $this->container->get('app.bulletin.pdf.cpa')->getPdfSalary($tokenOAuth, $identifiantBse);


	$em = $this->getDoctrine()->getManager();
        $accessToken = $tokenOAuth;
        $refreshToken = $tokenOAuth;
        $ObjectAccessToken = $this->findUserIdByAccessToken($accessToken, $em);
        $ObjectRefreshToken = $this->findUserIdByRefreshToken($refreshToken, $em);
        $IdSignedDocCpa = $identifiantBse;
	$date_courante = date("d/m/Y H:i:s");

	 if (($ObjectRefreshToken == NULL) && ($ObjectAccessToken == NULL)) {
           
          $JsonResponse = new JsonResponse(array("401" => "Unauthorized"));
           return $JsonResponse;
        
        }

	
	if ($ObjectAccessToken) {
            $user_id_access = $ObjectAccessToken[0]['user_id'];
            if ($user_id_access != null) {
                $expires_at_access = $ObjectAccessToken[0]['expires_at'];
                //convert timestamp en date
                $date_expire_at_access = date('d/m/Y H:i:s', $expires_at_access);
                $today_time = strtotime($date_courante);
                $expire_time = strtotime($date_expire_at_access);
                if ($expire_time < $today_time) {
                    $JsonResponse = new JsonResponse(array("invalid_request" => "access token expiré !"));
                    return $JsonResponse;
                }
            }
        }



	if ($ObjectRefreshToken) {
            $user_id_refresh = $ObjectRefreshToken[0]['user_id'];
            if ($user_id_refresh != null) {
                $expires_at_refresh = $ObjectRefreshToken[0]['expires_at'];
                //convert timestamp en date
                $date_expire_at_refresh = date('d/m/Y H:i:s', $expires_at_refresh);
                $today_time2 = strtotime($date_courante);
                $expire_time2 = strtotime($date_expire_at_refresh);
                if ($expire_time2 < $today_time2) {
                    $JsonResponse = new JsonResponse(array("invalid_request" => "Refresh token expiré !"));
                    return $JsonResponse;
                }
            }
        }

	



	if ($ObjectAccessToken) {
            $user_id_access = $ObjectAccessToken[0]['user_id'];
            if ($user_id_access != NULL) {
                $user = $em->getRepository('UserBundle:User')->findUserById($user_id_access);
                if ($user) {
                    $sdoc = $em->getRepository('UserBundle:SignedDoc')->getBsebyId($identifiantBse);
                    if ($sdoc) {
                        $signature = $sdoc[0]['signature'];

                        $record = $sdoc[0]['record'];
                        $dataSignedDoc = $this->container->get('cert_sign.server')->getDocument($signature, $record);
                        if ($dataSignedDoc) {
                            //return $dataSignedDoc;
                            $response = new Response($dataSignedDoc);
                            $response->headers->set('Content-type', 'application/octect-stream');
                            $response->setCharset('utf8');
                            return $response;
                        } else {
                            //return new Response("Temps d'attente écoulé", 504);
                             $JsonResponse = new JsonResponse(array("Temps d'attente écoulé" => "504"));
               		     return $JsonResponse;
                        }
                    } else {

                        //return new Response("Pas de Documents pour l'utilisateur ou les critéres", 404);
			$JsonResponse = new JsonResponse(array("Pas de Documents pour l'utilisateur ou les critéres" => "404"));
               		return $JsonResponse;
                    }
                } else {
                        //return new Response("Pas de Documents pour l'utilisateur ou les critéres", 404);
			    $JsonResponse = new JsonResponse(array("Pas de Documents pour l'utilisateur ou les critéres" => "404"));
               		     return $JsonResponse;
                }
            }
        }// end if $ObjectAccessToken





	if ($ObjectRefreshToken) {
            $user_id_refresh = $ObjectRefreshToken[0]['user_id'];
            if ($user_id_refresh != NULL) {
                $user = $em->getRepository('UserBundle:User')->findUserById($user_id_refresh);
                if ($user) {
                    $sdoc = $em->getRepository('UserBundle:SignedDoc')->getBsebyId($identifiantBse);
                    if ($sdoc) {
                        $signature = $sdoc[0]['signature'];
                        $record = $sdoc[0]['record'];
                        $dataSignedDoc = $this->container->get('cert_sign.server')->getDocument($signature, $record);
                        if ($dataSignedDoc) {
                            //return $dataSignedDoc;
                            $response = new Response($dataSignedDoc);
                            $response->headers->set('Content-type', 'application/octect-stream');
                            $response->setCharset('utf8');
                            return $response;
                        } else {
                            $JsonResponse = new JsonResponse(array("Temps d'attente écoulé" => "504"));
                            return $JsonResponse;
                        }
                    } else {

                        $JsonResponse = new JsonResponse(array("Pas de Documents pour l'utilisateur ou les critéres" => "404"));
                        return $JsonResponse;
                    }
                } else {
                    $JsonResponse = new JsonResponse(array("Pas de Documents pour l'utilisateur ou les critéres" => "404"));
                    return $JsonResponse;
                }
            }
        }// end if $ObjectRefreshToken



        
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
