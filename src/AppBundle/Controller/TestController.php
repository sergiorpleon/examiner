<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Carrera;
use AppBundle\Entity\Estudia;
use AppBundle\Entity\Inciso_Simple_Selection;
use AppBundle\Entity\Institucion;
use AppBundle\Entity\Item_List_Selection;
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
 * @Route("/test")
 */
class TestController extends Controller
{
    /**
     * Lists all Test entities.
     *
     * @Route("/route", name="test_route")
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
     * @Route("/", name="test_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tests = $em->getRepository('AppBundle:Test')->findAll();

        return $this->render('test/index.html.twig');
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
                $arrayP[$i]['id'] = $p->getId();
                $arrayP[$i]['deprueba'] = $p->getDeprueba();
                $arrayP[$i]['profesor'] = $p->getIdProfesor()->getNombre();
                try{
                    $textreading = "null";
                    if($p->getIdReading() == null ) {
                    }else{
                        $textreading = $p->getIdReading()->getFecha()->format("d-m-Y");
                    }

                }catch (Exception $e){
                    $textreading = "null";
                }
                try{
                    $textlistening = "null";
                    if($p->getIdListening() == null ) {
                    }else{
                        $textlistening = $p->getIdListening()->getFecha()->format("d-m-Y");
                    }

                }catch (Exception $e){
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
     * Finds and displays a Test entity.
     *
     * @Route("/{id}", name="test_show")
     * @Method("GET")

    public function showAction(Test $test)
     * {
     *
     * return $this->render('test/show.html.twig', array(
     * 'test' => $test,
     * ));
     * }
     */


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
                $r->setTextoInstrucciones($reading->textoInstrucciones);
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
                    $sec->setTextoInstruccion($s->textoInstruccion);
                    //$sec->setTextoReading($s->textoReading);
                    $sec->setIdReading($r);

                    $r->addSeccionesReading($sec);
                    $em->persist($sec);
                    $em->flush();

