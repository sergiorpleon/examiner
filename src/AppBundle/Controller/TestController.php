<?php

namespace AppBundle\Controller;

use AppBundle\Controller\Funciones\Funciones_Completa;
use AppBundle\Entity\Carrera;
use AppBundle\Entity\Estudia;
use AppBundle\Entity\Inciso_Simple_Selection;
use AppBundle\Entity\Institucion;
use AppBundle\Entity\Item_List_Selection;
use AppBundle\Entity\Recursos;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
class TestController extends Controller
{
    /**
     * Lists all Test entities.
     *
     * @Route("/", name="admin_home")
     * @Method("GET")
     */
    public function adminHomeAction()
    {
        //$em = $this->getDoctrine()->getManager();

        // $tests = $em->getRepository('AppBundle:Test')->findAll();




        return $this->render('test/config/home.html.twig', array(

        ));
    }

    /**
     * Lists all Test entities.
     *
     * @Route("/test", name="test_route")
     * @Method("GET")
     */
    public function routeAction()
    {
        //$em = $this->getDoctrine()->getManager();

        // $tests = $em->getRepository('AppBundle:Test')->findAll();


        $fechaaactual = new \DateTime();
        $fechaaactual->setTimezone(new \DateTimeZone('America/Caracas'));
        $stringfechaaactual = $fechaaactual->format('d-m-Y');

        return $this->render('test/route/index.html.twig', array(
            'fechaaactual' => $stringfechaaactual,

        ));
    }

    /**
     * Lists all Test entities.
     *
     * @Route("/effectuate", name="test_effectuate")
     * @Method("GET")
     */
    public function effectuateAction()
    {
        //$em = $this->getDoctrine()->getManager();

        // $tests = $em->getRepository('AppBundle:Test')->findAll();


        $fechaaactual = new \DateTime();
        $fechaaactual->setTimezone(new \DateTimeZone('America/Caracas'));
        $stringfechaaactual = $fechaaactual->format('d-m-Y');

        return $this->render('test/route/effectuate.html.twig', array(
            'fechaaactual' => $stringfechaaactual,

        ));
    }


