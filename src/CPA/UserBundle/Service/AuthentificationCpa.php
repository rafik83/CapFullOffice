<?php

namespace CPA\UserBundle\Service;

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
use Symfony\Component\Validator\Constraints\DateTime;

//use CPA\DoctrineExtensions\DBAL\Types\UTCDateTimeType;

class AuthentificationCpa {

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

    public function AuthentificationCpaUser2($username, $plainpassword) {

//        dump($username);
//        dump($plainpassword);
        die('AuthentificationCpaUser');
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
        $entity = $db->getRepository('UserBundle:Correspendance')->byUserName($username);
        if (!$entity) {
            $boolean_return = false;
        }
        if (!$user) {
            $boolean_return = false;
        }
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
                            $boolean_return = false;
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
                                $boolean_return = true;
//                                die('update user');
                            }
                        }

//                        dump($bool);
//                        die('bool');

                        if ($bool) {
//                            die('enter bool Salary');
//                            dump($id3);
//                            die('insert Salary');
                            if ($nom_bdd != "" && $id3 != "") {
                                $sql2 = "SELECT s.id as id, s.user_id as user_id,s.nom as nom_salary, s.prenom as prenom_salary, s.matricule as matricule,s.num_secu as num_secu, s.is_paper, c.nom as nom_company  FROM";
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
//                                dump($sql2);
//                                die('sql2');
                                $stmt2 = $Alldb->getConnection()->prepare($sql2);
                                $stmt2->execute();
                                $resultSalary = array();
                                $resultSalary = $stmt2->fetchAll();
//                                dump($resultSalary);
//                                die('$resultSalary');
//and s.is_paper = 0
                            }
                            if (count($resultSalary) > 0) {
                                foreach ($resultSalary as $key => $value) {

                                    if ($user) {
                                        $existeSalary = $db->getRepository('UserBundle:Salary')->findOneBy(array('user' => $user));
                                        if (!$existeSalary) {
                                            $salary_id_from_base = $value['id'];
                                            $user_id = $value['user_id'];
                                            $nom_salary = $value['nom_salary'];
                                            $prenom_salary = $value['prenom_salary'];
                                            $matricule = $value['matricule'];
                                            $num_secu = $value['num_secu'];
                                            $is_paper = $value['is_paper'];
                                            $nom_company = $value['nom_company'];
                                            $employeur = 'PRISCILLIA' . ' ' . 'LIAUSSON';
                                            $s = new Salary();
                                            $s->setSalaryFromBase($salary_id_from_base);
                                            $s->setNom($nom_salary);
                                            $s->setPrenom($prenom_salary);
                                            $s->setCompany($nom_company);
                                            $s->setMatricule($matricule);
                                            $s->setNumSecu($num_secu);
                                            $s->setEmployeur($employeur);
                                            $s->setUser($user);
                                            $db->persist($s);
//                                            dump($value);
//                                            die('value');
                                        }
                                    }
                                }
                                $db->flush();
                                $boolean_return = true;
//                                die('insertion salary');
                            }
                        }
//insertion DocSigne
//                         dump($bool);
//                        die('bool');

                        if ($bool) {
//                            die('enter insertion Doc Signe');

                            if ($user) {
//                                  die('enter user');
                                $Salarys = $db->getRepository('UserBundle:Salary')->findOneBy(array('user' => $user));
//                                dump($Salarys);
//                                 die('$Salarys');
                                if ($Salarys) {
                                    $DocSigne = $db->getRepository('UserBundle:SignedDoc')->findOneBy(array('salary' => $Salarys));
//                                    dump($DocSigne);
//                                    die('$Salarys');
                                    if (count($Salarys) == 1) {
                                        if (!$DocSigne) {
                                            $IdSalary = $Salarys->getSalaryFromBase();
//                                            dump($IdSalary);
//                                            die('$Salarys');
                                            if ($nom_bdd != "" && $IdSalary != NULL) {
                                                $sql3 = "SELECT id, salary_id,ext,month,year,obsolete,created_at  FROM";
                                                $sql3 .= " ";
                                                $sql3 .= $nom_bdd;
                                                $sql3 .= ".signed_doc";
                                                $sql3 .= " ";
                                                $sql3 .= " where salary_id ='" . $IdSalary . "'";
                                                $sql3 .= " ";
//                                                dump($sql3);
//                                                die('sql3');
                                                $stmt3 = $Alldb->getConnection()->prepare($sql3);
                                                $stmt3->execute();
                                                $resultSignedDoc = array();
                                                $resultSignedDoc = $stmt3->fetchAll();
//                                                dump($resultSignedDoc);
//                                                die('$resultSignedDoc');

                                                if (count($resultSignedDoc) > 1) {
                                                    foreach ($resultSignedDoc as $key => $value) {

//                                                        dump($value);
//                                                        die('doc');

                                                        $idDoc_from_base = $value['id'];
                                                        $salary_id_from_base = $value['salary_id'];
                                                        $ext = $value['ext'];
                                                        $month = $value['month'];
                                                        $year = $value['year'];
                                                        $obsolete = $value['obsolete']; //
                                                        $createdAt = $value['created_at'];

//                                                        $date_reception = new \Datetime();
//                                                        $createdAt = new \Datetime();

                                                        $d = new SignedDoc();
                                                        $d->setDocSigneFromBase($idDoc_from_base); //
                                                        $d->setIdSalaryFromBase($salary_id_from_base);
                                                        $d->setExt($ext);
                                                        $d->setMonth($month);
                                                        $d->setYear($year);
                                                        $d->setObsolete($obsolete);
                                                        $d->setSalary($Salarys);
                                                        $d->setCreatedAt($createdAt);
//                                                        $d->setDateReception($date_reception);
                                                        $db->persist($d);
                                                    }
                                                    $db->flush();
//                                                    $boolean_return = true;
//                                                    die('insert doc signe');
                                                }
//                                                   
                                            }
                                        }
                                    }
                                }
                            }
                        }
