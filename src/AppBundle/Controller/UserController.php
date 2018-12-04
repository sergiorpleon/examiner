<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\Funciones\Funciones_Completa;
use AppBundle\Entity\Carrera;
use AppBundle\Entity\Estudia;
use AppBundle\Entity\Inciso_Simple_Selection;
use AppBundle\Entity\Institucion;
use AppBundle\Entity\Item_List_Selection;
use AppBundle\Entity\Recursos;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\Test;
use Symfony\Bundle\FrameworkBundle\Tests\Functional\app\AppKernel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Test controller.
 *
 * @Route("/admin")
 */
class UserController extends Controller
{

    /******************************************************************************************************/
    /************************************** Config table ********************************************/
    /******************************************************************************************************/


    /**
     * Lists all Results entities.
     *
     * @Route("/config", name="config")
     * @Method("GET")
     */
    public function configindexAction()
    {
        return $this->render('test/config/index.html.twig');
    }


    /**
     * Lists all Test entities.
     *
     * @Route("/config/json/", name="config_json")
     * @Method("GET")
     */
    public function configjsonAction(Request $r)
    {
        //listar json de una pregunta
        $em = $this->getDoctrine()->getManager();
        $instituciones = $em->getRepository('AppBundle:Institucion')->findAll();
        $carreras = $em->getRepository('AppBundle:Carrera')->findAll();
        //$estudias = $em->getRepository('AppBundle:Estudia')->findAll();
        try {
            $arrayC = array();
            $i = 0;
            foreach ($instituciones as $ins) {
                $arrayC['instituciones'][$i]['id'] = $ins->getId();
                $arrayC['instituciones'][$i]['nombre'] = $ins->getNombre();
                $arrayC['instituciones'][$i]['edit'] = false;

                $i++;
            };
            $i = 0;
            foreach ($carreras as $car) {
                $arrayC['carreras'][$i]['id'] = $car->getId();
                $arrayC['carreras'][$i]['nombre'] = $car->getNombre();
                $arrayC['carreras'][$i]['edit'] = false;

                $i++;
            };

            $i = 0;
            foreach ($instituciones as $inst) {

                $estudias = $em->getRepository('AppBundle:Estudia')->findBy(array('id_institucion'=>$inst->getId()));
                if($estudias){
                    $j=0;
                    foreach ($carreras as $cart) {
                        $arrayC['estudias'][$i]['carreras'][$j]['id'] = -1;
                        $arrayC['estudias'][$i]['id_institucion'] = $inst->getId();
                        $arrayC['estudias'][$i]['nombre_institucion'] = $inst->getNombre();

                        $arrayC['estudias'][$i]['carreras'][$j]['id_carrera'] = $cart->getId();
                        $arrayC['estudias'][$i]['carreras'][$j]['nombre_carrera'] = $cart->getNombre();

                        $arrayC['estudias'][$i]['carreras'][$j]['select'] = false;
                        foreach ($estudias as $est) {

                            if($cart->getNombre() === $est->getIdCarrera()->getNombre()){
                                $arrayC['estudias'][$i]['carreras'][$j]['id'] = $est->getId();
                                $arrayC['estudias'][$i]['carreras'][$j]['select'] = true;
                            }
                        }
                        $j++;
                    }
                }else{

                    $arrayC['estudias'][$i]['id_institucion'] = $inst->getId();
                    $arrayC['estudias'][$i]['nombre_institucion'] = $inst->getNombre();

                    $j =0;
                    foreach ($carreras as $estt) {
                        $arrayC['estudias'][$i]['carreras'][$j]['id'] = -1;
                        $arrayC['estudias'][$i]['carreras'][$j]['id_carrera'] = $estt->getId();
                        $arrayC['estudias'][$i]['carreras'][$j]['nombre_carrera'] = $estt->getNombre();

                        $arrayC['estudias'][$i]['carreras'][$j]['select'] = false;
                        $j++;
                    }

                }
                $i++;
            };


            $response = new Response();
            $response->setContent(json_encode($arrayC));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        } catch (Exception $e) {
            $error = '{"error":{"text":' . $e->getMessage() . '}}';
            $response = new Response();
            $response->setContent(json_encode($error));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }


    /**
     * Creates a new carrera en BD.
     *
     * @Route("/save_carrera_institucion/json", name="save_carrera_institucion")
     * @Method({"GET", "POST"})
     */
    public function saveCarreraInstitucionAction(Request $request)
    {
        //-------------------------------------------------------------------------------------
        //------------------------guardar json de new prueba------------------------------------
        //-------------------------------------------------------------------------------------

        $em = $this->getDoctrine()->getManager();

        $x = json_decode($request->getContent());
        $instituciones = $x->config->instituciones;
        $carreras = $x->config->carreras;

        $i = 0;
        foreach ($instituciones as $ins) {
            $iid = $ins->id;
            if($iid != -1){
                $institucion = $em->getRepository('AppBundle:Institucion')->findOneBy(array('id'=>$iid));
            }else{
                $institucion = new Institucion();
            }
            $institucion->setNombre($ins->nombre);
            $i++;
            $em->persist($institucion);
            $em->flush();
        };


        $i = 0;
        foreach ($carreras as $car) {
            $cid = $car->id;
            if($cid != -1){
                $carrera = $em->getRepository('AppBundle:Carrera')->findOneBy(array('id'=>$cid));
            }else{
                $carrera = new Carrera();
            }
            $carrera->setNombre($car->nombre);
            $i++;
            $em->persist($carrera);
            $em->flush();
        };


        $idsc = $x->delCarrera;

        foreach ($idsc as $idc) {
            if($idc->id!=-1){
                $c = $em->getRepository('AppBundle:Carrera')->find($idc->id);
                if ($c != null) {
                    $em->remove($c);
                    $em->flush();
                }
            }

        }

        $idsi = $x->delInstitucion;

        foreach ($idsi as $idi) {
            if($idi->id!=-1) {
                $i = $em->getRepository('AppBundle:Institucion')->find($idi->id);
                if ($i != null) {
                    $em->remove($i);
                    $em->flush();
                }
            }

        }

        //redirigir ojoooo
        //$error = '{"error":{"text":'. $e->getMessage() .'}}';
        $id = '-1';
        $response = new Response();
        $response->setContent(json_encode($id));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * Creates a new carrera en BD.
     *
     * @Route("/save_estudia/json", name="save_estudia")
     * @Method({"GET", "POST"})
     */
    public function saveEstudiaAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $x = json_decode($request->getContent());
        //$instituciones = $x->config->instituciones;
        //$carreras = $x->config->carreras;
        $estudias = $x->config->estudias;

        $i = 0;
        foreach ($estudias as $estt) {
            $estun = $estt->carreras;
            foreach ($estun as $est) {

                if($est->select){

                    $eid = $est->id;
                    if($eid != -1){
                        $estudia = $em->getRepository('AppBundle:Estudia')->findOneBy(array('id'=>$eid));
                    }else{
                        $estudia = new Estudia();
                    }
                    $relinst = $em->getRepository('AppBundle:Institucion')->findOneBy(array('id'=>$estt->id_institucion));
                    $estudia->setIdInstitucion($relinst);
                    $relcarr = $em->getRepository('AppBundle:Carrera')->findOneBy(array('id'=>$est->id_carrera));
                    $estudia->setIdCarrera($relcarr);
                    $em->persist($estudia);
                    $em->flush();
                }else{
                    $eid = $est->id;
                    if($eid != -1){


                        $e = $em->getRepository('AppBundle:Estudia')->find($eid);
                        if ($e != null) {
                            $em->remove($e);
                            $em->flush();
                        }
                    }
                }
            };
            $i++;
        };


        /*$idse = $x->delEstudia;

        foreach ($idse as $ide) {
            if($ide->id!=-1) {
                $e = $em->getRepository('AppBundle:Estudia')->find($ide->id);
                if ($e != null) {
                    $em->remove($e);
                    $em->flush();
                }
            }

        }*/
        //redirigir ojoooo
        //$error = '{"error":{"text":'. $e->getMessage() .'}}';
        $id = '-1';
        $response = new Response();
        $response->setContent(json_encode($id));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }


    /*************************************************************************************************************
     * *************************************LIST USER*********************************************************
     ************************************************************************************************************* */
    /**
     * Lists all EVALS FOR USER PROFILE
     *
     * @Route("/eval_list/json/", name="eval_list_json")
     * @Method({"GET", "POST"})
     */
    public function jsonevallistAction(Request $request)
    {
        //listar json de una pregunta
        $em = $this->getDoctrine()->getManager();
        $x = json_decode($request->getContent());
        $usuario = $em->getRepository('AppBundle:User')->find($x->id);

        try {
            $rawdatausuario = array();

            $em = $this->getDoctrine()->getManager();
            $evaluaciones = $em->getRepository('AppBundle:Evaluaciones')->findBy(array('id_estudiante' => $usuario->getId()));

            $arrayE = array();
            $i = 0;
            foreach ($evaluaciones as $e) {
                $arrayE[$i]['id'] = $e->getId();
                $arrayE[$i]['deprueba'] = $e->getIdTest()->getDeprueba();
                $arrayE[$i]['prueba'] = $e->getIdTest()->getId();

                $sfr = '-';
                if ($e->getIdTest()->getIdReading()) {
                    $fr = $e->getIdTest()->getIdReading()->getFecha();
                    if ($fr) {
                        $sfr = $fr->format("d-m-Y");
                    }
                }
                $arrayE[$i]['fecha_reading'] = $sfr;
                $arrayE[$i]['puntos_reading'] = $e->getPuntosReading();

                $sfl = '-';
                if ($fl = $e->getIdTest()->getIdListening()) {
                    $fl = $e->getIdTest()->getIdListening()->getFecha();
                    if ($fl) {
                        $sfl = $fl->format("d-m-Y");
                    }
                }
                $arrayE[$i]['fecha_listening'] = $sfl;
                $arrayE[$i]['puntos_listening'] = $e->getPuntosListening();

                $i++;
            };

            $rawdatausuario['evaluaciones'] = $arrayE;


            $response = new Response();
            $response->setContent(json_encode($rawdatausuario));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        } catch (Exception $e) {
            $error = '{"error":{"text":' . $e->getMessage() . '}}';
            $response = new Response();
            $response->setContent(json_encode($error));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }

    /**
     * Lists all User entities.
     *
     * @Route("/user/list", name="userlist")
     * @Method("GET")
     */
    public function userlistAction()
    {

        return $this->render('test/user/list.html.twig');
    }

    /**
     * Lists all Test entities.
     *
     * @Route("/user/listjson/", name="user_list_json")
     * @Method("GET")
     */
    public function jsonuserlistAction(Request $r)
    {
        //listar json de u//na pregunta
        $em = $this->getDoctrine()->getManager();
        $usuarios = $em->getRepository('AppBundle:User')->findAll();
        try {
            $arrayU = array();
            $i = 0;
            foreach ($usuarios as $u) {
                $arrayU[$i]['selected'] = false;
                $arrayU[$i]['id'] = $u->getId();
                $arrayU[$i]['username'] = $u->getUsername();
                $arrayU[$i]['enabled'] = $u->isEnabled();
                $arrayU[$i]['email'] = $u->getEmail();
                $arrayU[$i]['nombre'] = $u->getNombre();
                $arrayU[$i]['expired'] = $u->isExpired();
                $arrayU[$i]['locked'] = $u->isLocked();
                $arrayU[$i]['roles'] = $u->getRoles();

                $i++;
            };
            $response = new Response();
            $response->setContent(json_encode($arrayU));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        } catch (Exception $e) {
            $error = '{"error":{"text":' . $e->getMessage() . '}}';
            $response = new Response();
            $response->setContent(json_encode($error));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }

    /**
     * Edit a User
     *
     * @Route("/edituser/json", name="edit_user")
     * @Method({"GET", "POST"})
     */
    public function editUserAction(Request $request)
    {
        //-------------------------------------------------------------------------------------
        //--------------------eliminar json de usuario------------------------------------
        //-------------------------------------------------------------------------------------

        $em = $this->getDoctrine()->getManager();

        $x = json_decode($request->getContent());
        $id = $x->id;

        $u = $em->getRepository('AppBundle:User')->find($id);

        try {
            $arrayU = array();
            $i = 0;

            $arrayU['id'] = $u->getId();
            $arrayU['username'] = $u->getUsername();
            $arrayU['enabled'] = $u->isEnabled();
            $arrayU['email'] = $u->getEmail();
            $arrayU['nombre'] = $u->getNombre();
            $arrayU['expired'] = $u->isExpired();
            $arrayU['locked'] = $u->isLocked();

            $roleHierarchy = $this->getParameter('security.role_hierarchy.roles');
            // sintaxis dentro de un controlador:
            // $roleHierarchy = $this->container->getParameter('security.role_hierarchy.roles');
            $roles = array_keys($roleHierarchy);
            $theRoles = array();

            $i = 0;
            foreach ($roles as $role) {
                $theRoles[$role] = $u->hasRole($role);
                $i++;
            }
            $arrayU['roles'] = $theRoles;

            $i = 0;
            foreach ($roles as $role) {
                $theRoles[$role] = $role;
                $i++;
            }
            $arrayU['arrayroles'] = $theRoles;

            $response = new Response();
            $response->setContent(json_encode($arrayU));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        } catch (Exception $e) {
            $error = '{"error":{"text":' . $e->getMessage() . '}}';
            $response = new Response();
            $response->setContent(json_encode($error));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }

    /**
     * Edit a User
     *
     * @Route("/updateuser/json", name="update_user")
     * @Method({"GET", "POST"})
     */
    public function updateUserAction(Request $request)
    {
        //-------------------------------------------------------------------------------------
        //--------------------eliminar json de usuario------------------------------------
        //-------------------------------------------------------------------------------------

        $em = $this->getDoctrine()->getManager();

        $x = json_decode($request->getContent());
        $id = $x->id;

        $u = $em->getRepository('AppBundle:User')->find($id);

        $u->setNombre($x->nombre);
        $u->setUsername($x->username);
        $u->setEmail($x->email);
        $u->setEnabled($x->enabled);
        $u->setLocked($x->locked);
        $u->setExpired($x->expired);

        $roleHierarchy = $this->getParameter('security.role_hierarchy.roles');
        // sintaxis dentro de un controlador:
        // $roleHierarchy = $this->container->getParameter('security.role_hierarchy.roles');
        $roles = array_keys($roleHierarchy);
        $theRoles = array();
        $i = 0;
        foreach ($roles as $role) {
            $theRoles[$i] = $role;
            $i++;
        }


        $i = 0;
        foreach ($x->roles as $rol) {
            if($rol == true){
                $u->addRole($theRoles[$i]);
            }
            $i++;
        }

        $em->persist($u);
        $em->flush();


        //redirigir ojoooo
        //$error = '{"error":{"text":'. $e->getMessage() .'}}';
        $error = '{ "update" : '.$x->nombre.'}';
        $response = new Response();
        $response->setContent(json_encode($error));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }




    /**
     * Delete a User
     *
     * @Route("/deleteuser/json", name="delete_user")
     * @Method({"GET", "POST"})
     */
    public function deleteUserAction(Request $request)
    {
        //-------------------------------------------------------------------------------------
        //--------------------eliminar json de usuario------------------------------------
        //-------------------------------------------------------------------------------------

        $em = $this->getDoctrine()->getManager();

        $x = json_decode($request->getContent());
        $id = $x->id;

        $u = $em->getRepository('AppBundle:User')->find($id);
        $r = $u->getIdReading();

        if ($u != null) {
            $em->remove($u);
            $em->flush();
        }
        //redirigir ojoooo
        //$error = '{"error":{"text":'. $e->getMessage() .'}}';
        $error = '{ "elimiado" : "true"}';
        $response = new Response();
        $response->setContent(json_encode($error));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * Creates a new question_List_Selection entity.
     *
     * @Route("/delete_selected_user/json", name="delete_selected_user")
     * @Method({"GET", "POST"})
     */
    public function deleteselecteduserAction(Request $request)
    {
        //-------------------------------------------------------------------------------------
        //--------------------eliminar json de prueba------------------------------------
        //-------------------------------------------------------------------------------------

        $em = $this->getDoctrine()->getManager();

        $x = json_decode($request->getContent());
        $selected = $x->selected;

        foreach ($selected as $s) {
            $t = $em->getRepository('AppBundle:User')->find($s->id);


            if ($t != null) {
                $em->remove($t);
                $em->flush();
            }
        }
        //redirigir ojoooo
        //$error = '{"error":{"text":'. $e->getMessage() .'}}';
        $error = '{ "elimiado" : "true"}';
        $response = new Response();
        $response->setContent(json_encode($error));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