    /**
     * Lists all Test entities.
     *
     * @Route("/json/", name="test_json")
     * @Method("GET")
     */
    public function jsonAction(Request $r)
    {
        //listar json de u//na pregunta
        $em = $this->getDoctrine()->getManager();
        $pruebas = $em->getRepository('AppBundle:Test')->findAll();
        try {
            $arrayP = array();
            $i = 0;
            foreach ($pruebas as $p) {
                $eval = $em->getRepository('AppBundle:Evaluaciones')->findBy(array('id_test' => $p->getId()));

                if ($eval == null) {
                    $arrayP[$i]['id'] = $p->getId();
                    $arrayP[$i]['deprueba'] = $p->getDeprueba();
                    $arrayP[$i]['profesor'] = $p->getIdProfesor()->getUsername();
                    try {
                        $textreading = "null";
                        if ($p->getIdReading() == null) {
                        } else {
                            $textreading = $p->getIdReading()->getFecha()->format("d-m-Y");
                        }

                    } catch (Exception $e) {
                        $textreading = "null";
                    }
                    try {
                        $textlistening = "null";
                        if ($p->getIdListening() == null) {
                        } else {
                            $textlistening = $p->getIdListening()->getFecha()->format("d-m-Y");
                        }

                    } catch (Exception $e) {
                        $textlistening = "null";
                    }

                    $arrayP[$i]['reading'] = $textreading;
                    $arrayP[$i]['listening'] = $textlistening;

                    //$arrayP[$i]['fecha'] = $p->getFecha();
                    //$carpeta=$this->container->getParameter('images_location');
                    //$imageUrl=$r->getBasePath()."/".$carpeta."/";
                    //$imagenurl="http://".$_SERVER['HTTP_HOST'].$imageUrl.'blobid1510017394189.jpg';
                    //$arrayP[$i]['texto'] = $_SERVER["HTTP_HOST"].'-'.$imagenurl.'-'.$r->getBasePath();
                    $arrayP[$i]['texto'] = $p->getTextoOrientacion();

                    $i++;
                }
            };
            $response = new Response();
            $response->setContent(json_encode($arrayP));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        } catch (Exception $e) {
            $error = '{"error":{"text":' . $e->getMessage() . '}}';
            $response = new Response();
            $response->setContent(json_encode($error));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

        /*
        return $this->render('item_list_selection/index.html.twig', array(
            'item_List_Selections' => $item_List_Selections,
        ));*/
    }


    /**
     * Lists all Test entities.
     *
     * @Route("/effec_json/", name="effectuate_json")
     * @Method("GET")
     */
    public function effectautejsonAction(Request $r)
    {
        //listar json de u//na pregunta
        $em = $this->getDoctrine()->getManager();
        $pruebas = $em->getRepository('AppBundle:Test')->findAll();
        try {
            $arrayP = array();
            $i = 0;
            foreach ($pruebas as $p) {
                $eval = $em->getRepository('AppBundle:Evaluaciones')->findOneBy(array('id_test' => $p->getId()));

                if ($eval == null) {
                } else {
                    $arrayP[$i]['id'] = $p->getId();
                    $arrayP[$i]['deprueba'] = $p->getDeprueba();
                    $arrayP[$i]['profesor'] = $p->getIdProfesor()->getUsername();
                    try {
                        $textreading = "null";
                        if ($p->getIdReading() == null) {
                        } else {
                            $textreading = $p->getIdReading()->getFecha()->format("d-m-Y");
                        }

                    } catch (Exception $e) {
                        $textreading = "null";
                    }
                    try {
                        $textlistening = "null";
                        if ($p->getIdListening() == null) {
                        } else {
                            $textlistening = $p->getIdListening()->getFecha()->format("d-m-Y");
                        }

                    } catch (Exception $e) {
                        $textlistening = "null";
                    }

                    $arrayP[$i]['reading'] = $textreading;
                    $arrayP[$i]['listening'] = $textlistening;

                    //$arrayP[$i]['fecha'] = $p->getFecha();
                    //$carpeta=$this->container->getParameter('images_location');
                    //$imageUrl=$r->getBasePath()."/".$carpeta."/";
                    //$imagenurl="http://".$_SERVER['HTTP_HOST'].$imageUrl.'blobid1510017394189.jpg';
                    //$arrayP[$i]['texto'] = $_SERVER["HTTP_HOST"].'-'.$imagenurl.'-'.$r->getBasePath();
                    $arrayP[$i]['texto'] = $p->getTextoOrientacion();

                    $i++;
                }
            };
            $response = new Response();
            $response->setContent(json_encode($arrayP));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        } catch (Exception $e) {
            $error = '{"error":{"text":' . $e->getMessage() . '}}';
            $response = new Response();
            $response->setContent(json_encode($error));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }

        /*
        return $this->render('item_list_selection/index.html.twig', array(
            'item_List_Selections' => $item_List_Selections,
        ));*/
    }

    /**
     * JSON de una prueba.
     *
     * @Route("/prueba/json", name="prueba_json")
     * @Method({"GET", "POST"})
     */
    public function listAction(Request $request)
    {

        //-------------------------------------------------------------------------------------
        //------------------------listar json de una prueba------------------------------------
        //-------------------------------------------------------------------------------------
        $em = $this->getDoctrine()->getManager();
        $x = json_decode($request->getContent());
        $q = $em->getRepository('AppBundle:Test')->find($x->id);

        try {
            $arrayP = array();
            $arrayP['id'] = $q->getId();
            //$arrayP['fecha'] = $q->getFecha();
            $arrayP['textoOrientacion'] = $q->getTextoOrientacion();
            $arrayP['deprueba'] = $q->getDeprueba();
            $arrayP['profesor'] = $q->getIdProfesor()->getUsername();

            $r = $q->getIdReading();
            if ($r == null) {
                $arrayP['activeReading'] = false;
                $arrayR = array();
            } else {
                $arrayP['activeReading'] = true;

                $arrayR = array();
                $arrayR['id'] = $r->getId();
                $arrayR['textoInstrucciones'] = $r->getTextoInstrucciones();

                $arrayR['fecha'] = $r->getFecha()->format("d-m-Y");
                //$arrayR['fecha'] = $r->getFecha()->format("d-m-Y").'-'.$r->getHoraComienzo()->format("H:i");
                //$arrayR['textoInformacion'] = $r->getTextoInformacion();
                $arrayR['horaComienzo'] = $r->getHoraComienzo()->format("H:i");
                $arrayR['tiempo'] = $r->getTiempo();
                $arrayR['totalItem'] = $r->getTotalItem();

                $seccionesReading = $r->getSeccionesReading();
                $rawdatasecciones = array();
                $j = 0;
                foreach ($seccionesReading as $sr) {
                    $rawdatasecciones[$j]['id'] = $sr->getId();
                    $rawdatasecciones[$j]['ordenSeccion'] = $sr->getOrdenSeccion();
                    $rawdatasecciones[$j]['num'] = $j + 1;
                    $rawdatasecciones[$j]['urlAudio'] = "http://#";
                    $rawdatasecciones[$j]['textoInstruccion'] = $sr->getTextoInstruccion();
                    //$rawdatasecciones[$j]['textoReading'] = $sr->getTextoReading();

                    $questionSeccionReading = $sr->getQuestionsSeccionReadings();
                    $rawdata = array();
                    $k = 0;
                    foreach ($questionSeccionReading as $objetoQ) {
                        $rawdata[$k]['id'] = $objetoQ->getId();
                        $rawdata[$k]['ordenPregunta'] = $objetoQ->getOrdenPregunta();
                        $rawdata[$k]['textoPregunta'] = $objetoQ->getTextoPregunta();
                        $rawdata[$k]['tipoPregunta'] = $objetoQ->getTipoPregunta();
                        $rawdata[$k]['totalItem'] = $objetoQ->getTotalItem();

                        if ($rawdata[$k]['tipoPregunta'] == 1) {
                            $item_True_False = $objetoQ->getItemsTrueFalse();
                            $rawdataitems = array();
                            $indz = 0;
                            foreach ($item_True_False as $objeto) {
                                $rawdataitems[$indz]['id'] = $objeto->getId();
                                $rawdataitems[$indz]['orden'] = $objeto->getOrdenItem();
                                $rawdataitems[$indz]['texto'] = $objeto->getTextoItem();
                                $rawdataitems[$indz]['seleccion'] = $objeto->getOpcionCorrecta();
                                $indz++;
                            }
                            $rawdata[$k]['truefalse']['incisos'] = $rawdataitems;

                        } else if ($rawdata[$k]['tipoPregunta'] == 2) {
                            $item_Simple_Selection = $objetoQ->getItemsSimpleSelection();
                            $rawdataitems = array();
                            $indz = 0;
                            foreach ($item_Simple_Selection as $objeto) {
                                $rawdataitems[$indz]['id'] = $objeto->getId();
                                $rawdataitems[$indz]['orden'] = $objeto->getOrdenItem();
                                $rawdataitems[$indz]['texto'] = $objeto->getTextoItem();
                                $rawdataitems[$indz]['correcta'] = $objeto->getOpcionCorrecta();


                                $inciso_Simple_Selection = $objeto->getIncisosSimpleSelection();
                                $rawdataincisos = array();
                                $indx = 0;
                                foreach ($inciso_Simple_Selection as $objetoI) {
                                    $rawdataincisos[$indx]['id'] = $objetoI->getId();
                                    $rawdataincisos[$indx]['orden'] = $objetoI->getOrdenInciso();
                                    $rawdataincisos[$indx]['texto'] = $objetoI->getTextoOpcion();
                                    $indx++;
                                }
                                $rawdataitems[$indz]['incisos'] = $rawdataincisos;

                                $indz++;
                            }
                            $rawdata[$k]['itemsSimple'] = $rawdataitems;


                        } else if ($rawdata[$k]['tipoPregunta'] == 3) {
                            $item_Multiple_Selection = $objetoQ->getIncisosMultipleSelection();
                            $rawdataitems = array();
                            $indz = 0;
                            foreach ($item_Multiple_Selection as $objeto) {
                                $rawdataitems[$indz]['id'] = $objeto->getId();
                                $rawdataitems[$indz]['orden'] = $objeto->getOrdenInciso();
                                $rawdataitems[$indz]['texto'] = $objeto->getTextoOpcion();
                                $rawdataitems[$indz]['correcta'] = $objeto->getCorrectaInciso();
                                $indz++;
                            }
                            $rawdata[$k]['selectionmultiple']['incisos'] = $rawdataitems;


                        } else if ($rawdata[$k]['tipoPregunta'] == 4) {
                            $valores_List_Selections = $objetoQ->getValoresListSelection();
                            $rawdatavalores = array();
                            $indj = 0;
                            foreach ($valores_List_Selections as $v) {
                                $rawdatavalores[$indj]['id'] = $v->getId();
                                $rawdatavalores[$indj]['texto'] = $v->getTextoOpcion();
                                $indj++;
                            }
                            $rawdata[$k]['listselection']['valores'] = $rawdatavalores;

                            $item_List_Selections = $objetoQ->getItemsListSelection();
                            $rawdataitems = array();
                            $indz = 0;
                            foreach ($item_List_Selections as $objeto) {
                                $rawdataitems[$indz]['id'] = $objeto->getId();
                                $rawdataitems[$indz]['orden'] = $objeto->getOrdenItem();
                                $rawdataitems[$indz]['texto'] = $objeto->getTextoItem();
                                $rawdataitems[$indz]['correcta'] = $objeto->getOpcionCorrecta();
                                $indz++;
                            }
                            $rawdata[$k]['listselection']['itemsList'] = $rawdataitems;

                        } else if ($rawdata[$k]['tipoPregunta'] == 5) {
                            $item_Complete = $objetoQ->getItemsComplete();
                            $rawdataitems = array();
                            $indz = 0;
                            foreach ($item_Complete as $objeto) {
                                $rawdataitems[$indz]['id'] = $objeto->getId();
                                $rawdataitems[$indz]['orden'] = $objeto->getOrdenItem();
                                $rawdataitems[$indz]['texto'] = $objeto->getRespuestaCorrecta();
                                $indz++;
                            }
                            $rawdata[$k]['completa']['vistaHtml'] = $objetoQ->getVistaHtmlCompleta();
                            $rawdata[$k]['completa']['itemsComplete'] = $rawdataitems;
                        }
                        $k++;
                    }
                    $rawdatasecciones[$j]['preguntas'] = $rawdata;

                    $j++;
                }
                $arrayR['secciones'] = $rawdatasecciones;
            }
            $arrayP['reading'] = $arrayR;


            $l = $q->getIdListening();
            if ($l == null) {
                $arrayP['activeListening'] = false;
                $arrayL = array();
            } else {
                $arrayP['activeListening'] = true;
                $arrayL = array();
                $arrayL['id'] = $l->getId();
                $arrayL['textoInstrucciones'] = $l->getTextoInstrucciones();

                $arrayL['fecha'] = $l->getFecha()->format("d-m-Y");
                //$arrayR['textoInformacion'] = $r->getTextoInformacion();
                $arrayL['horaComienzo'] = $l->getHoraComienzo()->format("H:i");
                $arrayL['tiempo'] = $l->getTiempo();
                $arrayL['totalItem'] = $l->getTotalItem();

                $seccionesListening = $l->getSeccionesListening();
                $rawdatasecciones = array();
                $j = 0;
                foreach ($seccionesListening as $sl) {
                    $rawdatasecciones[$j]['id'] = $sl->getId();
                    $rawdatasecciones[$j]['ordenSeccion'] = $sl->getOrdenSeccion();
                    $rawdatasecciones[$j]['num'] = $j + 1;
                    $rawdatasecciones[$j]['urlAudio'] = $sl->getUrlAudio();
                    $rawdatasecciones[$j]['textoInstruccion'] = $sl->getTextoInstruccion();
                    //$rawdatasecciones[$j]['textoReading'] = $sr->getTextoReading();

                    $questionSeccionListening = $sl->getQuestionsSeccionListenings();
                    $rawdata = array();
                    $k = 0;
                    foreach ($questionSeccionListening as $objetoQ) {
                        $rawdata[$k]['id'] = $objetoQ->getId();
                        $rawdata[$k]['ordenPregunta'] = $objetoQ->getOrdenPregunta();
                        $rawdata[$k]['textoPregunta'] = $objetoQ->getTextoPregunta();
                        $rawdata[$k]['tipoPregunta'] = $objetoQ->getTipoPregunta();
                        $rawdata[$k]['totalItem'] = $objetoQ->getTotalItem();

                        if ($rawdata[$k]['tipoPregunta'] == 1) {
                            $item_True_False = $objetoQ->getItemsTrueFalse();
                            $rawdataitems = array();
                            $indz = 0;
                            foreach ($item_True_False as $objeto) {
                                $rawdataitems[$indz]['id'] = $objeto->getId();
                                $rawdataitems[$indz]['orden'] = $objeto->getOrdenItem();
                                $rawdataitems[$indz]['texto'] = $objeto->getTextoItem();
                                $rawdataitems[$indz]['seleccion'] = $objeto->getOpcionCorrecta();
                                $indz++;
                            }
                            $rawdata[$k]['truefalse']['incisos'] = $rawdataitems;

                        } else if ($rawdata[$k]['tipoPregunta'] == 2) {
                            $item_Simple_Selection = $objetoQ->getItemsSimpleSelection();
                            $rawdataitems = array();
                            $indz = 0;
                            foreach ($item_Simple_Selection as $objeto) {
                                $rawdataitems[$indz]['id'] = $objeto->getId();
                                $rawdataitems[$indz]['orden'] = $objeto->getOrdenItem();
                                $rawdataitems[$indz]['texto'] = $objeto->getTextoItem();
                                $rawdataitems[$indz]['correcta'] = $objeto->getOpcionCorrecta();


                                $inciso_Simple_Selection = $objeto->getIncisosSimpleSelection();
                                $rawdataincisos = array();
                                $indx = 0;
                                foreach ($inciso_Simple_Selection as $objetoI) {
                                    $rawdataincisos[$indx]['id'] = $objetoI->getId();
                                    $rawdataincisos[$indx]['orden'] = $objetoI->getOrdenInciso();
                                    $rawdataincisos[$indx]['texto'] = $objetoI->getTextoOpcion();
                                    $indx++;
                                }
                                $rawdataitems[$indz]['incisos'] = $rawdataincisos;

                                $indz++;
                            }
                            $rawdata[$k]['itemsSimple'] = $rawdataitems;


                        } else if ($rawdata[$k]['tipoPregunta'] == 3) {
                            $item_Multiple_Selection = $objetoQ->getIncisosMultipleSelection();
                            $rawdataitems = array();
                            $indz = 0;
                            foreach ($item_Multiple_Selection as $objeto) {
                                $rawdataitems[$indz]['id'] = $objeto->getId();
                                $rawdataitems[$indz]['orden'] = $objeto->getOrdenInciso();
                                $rawdataitems[$indz]['texto'] = $objeto->getTextoOpcion();
                                $rawdataitems[$indz]['correcta'] = $objeto->getCorrectaInciso();
                                $indz++;
                            }
                            $rawdata[$k]['selectionmultiple']['incisos'] = $rawdataitems;


                        } else if ($rawdata[$k]['tipoPregunta'] == 4) {
                            $valores_List_Selections = $objetoQ->getValoresListSelection();
                            $rawdatavalores = array();
                            $indj = 0;
                            foreach ($valores_List_Selections as $v) {
                                $rawdatavalores[$indj]['id'] = $v->getId();
                                $rawdatavalores[$indj]['texto'] = $v->getTextoOpcion();
                                $indj++;
                            }
                            $rawdata[$k]['listselection']['valores'] = $rawdatavalores;

                            $item_List_Selections = $objetoQ->getItemsListSelection();
                            $rawdataitems = array();
                            $indz = 0;
                            foreach ($item_List_Selections as $objeto) {
                                $rawdataitems[$indz]['id'] = $objeto->getId();
                                $rawdataitems[$indz]['orden'] = $objeto->getOrdenItem();
                                $rawdataitems[$indz]['texto'] = $objeto->getTextoItem();
                                $rawdataitems[$indz]['correcta'] = $objeto->getOpcionCorrecta();
                                $indz++;
                            }
                            $rawdata[$k]['listselection']['itemsList'] = $rawdataitems;

                        } else if ($rawdata[$k]['tipoPregunta'] == 5) {
                            $item_Complete = $objetoQ->getItemsComplete();
                            $rawdataitems = array();
                            $indz = 0;
                            foreach ($item_Complete as $objeto) {
                                $rawdataitems[$indz]['id'] = $objeto->getId();
                                $rawdataitems[$indz]['orden'] = $objeto->getOrdenItem();
                                $rawdataitems[$indz]['texto'] = $objeto->getRespuestaCorrecta();
                                $indz++;
                            }
                            $rawdata[$k]['completa']['vistaHtml'] = $objetoQ->getVistaHtmlCompleta();
                            $rawdata[$k]['completa']['itemsComplete'] = $rawdataitems;
                        }
                        $k++;
                    }
                    $rawdatasecciones[$j]['preguntas'] = $rawdata;

                    $j++;
                }
                $arrayL['secciones'] = $rawdatasecciones;
            }
            $arrayP['listening'] = $arrayL;

            $est = $em->getRepository('AppBundle:Estudia')->findAll();
            $arrayte = array();
            $j = 0;
            foreach ($est as $element) {
                $arrayte[$j]['id'] = $element->getId();

                $te = $em->getRepository('AppBundle:TestEstudia')->findOneBy(array("id_test"=>$x->id, "id_estudia"=>$element->getId()));
                if($te){
                    $arrayte[$j]['selected'] = true;
                }else{
                    $arrayte[$j]['selected'] = false;
                }
                $arrayte[$j]['text'] = $element->__toString();
                $j ++;
            }

            $arrayP['testestudia'] = $arrayte;

            $response = new Response();
            $response->setContent(json_encode($arrayP));
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
     * JSON de una test estudia prueba.
     *
     * @Route("/testestudia/json", name="list_testestudia_json")
     * @Method({"GET", "POST"})
     */
    public function listtestestudiaAction(Request $request)
    {

        //-------------------------------------------------------------------------------------
        //------------------------listar json de una prueba------------------------------------
        //-------------------------------------------------------------------------------------
        $em = $this->getDoctrine()->getManager();
        $x = json_decode($request->getContent());
        $est = $em->getRepository('AppBundle:Estudia')->findAll();

        try {
            $arrayte = array();
            $j = 0;
            foreach ($est as $element) {
                $arrayte[$j]['id'] = $element->getId();
                $arrayte[$j]['selected'] = false;
                $arrayte[$j]['text'] = $element->__toString();
                $j ++;
            }



            $response = new Response();
            $response->setContent(json_encode($arrayte));
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


    //POST OBTENER PRUEBA

    /**
     * Creates a new prueba en BD.
     *
     * @Route("/newprueba/json", name="new_prueba")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        //-------------------------------------------------------------------------------------
        //------------------------guardar json de new prueba------------------------------------
        //-------------------------------------------------------------------------------------

        $em = $this->getDoctrine()->getManager();

        $x = json_decode($request->getContent());
        $activeReading = $x->prueba->activeReading;




        $r = null;
        if ($activeReading) {
            $r = new \AppBundle\Entity\Reading();
            $reading = $x->prueba->reading;
            if ($reading) {
                $r->setFecha(\DateTime::createFromFormat('d-m-Y', $reading->fecha));
                $r->setTextoInstrucciones(  $reading->textoInstrucciones);
                //$r->setTextoInformacion($reading->textoInformacion);
                $r->setHoraComienzo(\DateTime::createFromFormat('H:i', $reading->horaComienzo));
                $r->setTiempo(120);
                $r->setTotalItem($reading->totalItem);


                //$r->setHoraComienzo($reading->horaComienzo);
                //$r->setTiempo($reading->tiempo);


                $em->persist($r);
                $em->flush();

                $seccionR = $reading->secciones;
                foreach ($seccionR as $s) {
                    $sec = new \AppBundle\Entity\Section_Reading();
                    $sec->setOrdenSeccion($s->ordenSeccion);
                    $sec->setTextoInstruccion( $s->textoInstruccion);
                    //$sec->setTextoReading($s->textoReading);
                    $sec->setIdReading($r);

                    $r->addSeccionesReading($sec);
                    $em->persist($sec);
                    $em->flush();

                    $questions = $s->preguntas;
                    foreach ($questions as $question) {
                        $q = new \AppBundle\Entity\Question();
                        $q->setOrdenPregunta($question->ordenPregunta);
                        $q->setTextoPregunta( $question->textoPregunta);
                        $q->setTipoPregunta($question->tipoPregunta);
                        $q->setTotalItem($question->totalItem);

                        //$q->setIdSeccionListening($sl);
                        $q->setIdSeccionReading($sec);

                        $em->persist($q);
                        $em->flush();

                        $id = $q->getId();

                        switch ($question->tipoPregunta) {
                            case 1:
                                //item
                                $itemsL = $question->truefalse->incisos;
                                foreach ($itemsL as $i) {
                                    $item = new \AppBundle\Entity\Item_True_False();
                                    $item->setOrdenItem($i->orden);
                                    $item->setTextoItem( $i->texto);
                                    $item->setOpcionCorrecta($i->seleccion);
                                    $item->setIdQuestion($q);

                                    $q->addItemsTrueFalse($item);
                                    $em->persist($q);
                                    $em->flush();
                                }
                                break;
                            case 2:
                                //item
                                $itemsL = $question->itemsSimple;
                                foreach ($itemsL as $i) {
                                    $item = new \AppBundle\Entity\Item_Simple_Selection();
                                    $item->setOrdenItem($i->orden);
                                    $item->setTextoItem( $i->texto);
                                    $item->setOpcionCorrecta($i->correcta);
                                    $item->setIdQuestion($q);

                                    //inciso
                                    $incisosL = $i->incisos;
                                    foreach ($incisosL as $in) {
                                        $inc = new \AppBundle\Entity\Inciso_Simple_Selection();
                                        $inc->setOrdenInciso($in->orden);
                                        $inc->setTextoOpcion( $in->texto);
                                        $inc->setIdItemSimpleSelection($item);

                                        $item->addIncisosSimpleSelection($inc);
                                        $em->persist($q);
                                        $em->flush();

                                    }

                                    $q->addItemsSimpleSelection($item);
                                    $em->persist($q);
                                    $em->flush();
                                }

                                break;
                            case 3:
                                //item
                                $itemsL = $question->selectionmultiple->incisos;
                                foreach ($itemsL as $i) {
                                    $item = new \AppBundle\Entity\Inciso_Multiple_Selection();
                                    $item->setOrdenInciso($i->orden);
                                    $item->setTextoOpcion( $i->texto);
                                    $item->setCorrectaInciso($i->correcta);
                                    $item->setIdQuestion($q);

                                    $q->addIncisosMultipleSelection($item);
                                    $em->persist($q);
                                    $em->flush();
                                }
                                break;
                            case 4:
                                //item
                                $itemsL = $question->listselection->itemsList;
                                foreach ($itemsL as $i) {
                                    $item = new \AppBundle\Entity\Item_List_Selection();
                                    $item->setOrdenItem($i->orden);
                                    $item->setTextoItem( $i->texto);
                                    $item->setOpcionCorrecta($i->correcta);
                                    $item->setIdQuestion($q);

                                    $q->addItemsListSelection($item);
                                    $em->persist($q);
                                    $em->flush();
                                }

                                //valores
                                $valL = $question->listselection->valores;
                                foreach ($valL as $v) {
                                    $item = new \AppBundle\Entity\Valor_List_Selection();
                                    $item->setTextoOpcion($v->texto);
                                    $item->setIdQuestion($q);

                                    $q->addValoresListSelection($item);
                                    //$em->persist($q);
                                    //$em->flush();
                                }
                                break;
                            case 5:
                                if ($question->completa->vistaHtml) {
                                    $q->setVistaHtmlCompleta($question->completa->vistaHtml);
                                }
                                //item
                                $itemsL = $question->completa->itemsComplete;
                                foreach ($itemsL as $i) {
                                    $item = new \AppBundle\Entity\Item_Complete();
                                    $item->setOrdenItem($i->orden);
                                    $item->setRespuestaCorrecta($i->texto);
                                    $item->setIdQuestion($q);

                                    $q->addItemsComplete($item);
                                    $em->persist($q);
                                    $em->flush();
                                }
                                break;
                        }

                    }

                }
            } else {
                $em->persist(r);
                $em->flush();
            }
        }

        //---

        $activeListening = $x->prueba->activeListening;
        $l = null;
        if ($activeListening) {
            $listening = $x->prueba->listening;
            $l = new \AppBundle\Entity\Listening();
            if ($listening) {
                $l->setFecha(\DateTime::createFromFormat('d-m-Y', $listening->fecha));
                $l->setTextoInstrucciones( $listening->textoInstrucciones);
                //$l->setTextoInformacion('info');
                $l->setHoraComienzo(\DateTime::createFromFormat('H:i', $listening->horaComienzo));
                $l->setTiempo(12);
                $l->setTotalItem($listening->totalItem);

                $em->persist($l);
                $em->flush();


                $seccionL = $listening->secciones;
                foreach ($seccionL as $s) {
                    $sec = new \AppBundle\Entity\Section_Listening();
                    $sec->setOrdenSeccion($s->ordenSeccion);
                    $sec->setTextoInstruccion( $s->textoInstruccion);
                    $sec->setUrlAudio($s->urlAudio);

                    //$sec->setTextoReading($s->textoReading);
                    $sec->setIdListening($l);

                    $l->addSeccionesListening($sec);
                    $em->persist($sec);
                    $em->flush();

                    $questions = $s->preguntas;
                    foreach ($questions as $question) {
                        $q = new \AppBundle\Entity\Question();
                        $q->setOrdenPregunta($question->ordenPregunta);
                        $q->setTextoPregunta( $question->textoPregunta);
                        $q->setTipoPregunta($question->tipoPregunta);
                        $q->setTotalItem($question->totalItem);

                        //$q->setIdSeccionListening($sl);
                        $q->setIdSeccionListening($sec);

                        $em->persist($q);
                        $em->flush();

                        $id = $q->getId();

                        switch ($question->tipoPregunta) {
                            case 1:
                                //item
                                $itemsL = $question->truefalse->incisos;
                                foreach ($itemsL as $i) {
                                    $item = new \AppBundle\Entity\Item_True_False();
                                    $item->setOrdenItem($i->orden);
                                    $item->setTextoItem( $i->texto);
                                    $item->setOpcionCorrecta($i->seleccion);
                                    $item->setIdQuestion($q);

                                    $q->addItemsTrueFalse($item);
                                    $em->persist($q);
                                    $em->flush();
                                }
                                break;
                            case 2:
                                //item
                                $itemsL = $question->itemsSimple;
                                foreach ($itemsL as $i) {
                                    $item = new \AppBundle\Entity\Item_Simple_Selection();
                                    $item->setOrdenItem($i->orden);
                                    $item->setTextoItem( $i->texto);
                                    $item->setOpcionCorrecta($i->correcta);
                                    $item->setIdQuestion($q);

                                    //inciso
                                    $incisosL = $i->incisos;
                                    foreach ($incisosL as $in) {
                                        $inc = new \AppBundle\Entity\Inciso_Simple_Selection();
                                        $inc->setOrdenInciso($in->orden);
                                        $inc->setTextoOpcion( $in->texto);
                                        $inc->setIdItemSimpleSelection($item);

                                        $item->addIncisosSimpleSelection($inc);
                                        $em->persist($q);
                                        $em->flush();

                                    }

                                    $q->addItemsSimpleSelection($item);
                                    $em->persist($q);
                                    $em->flush();
                                }

                                break;
                            case 3:
                                //item
                                $itemsL = $question->selectionmultiple->incisos;
                                foreach ($itemsL as $i) {
                                    $item = new \AppBundle\Entity\Inciso_Multiple_Selection();
                                    $item->setOrdenInciso($i->orden);
                                    $item->setTextoOpcion( $i->texto);
                                    $item->setCorrectaInciso($i->correcta);
                                    $item->setIdQuestion($q);

                                    $q->addIncisosMultipleSelection($item);
                                    $em->persist($q);
                                    $em->flush();
                                }
                                break;
                            case 4:
                                //item
                                $itemsL = $question->listselection->itemsList;
                                foreach ($itemsL as $i) {
                                    $item = new \AppBundle\Entity\Item_List_Selection();
                                    $item->setOrdenItem($i->orden);
                                    $item->setTextoItem( $i->texto);
                                    $item->setOpcionCorrecta($i->correcta);
                                    $item->setIdQuestion($q);

                                    $q->addItemsListSelection($item);
                                    $em->persist($q);
                                    $em->flush();
                                }

                                //valores
                                $valL = $question->listselection->valores;
                                foreach ($valL as $v) {
                                    $item = new \AppBundle\Entity\Valor_List_Selection();
                                    $item->setTextoOpcion($v->texto);
                                    $item->setIdQuestion($q);

                                    $q->addValoresListSelection($item);
                                    //$em->persist($q);
                                    //$em->flush();
                                }
                                break;
                            case 5:
                                if ($question->completa->vistaHtml) {
                                    $q->setVistaHtmlCompleta($question->completa->vistaHtml);
                                }
                                //item
                                $itemsL = $question->completa->itemsComplete;
                                foreach ($itemsL as $i) {
                                    $item = new \AppBundle\Entity\Item_Complete();
                                    $item->setOrdenItem($i->orden);
                                    $item->setRespuestaCorrecta( $i->texto);
                                    $item->setIdQuestion($q);

                                    $q->addItemsComplete($item);
                                    $em->persist($q);
                                    $em->flush();
                                }
                                break;
                        }

                    }

                }
            } else {
                $em->persist($l);
                $em->flush();
            }
        }

        $t = new \AppBundle\Entity\Test();

        $test = $x->prueba;
        if ($test) {
            //$t->setFecha(new \DateTime('today'));
            //$t->setFecha($test->fecha);
            $t->setTextoOrientacion($test->textoOrientacion);
            $t->setDeprueba($test->deprueba);
        } else {
            //$t->setFecha(new \DateTime('today'));
            //$t->setFecha($test->fecha);
            $t->setTextoOrientacion('Text orientation...');
            $t->setDeprueba(false);
        }

        $user = $this->getUser();
        $t->setIdProfesor($user);

        if ($activeReading) {
            $t->setIdReading($r);
            $r->addTest($t);
        }
        if ($activeListening) {
            $t->setIdListening($l);
            $l->addTest($t);
        }


        $em->persist($t);
        $em->flush();

        if ($activeReading) {
            $em->persist($r);
        }

        if ($activeListening) {
            $em->persist($l);
        }


        $testestudia = $x->prueba->testestudia;
        $em = $this->getDoctrine()->getManager();
        $testx = $em->getRepository('AppBundle:Test')->findOneBy(array("id"=>$t->getId()));

        foreach ($x->prueba->testestudia as $s) {
            if($s->selected){
                $te = new \AppBundle\Entity\TestEstudia();

                $est= $em->getRepository('AppBundle:Estudia')->findOneBy(array("id"=>$s->id));
                $te->setIdEstudia($est);

                $te->setIdTest($testx);
                $em->persist($te);
                $em->flush();
            }
        }



        $dql = 'UPDATE AppBundle:Recursos r SET r.idTest = :idt WHERE r.idTest = :defaultid AND r.idBreading = :defaultid  AND r.idBlistening = :defaultid';
        $consulta = $em->createQuery($dql);
        $consulta->setParameter('defaultid', '-1');
        $consulta->setParameter('idt', $t->getId().'');
        $result = $consulta->getOneOrNullResult();
        $em->flush();

        //redirigir ojoooo
        //$error = '{"error":{"text":'. $e->getMessage() .'}}';
        $id = $t->getId();
        $response = new Response();
        $response->setContent(json_encode($id));
        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }

    /**
     * Edit una prueba en BD.
     *
     * @Route("/editprueba/json", name="edit_prueba")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request)
    {
        //-------------------------------------------------------------------------------------
        //--------------------actualizar json de new prueba------------------------------------
        //-------------------------------------------------------------------------------------

        $em = $this->getDoctrine()->getManager();

        $x = json_decode($request->getContent());
        $activeReading = $x->prueba->activeReading;
        $r = null;
        if ($activeReading) {
            $reading = $x->prueba->reading;
            $r = $em->getRepository('AppBundle:Reading')->find($reading->id);
            if ($r) {

            } else {
                $r = new \AppBundle\Entity\Reading();
            }

            if ($reading) {
                $r->setFecha(\DateTime::createFromFormat('d-m-Y', $reading->fecha));

                //$r->setFecha(new \DateTime('today'));
                $r->setTextoInstrucciones($reading->textoInstrucciones);
                //$r->setTextoInformacion($reading->textoInformacion);
                $r->setHoraComienzo(\DateTime::createFromFormat('H:i', $reading->horaComienzo));
                $r->setTiempo(12);
                $r->setTotalItem($reading->totalItem);
            } else {

            }
            //$r->setHoraComienzo($reading->horaComienzo);
            //$r->setTiempo($reading->tiempo);

            $em->persist($r);
            $em->flush();

            $seccionR = $reading->secciones;
            foreach ($seccionR as $s) {
                if ($s->id == -1) {
                    $sec = new \AppBundle\Entity\Section_Reading();
                } else {
                    $sec = $em->getRepository('AppBundle:Section_Reading')->find($s->id);
                    if ($sec == null) {
                        $sec = new \AppBundle\Entity\Section_Reading();
                    }
                }
                $sec->setOrdenSeccion($s->ordenSeccion);
                $sec->setTextoInstruccion($s->textoInstruccion);
                //$sec->setTextoReading($s->textoReading);
                $sec->setIdReading($r);

                $r->addSeccionesReading($sec);
                $em->persist($sec);
                $em->flush();

                $questions = $s->preguntas;
                foreach ($questions as $question) {
                    if ($question->id == -1) {
                        $q = new \AppBundle\Entity\Question();
                    } else {
                        $q = $em->getRepository('AppBundle:Question')->find($question->id);

                        if ($q == null) {
                            $q = new \AppBundle\Entity\Question();
                        }
                    }

                    $q->setOrdenPregunta($question->ordenPregunta);
                    $q->setTextoPregunta($question->textoPregunta);
                    $q->setTipoPregunta($question->tipoPregunta);
                    $q->setTotalItem($question->totalItem);

                    //$q->setIdSeccionListening($sl);
                    $q->setIdSeccionReading($sec);

                    $em->persist($q);
                    $em->flush();

                    $id = $q->getId();

                    switch ($question->tipoPregunta) {
                        case 1:
                            //item
                            $itemsL = $question->truefalse->incisos;
                            foreach ($itemsL as $i) {
                                if ($i->id == -1) {
                                    $item = new \AppBundle\Entity\Item_True_False();
                                } else {
                                    $item = $em->getRepository('AppBundle:Item_True_False')->find($i->id);
                                    if ($item == null) {
                                        $item = new \AppBundle\Entity\Item_True_False();
                                    }
                                }

                                $item->setOrdenItem($i->orden);
                                $item->setTextoItem($i->texto);
                                $item->setOpcionCorrecta($i->seleccion);
                                $item->setIdQuestion($q);

                                $q->addItemsTrueFalse($item);
                                $em->persist($q);
                                $em->flush();
                            }
                            break;
                        case 2:
                            //item
                            $itemsL = $question->itemsSimple;
                            foreach ($itemsL as $i) {
                                if ($i->id == -1) {
                                    $item = new \AppBundle\Entity\Item_Simple_Selection();
                                } else {
                                    $item = $em->getRepository('AppBundle:Item_Simple_Selection')->find($i->id);
                                    if ($item == null) {
                                        $item = new \AppBundle\Entity\Item_Simple_Selection();
                                    }
                                }
                                $item->setOrdenItem($i->orden);
                                $item->setTextoItem($i->texto);
                                $item->setOpcionCorrecta($i->correcta);
                                $item->setIdQuestion($q);

                                //inciso
                                $incisosL = $i->incisos;
                                foreach ($incisosL as $in) {
                                    if ($in->id == -1) {
                                        $inc = new \AppBundle\Entity\Inciso_Simple_Selection();
                                    } else {
                                        $inc = $em->getRepository('AppBundle:Inciso_Simple_Selection')->find($in->id);
                                        if ($inc == null) {
                                            $inc = new \AppBundle\Entity\Inciso_Simple_Selection();
                                        }
                                    }
                                    //$inc = new \AppBundle\Entity\Inciso_Simple_Selection();
                                    $inc->setOrdenInciso($in->orden);
                                    $inc->setTextoOpcion($in->texto);
                                    $inc->setIdItemSimpleSelection($item);

                                    $item->addIncisosSimpleSelection($inc);
                                    $em->persist($q);
                                    $em->flush();
                                }

                                $q->addItemsSimpleSelection($item);
                                $em->persist($q);
                                $em->flush();
                            }

                            break;
                        case 3:
                            //item
                            $itemsL = $question->selectionmultiple->incisos;
                            foreach ($itemsL as $i) {
                                if ($i->id == -1) {
                                    $item = new \AppBundle\Entity\Inciso_Multiple_Selection();
                                } else {
                                    $item = $em->getRepository('AppBundle:Inciso_Multiple_Selection')->find($i->id);
                                    if ($item == null) {
                                        $item = new \AppBundle\Entity\Inciso_Multiple_Selection();
                                    }
                                }
                                $item->setOrdenInciso($i->orden);
                                $item->setTextoOpcion($i->texto);
                                $item->setCorrectaInciso($i->correcta);
                                $item->setIdQuestion($q);

                                $q->addIncisosMultipleSelection($item);
                                $em->persist($q);
                                $em->flush();
                            }
                            break;
                        case 4:
                            //item
                            $itemsL = $question->listselection->itemsList;
                            foreach ($itemsL as $i) {
                                if ($i->id == -1) {
                                    $item = new \AppBundle\Entity\Item_List_Selection();
                                } else {
                                    $item = $em->getRepository('AppBundle:Item_List_Selection')->find($i->id);
                                    if ($item == null) {
                                        $item = new \AppBundle\Entity\Item_List_Selection();
                                    }
                                }
                                $item->setOrdenItem($i->orden);
                                $item->setTextoItem($i->texto);
                                $item->setOpcionCorrecta($i->correcta);
                                $item->setIdQuestion($q);

                                $q->addItemsListSelection($item);
                                $em->persist($q);
                                $em->flush();
                            }

                            //valores
                            $valL = $question->listselection->valores;
                            foreach ($valL as $v) {
                                if ($v->id == -1) {
                                    $item = new \AppBundle\Entity\Valor_List_Selection();
                                } else {
                                    $item = $em->getRepository('AppBundle:Valor_List_Selection')->find($v->id);
                                    if ($item == null) {
                                        $item = new \AppBundle\Entity\Valor_List_Selection();
                                    }
                                }
                                $item->setTextoOpcion($v->texto);
                                $item->setIdQuestion($q);

                                $q->addValoresListSelection($item);
                                //$em->persist($q);
                                //$em->flush();
                            }
                            break;
                        case 5:
                            if ($question->completa->vistaHtml) {
                                $q->setVistaHtmlCompleta($question->completa->vistaHtml);
                            }
                            if ($question->id != -1) {
                                $delC = $em->getRepository('AppBundle:Question')->find($question->id);
                                $delItemC = $delC->getItemsComplete();
                                foreach ($delItemC as $it) {
                                    //$item = $em->getRepository('AppBundle:Item_Complete')->find($question->completa->id);
                                    if ($it != null) {
                                        $em->remove($it);
                                        $em->flush();
                                    }
                                }
                            }
                            //item
                            $itemsL = $question->completa->itemsComplete;
                            //5
                            foreach ($itemsL as $i) {
                                if ($i->id == -1) {
                                    $item = new \AppBundle\Entity\Item_Complete();
                                } else {
                                    $item = $em->getRepository('AppBundle:Item_Complete')->find($i->id);
                                    if ($item == null) {
                                        $item = new \AppBundle\Entity\Item_Complete();
                                    }
                                }
                                $item->setOrdenItem($i->orden);
                                $item->setRespuestaCorrecta($i->texto);
                                $item->setIdQuestion($q);

                                $q->addItemsComplete($item);
                                $em->persist($q);
                                $em->flush();
                            }


                            break;
                    }
                }
            }
        }
        /*else{

            $reading = $x->prueba->reading;
            if($reading){
            $r = $em->getRepository('AppBundle:Reading')->find($reading->id);
            if ($reading->id == -1) {
               // $item = new \AppBundle\Entity\Reading();
            } else {
                $r = $em->getRepository('AppBundle:Reading')->find($reading->id);
                if ($r != null) {
                    $t->setReading(null);
                    $em->remove($r);
                    $em->flush();
                }
            }
            }

        }*/


        $activeListening = $x->prueba->activeListening;

        $l = null;
        if ($activeListening) {
            $listening = $x->prueba->listening;


            $l = $em->getRepository('AppBundle:Listening')->find($listening->id);
            if ($l) {

            } else {
                $l = new \AppBundle\Entity\Listening();
            }
            if ($listening) {
                $l->setFecha(\DateTime::createFromFormat('d-m-Y', $listening->fecha));

                //$l->setFecha(new \DateTime('today'));
                $l->setTextoInstrucciones($listening->textoInstrucciones);
                //$r->setTextoInformacion($reading->textoInformacion);
                $l->setHoraComienzo(\DateTime::createFromFormat('H:i', $listening->horaComienzo));
                $l->setTiempo(12);
                $l->setTotalItem($listening->totalItem);
            } else {

            }

            $em->persist($l);
            $em->flush();

            $seccionL = $listening->secciones;
            foreach ($seccionL as $s) {
                if ($s->id == -1) {
                    $sec = new \AppBundle\Entity\Section_listening();
                } else {
                    $sec = $em->getRepository('AppBundle:Section_Listening')->find($s->id);
                    if ($sec == null) {
                        $sec = new \AppBundle\Entity\Section_Listening();
                    }
                }
                $sec->setOrdenSeccion($s->ordenSeccion);
                $sec->setTextoInstruccion($s->textoInstruccion);
                $sec->setUrlAudio($s->urlAudio);

                //$sec->setTextoReading($s->textoReading);
                $sec->setIdListening($l);

                $l->addSeccionesListening($sec);
                $em->persist($sec);
                $em->flush();

                $questions = $s->preguntas;
                foreach ($questions as $question) {
                    if ($question->id == -1) {
                        $q = new \AppBundle\Entity\Question();
                    } else {
                        $q = $em->getRepository('AppBundle:Question')->find($question->id);

                        if ($q == null) {
                            $q = new \AppBundle\Entity\Question();
                        }
                    }

                    $q->setOrdenPregunta($question->ordenPregunta);
                    $q->setTextoPregunta($question->textoPregunta);
                    $q->setTipoPregunta($question->tipoPregunta);
                    $q->setTotalItem($question->totalItem);

                    //$q->setIdSeccionListening($sl);
                    $q->setIdSeccionListening($sec);

                    $em->persist($q);
                    $em->flush();

                    $id = $q->getId();

                    switch ($question->tipoPregunta) {
                        case 1:
                            //item
                            $itemsL = $question->truefalse->incisos;
                            foreach ($itemsL as $i) {
                                if ($i->id == -1) {
                                    $item = new \AppBundle\Entity\Item_True_False();
                                } else {
                                    $item = $em->getRepository('AppBundle:Item_True_False')->find($i->id);
                                    if ($item == null) {
                                        $item = new \AppBundle\Entity\Item_True_False();
                                    }
                                }

                                $item->setOrdenItem($i->orden);
                                $item->setTextoItem($i->texto);
                                $item->setOpcionCorrecta($i->seleccion);
                                $item->setIdQuestion($q);

                                $q->addItemsTrueFalse($item);
                                $em->persist($q);
                                $em->flush();
                            }
                            break;
                        case 2:
                            //item
                            $itemsL = $question->itemsSimple;
                            foreach ($itemsL as $i) {
                                if ($i->id == -1) {
                                    $item = new \AppBundle\Entity\Item_Simple_Selection();
                                } else {
                                    $item = $em->getRepository('AppBundle:Item_Simple_Selection')->find($i->id);
                                    if ($item == null) {
                                        $item = new \AppBundle\Entity\Item_Simple_Selection();
                                    }
                                }
                                $item->setOrdenItem($i->orden);
                                $item->setTextoItem($i->texto);
                                $item->setOpcionCorrecta($i->correcta);
                                $item->setIdQuestion($q);

                                //inciso
                                $incisosL = $i->incisos;
                                foreach ($incisosL as $in) {
                                    if ($in->id == -1) {
                                        $inc = new \AppBundle\Entity\Inciso_Simple_Selection();
                                    } else {
                                        $inc = $em->getRepository('AppBundle:Inciso_Simple_Selection')->find($in->id);
                                        if ($inc == null) {
                                            $inc = new \AppBundle\Entity\Inciso_Simple_Selection();
                                        }
                                    }
                                    //$inc = new \AppBundle\Entity\Inciso_Simple_Selection();
                                    $inc->setOrdenInciso($in->orden);
                                    $inc->setTextoOpcion($in->texto);
                                    $inc->setIdItemSimpleSelection($item);

                                    $item->addIncisosSimpleSelection($inc);
                                    $em->persist($q);
                                    $em->flush();
                                }

                                $q->addItemsSimpleSelection($item);
                                $em->persist($q);
                                $em->flush();
                            }

                            break;
                        case 3:
                            //item
                            $itemsL = $question->selectionmultiple->incisos;
                            foreach ($itemsL as $i) {
                                if ($i->id == -1) {
                                    $item = new \AppBundle\Entity\Inciso_Multiple_Selection();
                                } else {
                                    $item = $em->getRepository('AppBundle:Inciso_Multiple_Selection')->find($i->id);
                                    if ($item == null) {
                                        $item = new \AppBundle\Entity\Inciso_Multiple_Selection();
                                    }
                                }
                                $item->setOrdenInciso($i->orden);
                                $item->setTextoOpcion($i->texto);
                                $item->setCorrectaInciso($i->correcta);
                                $item->setIdQuestion($q);

                                $q->addIncisosMultipleSelection($item);
                                $em->persist($q);
                                $em->flush();
                            }
                            break;
                        case 4:
                            //item
                            $itemsL = $question->listselection->itemsList;
                            foreach ($itemsL as $i) {
                                if ($i->id == -1) {
                                    $item = new \AppBundle\Entity\Item_List_Selection();
                                } else {
                                    $item = $em->getRepository('AppBundle:Item_List_Selection')->find($i->id);
                                    if ($item == null) {
                                        $item = new \AppBundle\Entity\Item_List_Selection();
                                    }
                                }
                                $item->setOrdenItem($i->orden);
                                $item->setTextoItem($i->texto);
                                $item->setOpcionCorrecta($i->correcta);
                                $item->setIdQuestion($q);

                                $q->addItemsListSelection($item);
                                $em->persist($q);
                                $em->flush();
                            }

                            //valores
                            $valL = $question->listselection->valores;
                            foreach ($valL as $v) {
                                if ($v->id == -1) {
                                    $item = new \AppBundle\Entity\Valor_List_Selection();
                                } else {
                                    $item = $em->getRepository('AppBundle:Valor_List_Selection')->find($v->id);
                                    if ($item == null) {
                                        $item = new \AppBundle\Entity\Valor_List_Selection();
                                    }
                                }
                                $item->setTextoOpcion($v->texto);
                                $item->setIdQuestion($q);

                                $q->addValoresListSelection($item);
                                //$em->persist($q);
                                //$em->flush();
                            }
                            break;
                        case 5:
                            if ($question->completa->vistaHtml) {
                                $q->setVistaHtmlCompleta($question->completa->vistaHtml);
                            }
                            if ($question->id != -1) {
                                $delC = $em->getRepository('AppBundle:Question')->find($question->id);
                                $delItemC = $delC->getItemsComplete();
                                foreach ($delItemC as $it) {
                                    //$item = $em->getRepository('AppBundle:Item_Complete')->find($question->completa->id);
                                    if ($it != null) {
                                        $em->remove($it);
                                        $em->flush();
                                    }
                                }
                            }
                            //item
                            $itemsL = $question->completa->itemsComplete;
                            //5
                            foreach ($itemsL as $i) {
                                if ($i->id == -1) {
                                    $item = new \AppBundle\Entity\Item_Complete();
                                } else {
                                    $item = $em->getRepository('AppBundle:Item_Complete')->find($i->id);
                                    if ($item == null) {
                                        $item = new \AppBundle\Entity\Item_Complete();
                                    }
                                }
                                $item->setOrdenItem($i->orden);
                                $item->setRespuestaCorrecta($i->texto);
                                $item->setIdQuestion($q);

                                $q->addItemsComplete($item);
                                $em->persist($q);
                                $em->flush();
                            }


                            break;
                    }
                }
            }
        }


        $test = $x->prueba;
        $t = $em->getRepository('AppBundle:Test')->find($test->id);
        //$t = new \AppBundle\Entity\Test();
        if ($test) {
            //$t->setFecha(new \DateTime('today'));
            //$t->setFecha($test->fecha);
            $t->setTextoOrientacion($test->textoOrientacion);
            $t->setDeprueba($test->deprueba);
        } else {
            //$t->setFecha(new \DateTime('today'));
            //$t->setFecha($test->fecha);
            $t->setTextoOrientacion('Text orientation...');
            $t->setDeprueba(false);
        }

        if ($activeReading) {
            $t->setIdReading($r);
            $r->addTest($t);
        }
        if ($activeListening) {
            $t->setIdListening($l);
            $l->addTest($t);
        }


        $em->persist($t);
        if ($activeReading) {
            $em->persist($r);
        } else {
            $reading = $x->prueba->reading;
            if ($reading) {
                $r = $em->getRepository('AppBundle:Reading')->find($reading->id);
                if ($reading->id == -1) {
                    // $item = new \AppBundle\Entity\Reading();
                } else {
                    $r = $em->getRepository('AppBundle:Reading')->find($reading->id);
                    if ($r != null) {

                        $t->setIdReading(null);
                        $em->persist($t);
                        $em->persist($r);
                        $em->flush();

                        $r = $em->getRepository('AppBundle:Reading')->find($reading->id);
                        $em->remove($r);
                        $em->flush();
                    }
                }
            }

        }

        if ($activeListening) {
            $em->persist($l);
        } else {
            $listening = $x->prueba->listening;
            if ($listening) {
                $l = $em->getRepository('AppBundle:Listening')->find($listening->id);
                if ($listening->id == -1) {
                    // $item = new \AppBundle\Entity\Reading();
                } else {
                    $l = $em->getRepository('AppBundle:Listening')->find($listening->id);
                    if ($l != null) {
                        $t->setIdListening(null);
                        $em->persist($t);
                        $em->persist($l);
                        $em->flush();

                        $l = $em->getRepository('AppBundle:Listening')->find($listening->id);
                        $em->remove($l);
                        $em->flush();
                    }
                }
            }
        }
        $em->flush();

        $delSecR = $x->delSecR;
        foreach ($delSecR as $v) {
            if ($v->id == -1) {
                $item = new \AppBundle\Entity\Section_Reading();
            } else {
                $item = $em->getRepository('AppBundle:Section_Reading')->find($v->id);
                if ($item == null) {
                    $item = new \AppBundle\Entity\Section_Reading();
                }
            }
            if ($item != null) {
                $em->remove($item);
                $em->flush();
            }
        }
        $delQuestion = $x->delQuestion;
        foreach ($delQuestion as $it) {
            if ($it->id == -1) {
                $item = new \AppBundle\Entity\Question();
            } else {
                $item = $em->getRepository('AppBundle:Question')->find($it->id);
                if ($item == null) {
                    $item = new \AppBundle\Entity\Question();
                }
            }
            if ($item != null) {
                $em->remove($item);
                $em->flush();
            }
        }
        //1
        $delItemVoF = $x->delItemVoF;
        foreach ($delItemVoF as $it) {
            if ($it->id == -1) {
                $item = new \AppBundle\Entity\Item_True_False();
            } else {
                $item = $em->getRepository('AppBundle:Item_True_False')->find($it->id);
                if ($item == null) {
                    $item = new \AppBundle\Entity\Item_True_False();
                }
            }
            if ($item != null) {
                $em->remove($item);
                $em->flush();
            }
        }
        //2
        $delItemSS = $x->delItemSS;
        foreach ($delItemSS as $it) {
            if ($it->id == -1) {
                $item = new \AppBundle\Entity\Item_Simple_Selection();
            } else {
                $item = $em->getRepository('AppBundle:Item_Simple_Selection')->find($it->id);
                if ($item == null) {
                    $item = new \AppBundle\Entity\Item_Simple_Selection();
                }
            }
            if ($item != null) {
                $em->remove($item);
                $em->flush();
            }
        }
        $delIncisoSS = $x->delIncisoSS;
        foreach ($delIncisoSS as $it) {
            if ($it->id == -1) {
                $item = new \AppBundle\Entity\Inciso_Simple_Selection();
            } else {
                $item = $em->getRepository('AppBundle:Inciso_Simple_Selection')->find($it->id);
                if ($item == null) {
                    $item = new \AppBundle\Entity\Inciso_Simple_Selection();
                }
            }
            if ($item != null) {
                $em->remove($item);
                $em->flush();
            }
        }
        //3

        $delIncisoMS = $x->delIncisoMS;
        foreach ($delIncisoMS as $it) {
            if ($it->id == -1) {
                $item = new \AppBundle\Entity\Inciso_Multiple_Selection();
            } else {
                $item = $em->getRepository('AppBundle:Inciso_Multiple_Selection')->find($it->id);
                if ($item == null) {
                    $item = new \AppBundle\Entity\Inciso_Multiple_Selection();
                }
            }
            if ($item != null) {
                $em->remove($item);
                $em->flush();
            }
        }
        //4
        $delItemLS = $x->delItemLS;
        foreach ($delItemLS as $it) {
            if ($it->id == -1) {
                $item = new \AppBundle\Entity\Item_List_Selection();
            } else {
                $item = $em->getRepository('AppBundle:Item_List_Selection')->find($it->id);
                if ($item == null) {
                    $item = new \AppBundle\Entity\Item_List_Selection();
                }
            }
            if ($item != null) {
                $em->remove($item);
                $em->flush();
            }
        }
        $delValorLS = $x->delValorLS;
        foreach ($delValorLS as $it) {
            if ($it->id == -1) {
                $item = new \AppBundle\Entity\Valor_List_Selection();
            } else {
                $item = $em->getRepository('AppBundle:Valor_List_Selection')->find($it->id);
                if ($item == null) {
                    $item = new \AppBundle\Entity\Valor_List_Selection();
                }
            }
            if ($item != null) {
                $em->remove($item);
                $em->flush();
            }
        }


        $testestudia = $x->prueba->testestudia;
        $em = $this->getDoctrine()->getManager();
        $testx = $em->getRepository('AppBundle:Test')->findOneBy(array("id"=>$t->getId()));

        foreach ($x->prueba->testestudia as $s) {
            if($s->selected){

                $te = $em->getRepository('AppBundle:TestEstudia')->findOneBy(array("id_estudia"=>$s->id,"id_test"=>$t->getId()));
                if($te){

                }else{
                    $te = new \AppBundle\Entity\TestEstudia();
                }

                $est = $em->getRepository('AppBundle:Estudia')->findOneBy(array("id"=>$s->id));
                $te->setIdEstudia($est);
                $te->setIdTest($testx);
                $em->persist($te);
                $em->flush();
            }
        }



        $dql = 'UPDATE AppBundle:Recursos r SET r.idTest = :idt WHERE r.idTest = :defaultid AND r.idBreading = :defaultid  AND r.idBlistening = :defaultid';
        $consulta = $em->createQuery($dql);
        $consulta->setParameter('defaultid', '-1');
        $consulta->setParameter('idt', $t->getId().'');
        $result = $consulta->getOneOrNullResult();
        $em->flush();

        $id = $x->prueba->id;
        //redirigir ojoooo
        //$error = '{"error":{"text":'. $e->getMessage() .'}}';
        $error = '{ "error" : "error"}';
        $response = new Response();
        $response->setContent(json_encode($error));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * Creates a new question_List_Selection entity.
     *
     * @Route("/deleteprueba/json", name="delete_prueba")
     * @Method({"GET", "POST"})
     */
    public function deleteAction(Request $request)
    {
        //-------------------------------------------------------------------------------------
        //--------------------eliminar json de prueba------------------------------------
        //-------------------------------------------------------------------------------------

        $em = $this->getDoctrine()->getManager();

        $x = json_decode($request->getContent());
        $id = $x->id;

        $t = $em->getRepository('AppBundle:Test')->find($id);
        if ($t->getIdReading()!=null) {
            $r = $t->getIdReading();
            $em->remove($r);
            $em->flush();
        }

        if ($t->getIdListening()!=null) {
            $l = $t->getIdListening();
            $em->remove($l);
            $em->flush();
        }
        if ($t != null) {

            $recursos = $em->getRepository('AppBundle:Recursos')->findBy(array('idTest'=>$id));
            foreach ($recursos as $re) {
                //cuento si esta e test
                $em->remove($re);
            }

            $urlrecurso = $em->getRepository('AppBundle:Urlrecurso')->findAll();
            foreach ($urlrecurso as $urlrec) {
                $rec = $em->getRepository('AppBundle:Recursos')->findBy(array('idRecurso'=>$urlrec->getId()));
                if(count($rec)){
                    if(is_file($urlrec->getUrl())){
                        unlink($urlrec->getUrl());

                        $em->remove($urlrec);
                    }
                }
            }

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
     * Creates a new question_List_Selection entity.
     *
     * @Route("/delete_selected_test/json", name="delete_selected_test")
     * @Method({"GET", "POST"})
     */
    public function deleteselectedtestAction(Request $request)
    {
        //-------------------------------------------------------------------------------------
        //--------------------eliminar json de prueba------------------------------------
        //-------------------------------------------------------------------------------------

        $em = $this->getDoctrine()->getManager();

        $x = json_decode($request->getContent());
        $selected = $x->selected;

        foreach ($selected as $s) {
            $t = $em->getRepository('AppBundle:Test')->find($s->id);


            if ($t != null) {

                //Recorrer todos los elementos e ir borrando por url
                $recursos = $em->getRepository('AppBundle:Recursos')->findBy(array('idTest' => $s->id));
                $i = 0;
                foreach ($recursos as $re) {
                    //cuento si esta e test
                    $em->remove($re);

                }

                $urlrecurso = $em->getRepository('AppBundle:Urlrecurso')->findAll();
                foreach ($urlrecurso as $urlrec) {
                    $rec = $em->getRepository('AppBundle:Recursos')->findBy(array('idRecurso' => $urlrec->getId()));
                    if (count($rec)) {
                        if (is_file($urlrec->getUrl())) {
                            unlink($urlrec->getUrl());

                            $em->remove($urlrec);
                        }

                    }
                }

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