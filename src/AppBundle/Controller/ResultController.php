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
class ResultController extends Controller
{

    //------------------------------------------------------------------------

    /******************************************************************************************************/
    /************************************** RESULTADOS ********************************************/
    /******************************************************************************************************/


    /**
     * Lists all Results entities.
     *
     * @Route("/list_results", name="list_results")
     * @Method("GET")
     */
    public function listresultadosindexAction()
    {

        return $this->render('test/resultados/index.html.twig');
    }


    /**
     * Lists all Test entities.
     *
     * @Route("/list_results/json/", name="list_results_json")
     * @Method("GET")
     */
    public function jsonlistresultsAction(Request $r)
    {
        //listar json de u//na pregunta
        $em = $this->getDoctrine()->getManager();
        $evaluaciones = $em->getRepository('AppBundle:Evaluaciones')->findAll();
        try {
            $arrayE = array();
            $i = 0;
            foreach ($evaluaciones as $e) {
                $arrayE[$i]['id'] = $e->getId();
                $arrayE[$i]['user'] = $e->getIdEstudiante()->getNombre();
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
                if ($e->getIdTest()->getIdListening()) {
                    $fl = $e->getIdTest()->getIdListening()->getFecha();
                    if ($fl) {
                        $sfl = $fl->format("d-m-Y");
                    }
                }
                $arrayE[$i]['fecha_listening'] = $sfl;
                $arrayE[$i]['puntos_listening'] = $e->getPuntosListening();

                $i++;
            };
            $response = new Response();
            $response->setContent(json_encode($arrayE));
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
     * Delete evalaucion
     *
     * @Route("/deleteeval/json", name="delete_eval")
     * @Method({"GET", "POST"})
     */
    public function deleteevalAction(Request $request)
    {
        //-------------------------------------------------------------------------------------
        //--------------------eliminar json de prueba------------------------------------
        //-------------------------------------------------------------------------------------

        $em = $this->getDoctrine()->getManager();

        $x = json_decode($request->getContent());
        $id = $x->id;

        $t = $em->getRepository('AppBundle:Evaluaciones')->find($id);

        if ($t != null) {
            $em->remove($t);
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
     * Lists one Reading and Listening entities.
     *
     * @Route("/view_result/json", name="view_result_json")
     * @Method({"GET", "POST"})
     */
    public function viewResultAction(Request $request)
    {
        //-------------------------------------------------------------------------------------
        //------------------------listar json de una evalaucion------------------------------------
        //-------------------------------------------------------------------------------------
        $em = $this->getDoctrine()->getManager();
        $x = json_decode($request->getContent());
        $evaluacion = $em->getRepository('AppBundle:Evaluaciones')->find($x->id);
        //$evaluacion= $em->getRepository('AppBundle:Evaluaciones')->find(1);

        try {
            $rawdataevaluacion = array();

            $rawdataevaluacion['id'] = $evaluacion->getId();
            $rawdataevaluacion['user'] = $evaluacion->getIdEstudiante()->getNombre();
            $rawdataevaluacion['profesor'] = $evaluacion->getIdTest()->getIdProfesor();
            $rawdataevaluacion['user_id'] = $evaluacion->getIdEstudiante()->getId();
            $rawdataevaluacion['prueba_id'] = $evaluacion->getIdTest()->getId();
            $rawdataevaluacion['prueba'] = $evaluacion->getIdTest()->getTextoOrientacion();
            $rawdataevaluacion['puntosreading'] = $evaluacion->getPuntosReading();

            $puntRead9 = floor( ($evaluacion->getPuntosReading()/40)*16 + 2);
            $bandareading = '';
            switch($puntRead9){
                case 2:
                case 3:
                    $bandareading = 'Below A1';
                    break;
                case 4:
                case 5:
                    $bandareading = 'A1';
                    break;
                case 6:
                case 7:
                    $bandareading = 'A2';
                    break;
                case 8:
                case 9:
                    $bandareading = 'B1';
                    break;
                case 10:
                    $bandareading = 'B1+';
                    break;

                case 11:
                case 12:
                    $bandareading = 'B2';
                    break;
                case 13:
                case 14:
                case 15:
                    $bandareading = 'C1';
                    break;
                case 16:
                case 17:
                case 18:
                    $bandareading = 'C2';
                    break;
            }


            $rawdataevaluacion['levelreading'] = $bandareading;


            $rawdataevaluacion['puntoslistening'] = $evaluacion->getPuntosListening();

            $puntList9 = floor( ($evaluacion->getPuntosListening()/40)*16 + 2);
            $bandalistening = '';
            switch($puntRead9){
                case 2:
                case 3:
                    $bandalistening = 'Below A1';
                    break;
                case 4:
                case 5:
                    $bandalistening = 'A1';
                    break;
                case 6:
                case 7:
                    $bandalistening = 'A2';
                    break;
                case 8:
                case 9:
                    $bandalistening = 'B1';
                    break;
                case 10:
                    $bandalistening = 'B1+';
                    break;

                case 11:
                case 12:
                    $bandalistening = 'B2';
                    break;
                case 13:
                case 14:
                case 15:
                    $bandalistening = 'C1';
                    break;
                case 16:
                case 17:
                case 18:
                    $bandalistening = 'C2';
                    break;
            }


            $rawdataevaluacion['levellistening'] = $bandalistening;

            $r = $evaluacion->getIdTest()->getIdReading();
            if($r) {
                $item = 0;

                $seccionesReading = $r->getSeccionesReading();
                $j = 0;
                $rawdatasection = array();
                foreach ($seccionesReading as $sr) {
                    $questionSeccionReading = $sr->getQuestionsSeccionReadings();

                    $k = 0;
                    $rawdataquestion = array();
                    foreach ($questionSeccionReading as $objetoQ) {

                        $rawdata = array();
                        if ($objetoQ->getTipoPregunta() == 1) {
                            $item_True_False = $objetoQ->getItemsTrueFalse();

                            $indzTF = 0;
                            $rawdataitem = array();
                            foreach ($item_True_False as $objeto) {

                                $valrespest = $objeto->getOpcionCorrecta();
                                $textrespest = '';
                                if ($valrespest == 0) {
                                    $textrespest = 'true';
                                }
                                if ($valrespest == 1) {
                                    $textrespest = 'false';
                                }
                                if ($valrespest == 2) {
                                    $textrespest = 'not gitven';
                                }
                                $rawdataitem[$indzTF]['textoItem'] = $textrespest;

                                //$rawdataitem[$indzTF]['textoItem'] = $objeto->getTextoItem();

                                $tie = $em->getRepository('AppBundle:Respuesta_True_False')->findOneBy(array('id_estudiante' => $evaluacion->getIdEstudiante()->getId(), 'idItem' => $objeto->getId()));
                                $valrespest = $tie->getRespuesta();
                                $textrespest = '';
                                if ($valrespest == 0) {
                                    $textrespest = 'true';
                                }
                                if ($valrespest == 1) {
                                    $textrespest = 'false';
                                }
                                if ($valrespest == 2) {
                                    $textrespest = 'not gitven';
                                }
                                $rawdataitem[$indzTF]['textoItemE'] = $textrespest;
                                $rawdataitem[$indzTF]['textoItemP'] = $tie->getPuntos();

                                $rawdataitem[$indzTF]['item'] = $item;
                                $item++;
                                $indzTF++;
                            }
                            $rawdata['truefalse'] = $rawdataitem;

                        } else if ($objetoQ->getTipoPregunta() == 2) {
                            $item_Simple_Selection = $objetoQ->getItemsSimpleSelection();

                            $indzSS = 0;
                            $rawdataitem = array();
                            foreach ($item_Simple_Selection as $objeto) {
                                $inciso_Simple_Selection = $objeto->getIncisosSimpleSelection();
                                $arrayValoresSS = array();

                                $respuestaOk = $objeto->getOpcionCorrecta();
                                $valRexpuetaOk = '';

                                $indxSS = 0;
                                foreach ($inciso_Simple_Selection as $v) {
                                    //$arrayValoresSS[$v->getOrdenInciso()] = $v->getTextoOpcion();
                                    if( $respuestaOk == $v->getOrdenInciso()){
                                        $valRexpuetaOk =  $v->getTextoOpcion();
                                    }
                                    $indxSS++;
                                }
                                $rawdataitem[$indzSS]['textoItem'] = $valRexpuetaOk;

                                //$rawdataitem[$indzSS]['textoItem'] = $objeto->getTextoItem();

                                $tie = $em->getRepository('AppBundle:Respuesta_Simple_Selection')->findOneBy(array('id_estudiante' => $evaluacion->getIdEstudiante()->getId(), 'id_item_simple_selection' => $objeto->getId()));
                                $tie2 = $em->getRepository('AppBundle:Inciso_Simple_Selection')->findOneBy(array('ordenInciso' => $tie->getRespuestaEstudiante()));
                                $rawdataitem[$indzSS]['textoItemE'] = $tie2->getTextoOpcion();
                                $rawdataitem[$indzSS]['textoItemP'] = $tie->getPuntos();

                                $rawdataitem[$indzSS]['item'] = $item;
                                $item++;

                                $indzSS++;
                            }
                            $rawdata['simpleselection'] = $rawdataitem;

                        } else if ($objetoQ->getTipoPregunta() == 3) {
                            $item_Multiple_Selection = $objetoQ->getIncisosMultipleSelection();

                            $indzSM = 0;
                            $rawdataitem = array();
                            //$itemest = $item;
                            foreach ($item_Multiple_Selection as $objeto) {


                                $tie = $em->getRepository('AppBundle:Respuesta_Multiple_Selection')->findOneBy(array('id_estudiante' => $evaluacion->getIdEstudiante()->getId(), 'id_inciso_multiple_selection' => $objeto->getId()));

                                if ($tie->getRespuestaEstudiante()) {
                                    $rawdataitem[$indzSM]['textoItemE'] = $objeto->getTextoOpcion();
                                    $rawdataitem[$indzSM]['textoItemP'] = $tie->getPuntos();
                                }

                                if ($objeto->getCorrectaInciso()) {
                                    $rawdataitem[$indzSM]['textoItem'] = $objeto->getTextoOpcion();
                                    $rawdataitem[$indzSM]['item'] = $item;
                                    $item++;
                                }

                                $indzSM++;
                            }
                            $rawdata['multipleselection'] = $rawdataitem;


                        } else if ($objetoQ->getTipoPregunta() == 4) {
                            $valores_List_Selections = $objetoQ->getValoresListSelection();

                            $indvLS = 0;
                            $arrayValoresLS = array();
                            foreach ($valores_List_Selections as $v) {
                                $arrayValoresLS[$v->getTextoOpcion()] = $v->getTextoOpcion();

                                $indvLS++;
                            }

                            $item_List_Selections = $objetoQ->getItemsListSelection();

                            $indzLS = 0;
                            $rawdataitem = array();
                            foreach ($item_List_Selections as $objeto) {
                                $tie = $em->getRepository('AppBundle:Respuesta_List_Selection')->findOneBy(array('id_estudiante' => $evaluacion->getIdEstudiante()->getId(), 'id_item_list_selection' => $objeto->getId()));
                                $rawdataitem[$indzLS]['textoItemE'] = $tie->getRespuestaEstudiante();
                                $rawdataitem[$indzLS]['textoItemP'] = $tie->getPuntos();


                                $rawdataitem[$indzLS]['textoItem'] = $objeto->getOpcionCorrecta();
                                $rawdataitem[$indzLS]['item'] = $item;
                                $item++;
                                $indzLS++;
                            }
                            $rawdata['listselection'] = $rawdataitem;

                        } else if ($objetoQ->getTipoPregunta() == 5) {
                            $item_Complete = $objetoQ->getItemsComplete();

                            $indzC = 0;
                            $rawdataitem = array();
                            foreach ($item_Complete as $objeto) {

                                $tie = $em->getRepository('AppBundle:Respuesta_Completa')->findOneBy(array('id_estudiante' => $evaluacion->getIdEstudiante()->getId(), 'id_completa' => $objeto->getId()));
                                $rawdataitem[$indzC]['textoItemE'] = $tie->getRespuestaEstudiante();
                                $rawdataitem[$indzC]['textoItemP'] = $tie->getPuntos();

                                $rawdataitem[$indzC]['textoItem'] = $objeto->getRespuestaCorrecta();
                                $rawdataitem[$indzC]['item'] = $item;
                                $item++;


                                $indzC++;
                            }
                            $rawdata['complete'] = $rawdataitem;

                        }
                        $rawdataquestion['question'][$k] = $rawdata;
                        $rawdataquestion['question'][$k]['tipoPregunta'] = $objetoQ->getTipoPregunta();

                        $k++;

                    }
                    $rawdatasection['section'][$j] = $rawdataquestion;
                    //$rawdatasection['section'][$j]['sec'] = 'entro';
                    $j++;

                }
                $rawdataevaluacion['reading'] = $rawdatasection;
            }

            $l = $evaluacion->getIdTest()->getIdListening();
            if($l) {
                $item = 0;

                $seccionesListening = $r->getSeccionesListening();
                $j = 0;
                $rawdatasection = array();
                foreach ($seccionesListening as $sl) {
                    $questionSeccionListening = $sl->getQuestionsSeccionListenings();

                    $k = 0;
                    $rawdataquestion = array();
                    foreach ($questionSeccionListening as $objetoQ) {

                        $rawdata = array();
                        if ($objetoQ->getTipoPregunta() == 1) {
                            $item_True_False = $objetoQ->getItemsTrueFalse();

                            $indzTF = 0;
                            $rawdataitem = array();
                            foreach ($item_True_False as $objeto) {
                                $rawdataitem[$indzTF]['textoItem'] = $objeto->getTextoItem();

                                $tie = $em->getRepository('AppBundle:Respuesta_True_False')->findOneBy(array('id_estudiante' => $evaluacion->getIdEstudiante()->getId(), 'idItem' => $objeto->getId()));
                                $valrespest = $tie->getRespuesta();
                                $textrespest = '';
                                if ($valrespest == 0) {
                                    $textrespest = 'true';
                                }
                                if ($valrespest == 1) {
                                    $textrespest = 'false';
                                }
                                if ($valrespest == 2) {
                                    $textrespest = 'not gitven';
                                }
                                $rawdataitem[$indzTF]['textoItemE'] = $textrespest;
                                $rawdataitem[$indzTF]['textoItemP'] = $tie->getPuntos();

                                $rawdataitem[$indzTF]['item'] = $item;
                                $item++;
                                $indzTF++;
                            }
                            $rawdata['truefalse'] = $rawdataitem;

                        } else if ($objetoQ->getTipoPregunta() == 2) {
                            $item_Simple_Selection = $objetoQ->getItemsSimpleSelection();

                            $indzSS = 0;
                            $rawdataitem = array();
                            foreach ($item_Simple_Selection as $objeto) {
                                $inciso_Simple_Selection = $objeto->getIncisosSimpleSelection();
                                $arrayValoresSS = array();

                                $respuestaOk = $objeto->getOpcionCorrecta();
                                $valRexpuetaOk = '';

                                $indxSS = 0;
                                foreach ($inciso_Simple_Selection as $v) {
                                    //$arrayValoresSS[$v->getOrdenInciso()] = $v->getTextoOpcion();
                                    if( $respuestaOk == $v->getOrdenInciso()){
                                        $valRexpuetaOk =  $v->getTextoOpcion();
                                    }
                                    $indxSS++;
                                }
                                //$rawdataitem[$indzSS]['textoItem'] = $valRexpuetaOk;

                                $rawdataitem[$indzSS]['textoItem'] = $objeto->getTextoItem();

                                $tie = $em->getRepository('AppBundle:Respuesta_Simple_Selection')->findOneBy(array('id_estudiante' => $evaluacion->getIdEstudiante()->getId(), 'id_item_simple_selection' => $objeto->getId()));
                                $tie2 = $em->getRepository('AppBundle:Inciso_Simple_Selection')->findOneBy(array('ordenInciso' => $tie->getRespuestaEstudiante()));
                                $rawdataitem[$indzSS]['textoItemE'] = $tie2->getTextoOpcion();
                                $rawdataitem[$indzSS]['textoItemP'] = $tie->getPuntos();

                                $rawdataitem[$indzSS]['item'] = $item;
                                $item++;

                                $indzSS++;
                            }
                            $rawdata['simpleselection'] = $rawdataitem;

                        } else if ($objetoQ->getTipoPregunta() == 3) {
                            $item_Multiple_Selection = $objetoQ->getIncisosMultipleSelection();

                            $indzSM = 0;
                            $rawdataitem = array();
                            //$itemest = $item;
                            foreach ($item_Multiple_Selection as $objeto) {


                                $tie = $em->getRepository('AppBundle:Respuesta_Multiple_Selection')->findOneBy(array('id_estudiante' => $evaluacion->getIdEstudiante()->getId(), 'id_inciso_multiple_selection' => $objeto->getId()));

                                if ($tie->getRespuestaEstudiante()) {
                                    $rawdataitem[$indzSM]['textoItemE'] = $objeto->getTextoOpcion();
                                    $rawdataitem[$indzSM]['textoItemP'] = $tie->getPuntos();
                                }

                                if ($objeto->getCorrectaInciso()) {
                                    $rawdataitem[$indzSM]['textoItem'] = $objeto->getTextoOpcion();
                                    $rawdataitem[$indzSM]['item'] = $item;
                                    $item++;
                                }

                                $indzSM++;
                            }
                            $rawdata['multipleselection'] = $rawdataitem;


                        } else if ($objetoQ->getTipoPregunta() == 4) {
                            $valores_List_Selections = $objetoQ->getValoresListSelection();

                            $indvLS = 0;
                            $arrayValoresLS = array();
                            foreach ($valores_List_Selections as $v) {
                                $arrayValoresLS[$v->getTextoOpcion()] = $v->getTextoOpcion();

                                $indvLS++;
                            }

                            $item_List_Selections = $objetoQ->getItemsListSelection();

                            $indzLS = 0;
                            $rawdataitem = array();
                            foreach ($item_List_Selections as $objeto) {
                                $tie = $em->getRepository('AppBundle:Respuesta_List_Selection')->findOneBy(array('id_estudiante' => $evaluacion->getIdEstudiante()->getId(), 'id_item_list_selection' => $objeto->getId()));
                                $rawdataitem[$indzLS]['textoItemE'] = $tie->getRespuestaEstudiante();
                                $rawdataitem[$indzLS]['textoItemP'] = $tie->getPuntos();


                                $rawdataitem[$indzLS]['textoItem'] = $objeto->getOpcionCorrecta();
                                $rawdataitem[$indzLS]['item'] = $item;
                                $item++;
                                $indzLS++;
                            }
                            $rawdata['listselection'] = $rawdataitem;

                        } else if ($objetoQ->getTipoPregunta() == 5) {
                            $item_Complete = $objetoQ->getItemsComplete();

                            $indzC = 0;
                            $rawdataitem = array();
                            foreach ($item_Complete as $objeto) {

                                $tie = $em->getRepository('AppBundle:Respuesta_Completa')->findOneBy(array('id_estudiante' => $evaluacion->getIdEstudiante()->getId(), 'id_completa' => $objeto->getId()));
                                $rawdataitem[$indzC]['textoItemE'] = $tie->getRespuestaEstudiante();
                                $rawdataitem[$indzC]['textoItemP'] = $tie->getPuntos();

                                $rawdataitem[$indzC]['textoItem'] = $objeto->getRespuestaCorrecta();
                                $rawdataitem[$indzC]['item'] = $item;
                                $item++;


                                $indzC++;
                            }
                            $rawdata['complete'] = $rawdataitem;

                        }
                        $rawdataquestion['question'][$k] = $rawdata;
                        $rawdataquestion['question'][$k]['tipoPregunta'] = $objetoQ->getTipoPregunta();

                        $k++;

                    }
                    $rawdatasection['section'][$j] = $rawdataquestion;
                    //$rawdatasection['section'][$j]['sec'] = 'entro';
                    $j++;

                }
                $rawdataevaluacion['listening'] = $rawdatasection;
            }

            $response = new Response();
            $response->setContent(json_encode($rawdataevaluacion));
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


    /******************************************************************************************************/
    /************************************** User ********************************************/
    /******************************************************************************************************/


    /**
     * Lists all Results entities.
     *
     * @Route("/list_users", name="list_users")
     * @Method("GET")
     */
    public function listusuariosindexAction()
    {
        return $this->render('test/user/index.html.twig');
    }


    /**
     * Lists all Test entities.
     *
     * @Route("/list_users/json/", name="list_users_json")
     * @Method("GET")
     */
    public function jsonlistusersAction(Request $r)
    {
        //listar json de u//na pregunta
        $em = $this->getDoctrine()->getManager();
        $usuarios = $em->getRepository('AppBundle:User')->findAll();
        try {
            $arrayU = array();
            $i = 0;
            foreach ($usuarios as $u) {
                $arrayU[$i]['id'] = $u->getId();
                $arrayU[$i]['username'] = $u->getUsername();
                $arrayU[$i]['email'] = $u->getEmail();
                $arrayU[$i]['nombre'] = $u->getNombre();
                $arrayU[$i]['annocurso'] = $u->getAnnoCurso();
                $evals = $em->getRepository('AppBundle:Evaluaciones')->findBy(array('id_estudiante' => $u->getId()));
                $arrayU[$i]['total'] = count($evals);
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
     * Lists one Reading entities.
     *
     * @Route("/view_user/json", name="view_user_json")
     * @Method({"GET", "POST"})
     */
    public function viewUsertAction(Request $request)
    {
        //-------------------------------------------------------------------------------------
        //------------------------listar json de una evalaucion------------------------------------
        //-------------------------------------------------------------------------------------
        $em = $this->getDoctrine()->getManager();
        $x = json_decode($request->getContent());
        $usuario = $em->getRepository('AppBundle:User')->find($x->id);

        try {
            $rawdatausuario = array();


            $rawdatausuario['id'] = $usuario->getId();
            $rawdatausuario['nombre'] = $usuario->getNombre();
            $rawdatausuario['username'] = $usuario->getUsername();
            $rawdatausuario['email'] = $usuario->getEmail();
            $rawdatausuario['annocurso'] = $usuario->getAnnoCurso();

            $em = $this->getDoctrine()->getManager();
            $evaluaciones = $em->getRepository('AppBundle:Evaluaciones')->findBy(array('id_estudiante' => $usuario->getId()));

            $arrayE = array();
            $i = 0;
            foreach ($evaluaciones as $e) {
                $arrayE[$i]['id'] = $e->getId();
                $arrayE[$i]['user'] = $e->getIdEstudiante()->getNombre();
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


    /******************************************************************************************************/
    /************************************** PRUEBAS EVALUADAS ********************************************/
    /******************************************************************************************************/


    /**
     * Lists all Results entities.
     *
     * @Route("/list_evals", name="list_evals")
     * @Method("GET")
     */
    public function listevalsindexAction()
    {
        return $this->render('test/resultados/evalindex.html.twig');
    }


    /**
     * Lists all Test entities.
     *
     * @Route("/list_evals/json/", name="list_evals_json")
     * @Method("GET")
     */
    public function jsonlistevalsAction(Request $r)
    {
        //listar json de u//na pregunta
        $em = $this->getDoctrine()->getManager();
        $tests = $em->getRepository('AppBundle:Test')->findAll();

        try {
            $arrayE = array();
            $i = 0;
            foreach ($tests as $t) {
                $evaluado = $em->getRepository('AppBundle:Evaluaciones')->findOneBy(array('id_test'=>$t->getId()));
                if($evaluado){
                    $arrayE[$i]['id'] = $t->getId();
                    $arrayE[$i]['deprueba'] = $t->getDeprueba();
                    $arrayE[$i]['texto'] = $t->getTextoOrientacion();
                    $sfr = '-';
                    if ($t->getIdReading()) {
                        $fr = $t->getIdReading()->getFecha();
                        if ($fr) {
                            $sfr = $fr->format("d-m-Y");
                        }
                    }
                    $arrayE[$i]['reading'] = $sfr;


                    $sfl = '-';
                    if ($fl = $t->getIdListening()) {
                        $fl = $t->getIdListening()->getFecha();
                        if ($fl) {
                            $sfl = $fl->format("d-m-Y");
                        }
                    }
                    $arrayE[$i]['listening'] = $sfl;

                    $evals = $em->getRepository('AppBundle:Evaluaciones')->findBy(array('id_test' => $t->getId()));
                    $arrayE[$i]['total'] = count($evals);

                    $i++;
                }
            };
            $response = new Response();
            $response->setContent(json_encode($arrayE));
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
     * Lists one Reading entities.
     *
     * @Route("/view_eval/json", name="view_eval_json")
     * @Method({"GET", "POST"})
     */
    public function viewEvalAction(Request $request)
    {
        //-------------------------------------------------------------------------------------
        //------------------------listar json de una evalaucion------------------------------------
        //-------------------------------------------------------------------------------------
        $em = $this->getDoctrine()->getManager();
        $x = json_decode($request->getContent());
        $test = $em->getRepository('AppBundle:Test')->find($x->id);

        try {
            $rawdatausuario = array();


            $rawdatausuario['id'] = $test->getId();
            $rawdatausuario['texto'] = $test->getTextoOrientacion();

            $em = $this->getDoctrine()->getManager();
            $evaluaciones = $em->getRepository('AppBundle:Evaluaciones')->findBy(array('id_test' => $test->getId()));

            $arrayE = array();
            $i = 0;
            foreach ($evaluaciones as $e) {
                $arrayE[$i]['id'] = $e->getId();
                $arrayE[$i]['user'] = $e->getIdEstudiante()->getNombre();
                $arrayE[$i]['deprueba'] = $e->getIdTest()->getDeprueba();
                $arrayE[$i]['prueba'] = $e->getIdTest()->getTextoOrientacion();

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


}