//                         die('non enter bool');
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
                        $boolean_return = false;
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
                            $boolean_return = true;
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
                            } else {
                                $boolean_return = false;
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
                                    $boolean_return = true;
//                                    die('insertion user');
// Insertion Salary
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
                        } else {
                            $boolean_return = false;
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
                                $boolean_return = true;
                                die('success + update + else');
                                break;
                            }
                        }
                    }
                }

                if (count($entity) == 0) {
                    $boolean_return = false;
                }
            }
        }// end else
//        die('fin if + else');



        $final_user = $db->getRepository('UserBundle:User')->findOneBy(['username' => $username]);
//        dump($final_user);
//        die('$final_user');
        $AllSalary = $db->getRepository('UserBundle:Salary')->findAll();
//        dump($AllSalary);
//        die('$AllSalary');

        $AllSignedDoc = $db->getRepository('UserBundle:SignedDoc')->findAll();
//        dump($AllSignedDoc);
//        die('$AllSignedDoc');

        if (!$final_user) {
            $boolean_return = false;
        }
        if (!$AllSalary) {
            $boolean_return = false;
        }
        if (!$AllSignedDoc) {
            $boolean_return = false;
        }



//        dump($boolean_return);
//        die('fin + $boolean_return');
//          return $this->redirect($this->generateUrl('user_homepage'));
//        return $boolean_return;
    }

    public function AuthentificationCpaUser($username) {

//        dump($username);
//   
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
        $entity = $db->getRepository('UserBundle:Correspendance')->byUserName($username);
//        if (!$entity) {
//            $boolean_return = false;
//        }
//        if (!$user) {
//            $boolean_return = false;
//        }
//         dump($user);
//         dump($entity);
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
//                            die('enter bool Salary');
//                            dump($id3);
//                            die('insert Salary');
            if ($nom_bdd != "" && $user != NULL) {
                $sql2 = "SELECT s.id as id, s.user_id as user_id,s.nom as nom_salary, s.prenom as prenom_salary, s.matricule as matricule,s.num_secu as num_secu, s.is_paper as is_paper, c.nom as nom_company, u.username as username  FROM";
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

                $sql2 .= "left  join";
                $sql2 .= " ";
                $sql2 .= $nom_bdd;
                $sql2 .= ".user";
                $sql2 .= " ";
                $sql2 .= "u";
                $sql2 .= " ";
                $sql2 .= "on";
                $sql2 .= " ";
                $sql2 .= "s.user_id = u.id"; //u.username
                $sql2 .= " ";
                $sql2 .= " where u.username ='" . $username . "'";
                $sql2 .= " ";
//                $sql2 .= "and";
//                $sql2 .= " ";
//                $sql2 .= "s.is_paper = 0";
//                $sql2 .= " ";
//                dump($sql2);
//                die('sql2');
                $stmt2 = $Alldb->getConnection()->prepare($sql2);
                $stmt2->execute();
                $resultSalary = array();
                $resultSalary = $stmt2->fetchAll();
//                dump($resultSalary);
//                die('$resultSalary');
                //and s.is_paper = 0
            }
            if (count($resultSalary) > 0) {
                foreach ($resultSalary as $key => $value) {

                    if ($user) {
                        $existeSalary = $db->getRepository('UserBundle:Salary')->findOneBy(array('user' => $user));
                        if (!$existeSalary) {
                            $salary_id_from_base = $value['id'];
                            $user_id = $value['user_id'];
                            $nom_salary = $value['nom_salary'];
                            $prenom_salary = $value['prenom_salary'];
                            $matricule = $value['matricule'];
                            $num_secu = $value['num_secu'];
                            $is_paper = $value['is_paper'];
                            $nom_company = $value['nom_company'];
//                            $employeur = 'PRISCILLIA' . ' ' . 'LIAUSSON';
                            $s = new Salary();
                            $s->setSalaryFromBase($salary_id_from_base);
                            $s->setNom($nom_salary);
                            $s->setPrenom($prenom_salary);
                            $s->setCompany($nom_company);
                            $s->setMatricule($matricule);
                            $s->setNumSecu($num_secu);
//                            $s->setEmployeur($employeur);
                            $s->setEmployeur($nom_company);
                            $s->setUser($user);
                            $db->persist($s);
//                                            dump($value);
//                                            die('value');
                        }
                    }
                }
                $db->flush();
//                                $boolean_return = true;
//                die('insertion salary');
            }

//                            die('enter insertion Doc Signe');

            if ($user) {
//                                  die('enter user');
                $Salarys = $db->getRepository('UserBundle:Salary')->findOneBy(array('user' => $user));
                if ($Salarys) {
                    $DocSigne = $db->getRepository('UserBundle:SignedDoc')->findOneBy(array('salary' => $Salarys));
//                                    dump($DocSigne);
//                                    die('$DocSigne');
                    if (count($Salarys) == 1) {
//                                        dump(count($Salarys));
//                                        dump($Salarys);
//                                        die('count salary 1');
                        if (!$DocSigne) {
                            $IdSalary = $Salarys->getSalaryFromBase();
//                                            dump($IdSalary);
//                                            die('$Salarys');
                            if ($nom_bdd != "" && $IdSalary != NULL) {
                                $sql3 = "SELECT id,salary_id,record,signature,doc,ext,month,year,size,obsolete, created_at   FROM";
                                $sql3 .= " ";
                                $sql3 .= $nom_bdd;
                                $sql3 .= ".signed_doc";
                                $sql3 .= " ";
                                $sql3 .= " where salary_id ='" . $IdSalary . "'";
                                $sql3 .= " ";
                                $sql3 .= "AND";
                                $sql3 .= " ";
                                $sql3 .= "obsolete = 0";
//                                dump($sql3);
//                                die('sql3');
                                $stmt3 = $Alldb->getConnection()->prepare($sql3);
                                $stmt3->execute();
                                $resultSignedDoc = array();
                                $resultSignedDoc = $stmt3->fetchAll();
//                                dump($resultSignedDoc);
//                                die('$resultSignedDoc');

                                if (count($resultSignedDoc) > 1) {
                                    foreach ($resultSignedDoc as $key => $value) {
                                        $idDoc_from_base = $value['id'];
                                        $salary_id_from_base = $value['salary_id'];
                                        $record = $value['record'];
                                        $signature = $value['signature'];
                                        $doc = $value['doc'];
                                        $size = $value['size'];
                                        $ext = $value['ext'];
                                        $month = $value['month'];
//                                                        var_dump($month);
//                                                        die('$month');
                                        $year = $value['year'];
                                        $obsolete = $value['obsolete']; //
                                        $createdAt = $value['created_at'];
//                                                         var_dump($createdAt);
//                                                        $date_debut = $year . '-' . '0' . $month . '-' . '01' . ' ' . '00' . ':' . '00' . ':' . '00';
//                                                        $date_fin = $year . '-' . '0' . $month . '-' . '31' . ' ' . '00' . ':' . '00' . ':' . '00';
//                                                         dump($date_debut);
//                                                        dump($date_fin);
//                                                        die('$createdAt');
//                                                        $tt = $month . '/' . $year;
//                                                        $formaDate = 'd/m/Y';
//                                                        $DateUtc = \DateTime::createFromFormat($formaDate, $tt);
//                                                        $DateUtc->setTimezone(new \DateTimeZone('UTC'));
//                                                        $DateUtc->format('Y-m-d H:i:s');
//                                                        $createdAt = new \Datetime();
//                                                        $tt = $month . '/' . $year;
//                                                        $formaDate = 'd/Y';
//                                                        $DateUtc = \DateTime::createFromFormat($formaDate, $tt);
//                                                        $DateUtc->setTimezone(new \DateTimeZone('UTC'));
//                                                        $date_reception = new \DateTime();
//                                                         dump($date_reception);
//                                                         die('ici0');
//                                                        dump($tt);
//                                                        dump($createdAt);
//                                                        dump($month);
//                                                        die('ici');
//                                                        $createdAt = new \Datetime();
//                                                        $date_reception = new \DateTime();
                                        //'2017-08-21 16:44:53'
                                        // 2017-01-01 00:0000
                                        $date_debut = '';
                                        $date_fin = '';
                                        switch ($month) {
                                            case '1':
                                                $date_debut = $year . '-' . '0' . $month . '-' . '01' . ' ' . '00' . ':' . '00' . ':' . '00';
                                                $date_fin = $year . '-' . '0' . $month . '-' . '31' . ' ' . '00' . ':' . '00' . ':' . '00';
                                                break;
                                            case '2':
                                                $date_debut = $year . '-' . '0' . $month . '-' . '01' . ' ' . '00' . ':' . '00' . ':' . '00';
                                                $date_fin = $year . '-' . '0' . $month . '-' . '28' . ' ' . '00' . ':' . '00' . ':' . '00';
                                                break;
                                            case '3':
                                                $date_debut = $year . '-' . '0' . $month . '-' . '01' . ' ' . '00' . ':' . '00' . ':' . '00';
                                                $date_fin = $year . '-' . '0' . $month . '-' . '31' . ' ' . '00' . ':' . '00' . ':' . '00';
                                                break;
                                            case '4':
                                                $date_debut = $year . '-' . '0' . $month . '-' . '01' . ' ' . '00' . ':' . '00' . ':' . '00';
                                                $date_fin = $year . '-' . '0' . $month . '-' . '30' . ' ' . '00' . ':' . '00' . ':' . '00';
                                                break;
                                            case '5':
                                                $date_debut = $year . '-' . '0' . $month . '-' . '01' . ' ' . '00' . ':' . '00' . ':' . '00';
                                                $date_fin = $year . '-' . '0' . $month . '-' . '31' . ' ' . '00' . ':' . '00' . ':' . '00';
                                                break;
                                            case '6':
                                                $date_debut = $year . '-' . '0' . $month . '-' . '01' . ' ' . '00' . ':' . '00' . ':' . '00';
                                                $date_fin = $year . '-' . '0' . $month . '-' . '30' . ' ' . '00' . ':' . '00' . ':' . '00';
                                                break;
                                            case '7':
                                                $date_debut = $year . '-' . '0' . $month . '-' . '01' . ' ' . '00' . ':' . '00' . ':' . '00';
                                                $date_fin = $year . '-' . '0' . $month . '-' . '31' . ' ' . '00' . ':' . '00' . ':' . '00';
                                                break;
                                            case '8':
                                                $date_debut = $year . '-' . '0' . $month . '-' . '01' . ' ' . '00' . ':' . '00' . ':' . '00';
                                                $date_fin = $year . '-' . '0' . $month . '-' . '31' . ' ' . '00' . ':' . '00' . ':' . '00';
                                                break;
                                            case '9':
                                                $date_debut = $year . '-' . '0' . $month . '-' . '01' . ' ' . '00' . ':' . '00' . ':' . '00';
                                                $date_fin = $year . '-' . '0' . $month . '-' . '30' . ' ' . '00' . ':' . '00' . ':' . '00';
                                                break;
                                            case '10':
                                                $date_debut = $year . '-' . $month . '-' . '01' . ' ' . '00' . ':' . '00' . ':' . '00';
                                                $date_fin = $year . '-' . $month . '-' . '31' . ' ' . '00' . ':' . '00' . ':' . '00';
                                                break;
                                            case '11':
                                                $date_debut = $year . '-' . $month . '-' . '01' . ' ' . '00' . ':' . '00' . ':' . '00';
                                                $date_fin = $year . '-' . $month . '-' . '30' . ' ' . '00' . ':' . '00' . ':' . '00';
                                                break;
                                            case '12':
                                                $date_debut = $year . '-' . $month . '-' . '01' . ' ' . '00' . ':' . '00' . ':' . '00';
                                                $date_fin = $year . '-' . $month . '-' . '31' . ' ' . '00' . ':' . '00' . ':' . '00';
                                                break;
                                        }


//                                                        die('$createdAt');

                                        $d = new SignedDoc();
                                        $d->setDocSigneFromBase($idDoc_from_base); //
                                        $d->setIdSalaryFromBase($salary_id_from_base);
                                        $d->setRecord($record);
                                        $d->setSignature($signature);
                                        $d->setDoc($doc);
                                        $d->setSize($size);
                                        $d->setExt($ext);
                                        $d->setMonth($month);
                                        $d->setYear($year);
                                        $d->setObsolete($obsolete);
                                        $d->setSalary($Salarys);
                                        $d->setCreatedAt(new \DateTime($createdAt));
                                        $d->setDateDebut(new \DateTime($date_debut));
                                        $d->setDateFin(new \DateTime($date_fin));
                                        $db->persist($d);
                                    }

                                    $db->flush();
//                                                    die('ici1 + flush d');
//                                                    die('insert doc signe');
//                                                    $boolean_return = true;
                                }
//                                                   
                            }
                        }
                    }
                }
            }

//                         die('non enter bool');
        } // end if
//       
    }

}