                    $questions = $s->preguntas;
                    foreach ($questions as $question) {
                        $q = new \AppBundle\Entity\Question();
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
                                    $item = new \AppBundle\Entity\Item_True_False();
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
                                    $item = new \AppBundle\Entity\Item_Simple_Selection();
                                    $item->setOrdenItem($i->orden);
                                    $item->setTextoItem($i->texto);
                                    $item->setOpcionCorrecta($i->correcta);
                                    $item->setIdQuestion($q);

                                    //inciso
                                    $incisosL = $i->incisos;
                                    foreach ($incisosL as $in) {
                                        $inc = new \AppBundle\Entity\Inciso_Simple_Selection();
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
                                    $item = new \AppBundle\Entity\Inciso_Multiple_Selection();
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
                                    $item = new \AppBundle\Entity\Item_List_Selection();
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
                $l->setTextoInstrucciones($listening->textoInstrucciones);
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
                    $sec->setTextoInstruccion($s->textoInstruccion);
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
                                    $item = new \AppBundle\Entity\Item_True_False();
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
                                    $item = new \AppBundle\Entity\Item_Simple_Selection();
                                    $item->setOrdenItem($i->orden);
                                    $item->setTextoItem($i->texto);
                                    $item->setOpcionCorrecta($i->correcta);
                                    $item->setIdQuestion($q);

                                    //inciso
                                    $incisosL = $i->incisos;
                                    foreach ($incisosL as $in) {
                                        $inc = new \AppBundle\Entity\Inciso_Simple_Selection();
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
                                    $item = new \AppBundle\Entity\Inciso_Multiple_Selection();
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
                                    $item = new \AppBundle\Entity\Item_List_Selection();
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
                $em->persist($l);
                $em->flush();
                //$l->setFecha(new \DateTime('today'));
                //$l->setTextoInstrucciones('textoInstrucciones');
                //$l->setTextoInformacion('info');
                //$l->setHoraComienzo(new \DateTime('today'));
                //$l->setTiempo(12);
            }
        }


        //$l->setTextoInstrucciones($listening->textoInstrucciones);
        //$l->setTextoInformacion($listening->textoInformacion);
        //$l->setHoraComienzo($listening->horaComienzo);
        //$l->setTiempo($listening->tiempo);

        $test = $x->prueba;
        $t = new \AppBundle\Entity\Test();
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

        if ($activeReading) {
            $em->persist($r);
        }
        if ($activeListening) {
            $em->persist($l);
        }


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

                        $t->setIdReading();
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
                        $t->setIdListening();
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


        $id = $x->prueba->id;
        //redirigir ojoooo
        //$error = '{"error":{"text":'. $e->getMessage() .'}}';
        $error = '{ "id" : "' . $id . '"}';
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
        $r = $t->getIdReading();
        if ($r != null) {
            $em->remove($r);
            $em->flush();
        }
        $l = $t->getIdListening();
        if ($l != null) {
            $em->remove($l);
            $em->flush();
        }
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




    /******************************************************************************************************/
    /************************************** BANCO DE PREGUNTAS ********************************************/
    /******************************************************************************************************/


    /**
     * Lists all Test entities.
     *
     * @Route("/bank_reading", name="bank_reading_index")
     * @Method("GET")
     */
    public function bankreadingindexAction()
    {
        return $this->render('test/bank/reading/index.html.twig');
    }


    /**
     * Lists all Test entities.
     *
     * @Route("/list_bank_reading/json/", name="list_bank_reading_json")
     * @Method("GET")
     */
    public function jsonbankreadingAction(Request $r)
    {
        //listar json de u//na pregunta
        $em = $this->getDoctrine()->getManager();
        $pruebas = $em->getRepository('AppBundle:B_Section_Reading')->findAll();
        try {
            $arrayP = array();
            $i = 0;
            foreach ($pruebas as $p) {
                $arrayP[$i]['id'] = $p->getId();
                //$arrayP[$i]['fecha'] = $p->getFecha();
                //$carpeta=$this->container->getParameter('images_location');
                //$imageUrl=$r->getBasePath()."/".$carpeta."/";
                //$imagenurl="http://".$_SERVER['HTTP_HOST'].$imageUrl.'blobid1510017394189.jpg';
                //$arrayP[$i]['texto'] = $_SERVER["HTTP_HOST"].'-'.$imagenurl.'-'.$r->getBasePath();
                $arrayP[$i]['texto'] = $p->getTextoInstruccion();

                $i++;
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
     * Lists all Reading entities.
     *
     * @Route("/bank_reading/json", name="bank_reading_json")
     * @Method({"GET", "POST"})
     */
    public function bankreadingAction(Request $request)
    {

        //-------------------------------------------------------------------------------------
        //------------------------listar json de una prueba------------------------------------
        //-------------------------------------------------------------------------------------
        $em = $this->getDoctrine()->getManager();
        $x = json_decode($request->getContent());
        $sr = $em->getRepository('AppBundle:B_Section_Reading')->find($x->id);

        try {

            $rawdatasecciones = array();

            $rawdatasecciones['id'] = $sr->getId();
            $rawdatasecciones['ordenSeccion'] = $sr->getOrdenSeccion();
            $rawdatasecciones['num'] = 1;
            $rawdatasecciones['urlAudio'] = "http://#";
            $rawdatasecciones['textoInstruccion'] = $sr->getTextoInstruccion();
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
            $rawdatasecciones['preguntas'] = $rawdata;


            $response = new Response();
            $response->setContent(json_encode($rawdatasecciones));
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
     * Creates a new question_List_Selection entity.
     *
     * @Route("/new_bank_reading/json", name="new_bank_reading")
     * @Method({"GET", "POST"})
     */
    public function newbankreadingAction(Request $request)
    {
        //-------------------------------------------------------------------------------------
        //------------------------guardar json de new seccion reading--------------------------
        //-------------------------------------------------------------------------------------

        $em = $this->getDoctrine()->getManager();

        $x = json_decode($request->getContent());
        $s = $x->readingtmp;

        $sec = new \AppBundle\Entity\B_Section_Reading();
        $sec->setOrdenSeccion($s->ordenSeccion);
        $sec->setTextoInstruccion($s->textoInstruccion);
        //$sec->setTextoReading($s->textoReading);

        $em->persist($sec);
        $em->flush();

        $questions = $s->preguntas;
        foreach ($questions as $question) {
            $q = new \AppBundle\Entity\B_Question();
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
                        $item = new \AppBundle\Entity\B_Item_True_False();
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
                        $item = new \AppBundle\Entity\B_Item_Simple_Selection();
                        $item->setOrdenItem($i->orden);
                        $item->setTextoItem($i->texto);
                        $item->setOpcionCorrecta($i->correcta);
                        $item->setIdQuestion($q);

                        //inciso
                        $incisosL = $i->incisos;
                        foreach ($incisosL as $in) {
                            $inc = new \AppBundle\Entity\B_Inciso_Simple_Selection();
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
                        $item = new \AppBundle\Entity\B_Inciso_Multiple_Selection();
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
                        $item = new \AppBundle\Entity\B_Item_List_Selection();
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
                        $item = new \AppBundle\Entity\B_Valor_List_Selection();
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
                        $item = new \AppBundle\Entity\B_Item_Complete();
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


        $em->persist($sec);


        $em->flush();

        //redirigir ojoooo
        //$error = '{"error":{"text":'. $e->getMessage() .'}}';
        $id = $sec->getId();
        $response = new Response();
        $response->setContent(json_encode($id));
        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }

    /**
     * Creates a new question_List_Selection entity.
     *
     * @Route("/edit_bank_reading/json", name="edit_bank_reading")
     * @Method({"GET", "POST"})
     */
    public function editbankreadingAction(Request $request)
    {
        //-------------------------------------------------------------------------------------
        //--------------------actualizar json de new prueba------------------------------------
        //-------------------------------------------------------------------------------------

        $em = $this->getDoctrine()->getManager();

        $x = json_decode($request->getContent());
        $reading = $x->readingtmp;

        $s = $em->getRepository('AppBundle:B_Section_Reading')->find($reading->id);

        if ($reading->id == -1) {
            $sec = new \AppBundle\Entity\B_Section_Reading();
        } else {
            $sec = $em->getRepository('AppBundle:B_Section_Reading')->find($reading->id);
            if ($sec == null) {
                $sec = new \AppBundle\Entity\B_Section_Reading();
            }
        }
        $sec->setOrdenSeccion($reading->ordenSeccion);
        $sec->setTextoInstruccion($reading->textoInstruccion);
        //$sec->setTextoReading($s->textoReading);

        $em->persist($sec);
        $em->flush();

        $questions = $reading->preguntas;
        foreach ($questions as $question) {
            if ($question->id == -1) {
                $q = new \AppBundle\Entity\B_Question();
            } else {
                $q = $em->getRepository('AppBundle:B_Question')->find($question->id);

                if ($q == null) {
                    $q = new \AppBundle\Entity\B_Question();
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
                            $item = new \AppBundle\Entity\B_Item_True_False();
                        } else {
                            $item = $em->getRepository('AppBundle:B_Item_True_False')->find($i->id);
                            if ($item == null) {
                                $item = new \AppBundle\Entity\B_Item_True_False();
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
                            $item = new \AppBundle\Entity\B_Item_Simple_Selection();
                        } else {
                            $item = $em->getRepository('AppBundle:B_Item_Simple_Selection')->find($i->id);
                            if ($item == null) {
                                $item = new \AppBundle\Entity\B_Item_Simple_Selection();
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
                                $inc = new \AppBundle\Entity\B_Inciso_Simple_Selection();
                            } else {
                                $inc = $em->getRepository('AppBundle:B_Inciso_Simple_Selection')->find($in->id);
                                if ($inc == null) {
                                    $inc = new \AppBundle\Entity\B_Inciso_Simple_Selection();
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
                            $item = new \AppBundle\Entity\B_Inciso_Multiple_Selection();
                        } else {
                            $item = $em->getRepository('AppBundle:B_Inciso_Multiple_Selection')->find($i->id);
                            if ($item == null) {
                                $item = new \AppBundle\Entity\B_Inciso_Multiple_Selection();
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
                            $item = new \AppBundle\Entity\B_Item_List_Selection();
                        } else {
                            $item = $em->getRepository('AppBundle:B_Item_List_Selection')->find($i->id);
                            if ($item == null) {
                                $item = new \AppBundle\Entity\B_Item_List_Selection();
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
                            $item = new \AppBundle\Entity\B_Valor_List_Selection();
                        } else {
                            $item = $em->getRepository('AppBundle:B_Valor_List_Selection')->find($v->id);
                            if ($item == null) {
                                $item = new \AppBundle\Entity\B_Valor_List_Selection();
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
                        $delC = $em->getRepository('AppBundle:B_Question')->find($question->id);
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
                            $item = new \AppBundle\Entity\B_Item_Complete();
                        } else {
                            $item = $em->getRepository('AppBundle:B_Item_Complete')->find($i->id);
                            if ($item == null) {
                                $item = new \AppBundle\Entity\B_Item_Complete();
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


        $em->persist($s);

        $em->flush();


        $delQuestion = $x->delQuestion;
        foreach ($delQuestion as $it) {
            if ($it->id == -1) {
                $item = new \AppBundle\Entity\B_Question();
            } else {
                $item = $em->getRepository('AppBundle:B_Question')->find($it->id);
                if ($item == null) {
                    $item = new \AppBundle\Entity\B_Question();
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
                $item = new \AppBundle\Entity\B_Item_True_False();
            } else {
                $item = $em->getRepository('AppBundle:B_Item_True_False')->find($it->id);
                if ($item == null) {
                    $item = new \AppBundle\Entity\B_Item_True_False();
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
                $item = new \AppBundle\Entity\B_Item_Simple_Selection();
            } else {
                $item = $em->getRepository('AppBundle:B_Item_Simple_Selection')->find($it->id);
                if ($item == null) {
                    $item = new \AppBundle\Entity\B_Item_Simple_Selection();
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
                $item = new \AppBundle\Entity\B_Inciso_Simple_Selection();
            } else {
                $item = $em->getRepository('AppBundle:B_Inciso_Simple_Selection')->find($it->id);
                if ($item == null) {
                    $item = new \AppBundle\Entity\B_Inciso_Simple_Selection();
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
                $item = new \AppBundle\Entity\B_Inciso_Multiple_Selection();
            } else {
                $item = $em->getRepository('AppBundle:B_Inciso_Multiple_Selection')->find($it->id);
                if ($item == null) {
                    $item = new \AppBundle\Entity\B_Inciso_Multiple_Selection();
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
                $item = new \AppBundle\Entity\B_Item_List_Selection();
            } else {
                $item = $em->getRepository('AppBundle:B_Item_List_Selection')->find($it->id);
                if ($item == null) {
                    $item = new \AppBundle\Entity\B_Item_List_Selection();
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
                $item = new \AppBundle\Entity\B_Valor_List_Selection();
            } else {
                $item = $em->getRepository('AppBundle:B_Valor_List_Selection')->find($it->id);
                if ($item == null) {
                    $item = new \AppBundle\Entity\B_Valor_List_Selection();
                }
            }
            if ($item != null) {
                $em->remove($item);
                $em->flush();
            }
        }


        //redirigir ojoooo
        //$error = '{"error":{"text":'. $e->getMessage() .'}}';
        $error = '{ "id" : "' . $id . '"}';
        $response = new Response();
        $response->setContent(json_encode($error));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * Creates a new question_List_Selection entity.
     *
     * @Route("/delete_bank_reading/json", name="delete_bank_reading")
     * @Method({"GET", "POST"})
     */
    public function deletebankreadingAction(Request $request)
    {
        //-------------------------------------------------------------------------------------
        //--------------------eliminar json de prueba------------------------------------
        //-------------------------------------------------------------------------------------

        $em = $this->getDoctrine()->getManager();

        $x = json_decode($request->getContent());
        $id = $x->id;

        $t = $em->getRepository('AppBundle:B_Section_Reading')->find($id);


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


    /******************************************************************************************************/
    /************************************** Listening ********************************************/
    /******************************************************************************************************/


    /**
     * Lists all Test entities.
     *
     * @Route("/bank_listening", name="bank_listening_index")
     * @Method("GET")
     */
    public function banklisteningindexAction()
    {
        return $this->render('test/bank/listening/index.html.twig');
    }


    /**
     * Lists all Test entities.
     *
     * @Route("/list_bank_listening/json/", name="list_bank_listening_json")
     * @Method("GET")
     */
    public function jsonbanklisteningAction(Request $r)
    {
        //listar json de u//na pregunta
        $em = $this->getDoctrine()->getManager();
        $pruebas = $em->getRepository('AppBundle:B_Section_Listening')->findAll();
        try {
            $arrayP = array();
            $i = 0;
            foreach ($pruebas as $p) {
                $arrayP[$i]['id'] = $p->getId();
                //$arrayP[$i]['fecha'] = $p->getFecha();
                //$carpeta=$this->container->getParameter('images_location');
                //$imageUrl=$r->getBasePath()."/".$carpeta."/";
                //$imagenurl="http://".$_SERVER['HTTP_HOST'].$imageUrl.'blobid1510017394189.jpg';
                //$arrayP[$i]['texto'] = $_SERVER["HTTP_HOST"].'-'.$imagenurl.'-'.$r->getBasePath();
                $arrayP[$i]['texto'] = $p->getTextoInstruccion();

                $i++;
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
     * Lists one Reading entities.
     *
     * @Route("/bank_listening/json", name="bank_listening_json")
     * @Method({"GET", "POST"})
     */
    public function banklisteningAction(Request $request)
    {
        //-------------------------------------------------------------------------------------
        //------------------------listar json de una prueba------------------------------------
        //-------------------------------------------------------------------------------------
        $em = $this->getDoctrine()->getManager();
        $x = json_decode($request->getContent());
        $sl = $em->getRepository('AppBundle:B_Section_Listening')->find($x->id);

        try {
            $rawdatasecciones = array();

            $rawdatasecciones['id'] = $sl->getId();
            $rawdatasecciones['ordenSeccion'] = $sl->getOrdenSeccion();
            $rawdatasecciones['num'] = 1;
            $rawdatasecciones['urlAudio'] = $sl->getUrlAudio();
            $rawdatasecciones['textoInstruccion'] = $sl->getTextoInstruccion();
            //$rawdatasecciones[$j]['textoReading'] = $sr->getTextoReading();

            $questionSeccionReading = $sl->getQuestionsSeccionListenings();
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
            $rawdatasecciones['preguntas'] = $rawdata;

            $response = new Response();
            $response->setContent(json_encode($rawdatasecciones));
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
     * Creates a new question_List_Selection entity.
     *
     * @Route("/new_bank_listening/json", name="new_bank_listening")
     * @Method({"GET", "POST"})
     */
    public function newbanklisteningAction(Request $request)
    {
        //-------------------------------------------------------------------------------------
        //------------------------guardar json de new seccion reading--------------------------
        //-------------------------------------------------------------------------------------

        $em = $this->getDoctrine()->getManager();

        $x = json_decode($request->getContent());
        $s = $x->listeningtmp;

        $sec = new \AppBundle\Entity\B_Section_Listening();
        $sec->setOrdenSeccion($s->ordenSeccion);
        $sec->setTextoInstruccion($s->textoInstruccion);
        $sec->setUrlAudio($s->urlAudio);
        //$sec->setTextoReading($s->textoReading);

        $em->persist($sec);
        $em->flush();

        $questions = $s->preguntas;
        foreach ($questions as $question) {
            $q = new \AppBundle\Entity\B_Question();
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
                        $item = new \AppBundle\Entity\B_Item_True_False();
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
                        $item = new \AppBundle\Entity\B_Item_Simple_Selection();
                        $item->setOrdenItem($i->orden);
                        $item->setTextoItem($i->texto);
                        $item->setOpcionCorrecta($i->correcta);
                        $item->setIdQuestion($q);

                        //inciso
                        $incisosL = $i->incisos;
                        foreach ($incisosL as $in) {
                            $inc = new \AppBundle\Entity\B_Inciso_Simple_Selection();
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
                        $item = new \AppBundle\Entity\B_Inciso_Multiple_Selection();
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
                        $item = new \AppBundle\Entity\B_Item_List_Selection();
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
                        $item = new \AppBundle\Entity\B_Valor_List_Selection();
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
                        $item = new \AppBundle\Entity\B_Item_Complete();
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

        $em->persist($sec);

        $em->flush();

        //redirigir ojoooo
        //$error = '{"error":{"text":'. $e->getMessage() .'}}';
        $id = $sec->getId();
        $response = new Response();
        $response->setContent(json_encode($id));
        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }

    /**
     * Creates a new question_List_Selection entity.
     *
     * @Route("/edit_bank_listening/json", name="edit_bank_listening")
     * @Method({"GET", "POST"})
     */
    public function editbanklisteningAction(Request $request)
    {
        //-------------------------------------------------------------------------------------
        //--------------------actualizar json de new prueba------------------------------------
        //-------------------------------------------------------------------------------------

        $em = $this->getDoctrine()->getManager();

        $x = json_decode($request->getContent());
        $listening = $x->listeningtmp;

        $s = $em->getRepository('AppBundle:B_Section_Listening')->find($listening->id);

        if ($listening->id == -1) {
            $sec = new \AppBundle\Entity\B_Section_Listening();
        } else {
            $sec = $em->getRepository('AppBundle:B_Section_Listening')->find($listening->id);
            if ($sec == null) {
                $sec = new \AppBundle\Entity\B_Section_Listening();
            }
        }
        $sec->setOrdenSeccion($listening->ordenSeccion);
        $sec->setTextoInstruccion($listening->textoInstruccion);
        $sec->setUrlAudio($listening->urlAudio);
        //$sec->setTextoReading($s->textoReading);

        $em->persist($sec);
        $em->flush();

        $questions = $listening->preguntas;
        foreach ($questions as $question) {
            if ($question->id == -1) {
                $q = new \AppBundle\Entity\B_Question();
            } else {
                $q = $em->getRepository('AppBundle:B_Question')->find($question->id);

                if ($q == null) {
                    $q = new \AppBundle\Entity\B_Question();
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
                            $item = new \AppBundle\Entity\B_Item_True_False();
                        } else {
                            $item = $em->getRepository('AppBundle:B_Item_True_False')->find($i->id);
                            if ($item == null) {
                                $item = new \AppBundle\Entity\B_Item_True_False();
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
                            $item = new \AppBundle\Entity\B_Item_Simple_Selection();
                        } else {
                            $item = $em->getRepository('AppBundle:B_Item_Simple_Selection')->find($i->id);
                            if ($item == null) {
                                $item = new \AppBundle\Entity\B_Item_Simple_Selection();
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
                                $inc = new \AppBundle\Entity\B_Inciso_Simple_Selection();
                            } else {
                                $inc = $em->getRepository('AppBundle:B_Inciso_Simple_Selection')->find($in->id);
                                if ($inc == null) {
                                    $inc = new \AppBundle\Entity\B_Inciso_Simple_Selection();
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
                            $item = new \AppBundle\Entity\B_Inciso_Multiple_Selection();
                        } else {
                            $item = $em->getRepository('AppBundle:B_Inciso_Multiple_Selection')->find($i->id);
                            if ($item == null) {
                                $item = new \AppBundle\Entity\B_Inciso_Multiple_Selection();
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
                            $item = new \AppBundle\Entity\B_Item_List_Selection();
                        } else {
                            $item = $em->getRepository('AppBundle:B_Item_List_Selection')->find($i->id);
                            if ($item == null) {
                                $item = new \AppBundle\Entity\B_Item_List_Selection();
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
                            $item = new \AppBundle\Entity\B_Valor_List_Selection();
                        } else {
                            $item = $em->getRepository('AppBundle:B_Valor_List_Selection')->find($v->id);
                            if ($item == null) {
                                $item = new \AppBundle\Entity\B_Valor_List_Selection();
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
                        $delC = $em->getRepository('AppBundle:B_Question')->find($question->id);
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
                            $item = new \AppBundle\Entity\B_Item_Complete();
                        } else {
                            $item = $em->getRepository('AppBundle:B_Item_Complete')->find($i->id);
                            if ($item == null) {
                                $item = new \AppBundle\Entity\B_Item_Complete();
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


        $em->persist($s);

        $em->flush();


        $delQuestion = $x->delQuestion;
        foreach ($delQuestion as $it) {
            if ($it->id == -1) {
                $item = new \AppBundle\Entity\B_Question();
            } else {
                $item = $em->getRepository('AppBundle:B_Question')->find($it->id);
                if ($item == null) {
                    $item = new \AppBundle\Entity\B_Question();
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
                $item = new \AppBundle\Entity\B_Item_True_False();
            } else {
                $item = $em->getRepository('AppBundle:B_Item_True_False')->find($it->id);
                if ($item == null) {
                    $item = new \AppBundle\Entity\B_Item_True_False();
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
                $item = new \AppBundle\Entity\B_Item_Simple_Selection();
            } else {
                $item = $em->getRepository('AppBundle:B_Item_Simple_Selection')->find($it->id);
                if ($item == null) {
                    $item = new \AppBundle\Entity\B_Item_Simple_Selection();
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
                $item = new \AppBundle\Entity\B_Inciso_Simple_Selection();
            } else {
                $item = $em->getRepository('AppBundle:B_Inciso_Simple_Selection')->find($it->id);
                if ($item == null) {
                    $item = new \AppBundle\Entity\B_Inciso_Simple_Selection();
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
                $item = new \AppBundle\Entity\B_Inciso_Multiple_Selection();
            } else {
                $item = $em->getRepository('AppBundle:B_Inciso_Multiple_Selection')->find($it->id);
                if ($item == null) {
                    $item = new \AppBundle\Entity\B_Inciso_Multiple_Selection();
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
                $item = new \AppBundle\Entity\B_Item_List_Selection();
            } else {
                $item = $em->getRepository('AppBundle:B_Item_List_Selection')->find($it->id);
                if ($item == null) {
                    $item = new \AppBundle\Entity\B_Item_List_Selection();
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
                $item = new \AppBundle\Entity\B_Valor_List_Selection();
            } else {
                $item = $em->getRepository('AppBundle:B_Valor_List_Selection')->find($it->id);
                if ($item == null) {
                    $item = new \AppBundle\Entity\B_Valor_List_Selection();
                }
            }
            if ($item != null) {
                $em->remove($item);
                $em->flush();
            }
        }

        //redirigir ojoooo
        //$error = '{"error":{"text":'. $e->getMessage() .'}}';
        $error = '{ "id" : "' . $id . '"}';
        $response = new Response();
        $response->setContent(json_encode($error));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    /**
     * Creates a new question_List_Selection entity.
     *
     * @Route("/delete_bank_listening/json", name="delete_bank_listening")
     * @Method({"GET", "POST"})
     */
    public function deletebanklisteningAction(Request $request)
    {
        //-------------------------------------------------------------------------------------
        //--------------------eliminar json de prueba------------------------------------
        //-------------------------------------------------------------------------------------
        $em = $this->getDoctrine()->getManager();
        $x = json_decode($request->getContent());
        $id = $x->id;

        $t = $em->getRepository('AppBundle:B_Section_Listening')->find($id);

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
            $rawdataevaluacion['user_id'] = $evaluacion->getIdEstudiante()->getId();
            $rawdataevaluacion['prueba_id'] = $evaluacion->getIdTest()->getId();
            $rawdataevaluacion['prueba'] = $evaluacion->getIdTest()->getTextoOrientacion();
            $rawdataevaluacion['puntosreading'] = $evaluacion->getPuntosReading();
            $rawdataevaluacion['puntoslistening'] = $evaluacion->getPuntosListening();


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
                $arrayU[$i]['id'] = $u->getId();
                $arrayU[$i]['username'] = $u->getUsername();
                $arrayU[$i]['email'] = $u->getEmail();
                $arrayU[$i]['nombre'] = $u->getNombre();
                //$arrayU[$i]['annocurso'] = $u->getAnnoCurso();
                //$evals = $em->getRepository('AppBundle:Evaluaciones')->findBy(array('id_estudiante' => $u->getId()));
                //$arrayU[$i]['total'] = count($evals);
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

}