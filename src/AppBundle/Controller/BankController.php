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
class BankController extends Controller
{
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
                $arrayP[$i]['profesor'] = $p->getIdProfesor()->getUsername();

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
            $rawdatasecciones['profesor'] = $sr->getIdProfesor()->getUsername();

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

            if($x->idtest != 0){
                $recursos = $em->getRepository('AppBundle:Recursos')->findBy(array('idBreading'=>$x->id));
                foreach ($recursos as $re) {
                    $newR = new Recursos();
                    $newR->setIdTest($x->idtest);
                    $newR->setIdBreading('-1');
                    $newR->setIdBListening('-1');
                    $newR->setIdRecurso($re->getIdRecurso());
                    $em->persist($newR);
                }
            }


            $em->flush();


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


        $user = $this->getUser();
        $sec->setIdProfesor($user);
        $em->persist($sec);

        $dql = 'UPDATE AppBundle:Recursos r SET r.idBreading = :idr WHERE r.idBreading = :defaultid AND r.idBlistening = :defaultid AND r.idTest = :defaultid';
        $consulta = $em->createQuery($dql);
        $consulta->setParameter('defaultid', '-1');
        $consulta->setParameter('idr', $sec->getId().'');
        $result = $consulta->getOneOrNullResult();

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
        $error = '{ "error" : "error"}';
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

            //Recorrer todos los elementos e ir borrando por url
            $recursos = $em->getRepository('AppBundle:Recursos')->findBy(array('idBreading'=>$id));
            $i = 0;
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
     * @Route("/delete_selected_reading/json", name="delete_selected_reading")
     * @Method({"GET", "POST"})
     */
    public function deleteselectedreadingAction(Request $request)
    {
        //-------------------------------------------------------------------------------------
        //--------------------eliminar json de prueba------------------------------------
        //-------------------------------------------------------------------------------------

        $em = $this->getDoctrine()->getManager();

        $x = json_decode($request->getContent());
        $selected = $x->selected;

        foreach ($selected as $s) {
            $t = $em->getRepository('AppBundle:B_Section_Reading')->find($s->id);


            if ($t != null) {

                //Recorrer todos los elementos e ir borrando por url
                $recursos = $em->getRepository('AppBundle:Recursos')->findBy(array('idBreading' => $s->id));
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
                $arrayP[$i]['profesor'] = $p->getIdProfesor()->getUsername();

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
            $rawdatasecciones['profesor'] = $sl->getIdProfesor()->getUsername();

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


            if($x->idtest != 0) {
                $recursos = $em->getRepository('AppBundle:Recursos')->findBy(array('idBlistening' => $x->id));
                foreach ($recursos as $re) {
                    $newR = new Recursos();
                    $newR->setIdTest($x->idtest);
                    $newR->setIdBreading('-1');
                    $newR->setIdReading('-1');
                    $newR->setIdRecurso($re->getId());
                    $em->persist($newR);
                }
            }

            $em->flush();

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
        $s = $x->readingtmp;

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

        $user = $this->getUser();
        $sec->setIdProfesor($user);
        $em->persist($sec);

        $dql = 'UPDATE AppBundle:Recursos r SET r.idBlistening = :idl WHERE r.idBlistening = :defaultid AND r.idBreading = :defaultid AND r.idTest = :defaultid';
        $consulta = $em->createQuery($dql);
        $consulta->setParameter('defaultid', '-1');
        $consulta->setParameter('idl', $sec->getId().'');
        $result = $consulta->getOneOrNullResult();

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
        $listening = $x->readingtmp;

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
        $error = '{ "id" : "1"}';
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
            //Recorrer todos los elementos e ir borrando por url
//Recorrer todos los elementos e ir borrando por url
            $recursos = $em->getRepository('AppBundle:Recursos')->findBy(array('idBlistening'=>$id));
            foreach ($recursos as $re) {
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
     * @Route("/delete_selected_listening/json", name="delete_selected_listening")
     * @Method({"GET", "POST"})
     */
    public function deleteselectedlisteningAction(Request $request)
    {
        //-------------------------------------------------------------------------------------
        //--------------------eliminar json de prueba------------------------------------
        //-------------------------------------------------------------------------------------

        $em = $this->getDoctrine()->getManager();

        $x = json_decode($request->getContent());
        $selected = $x->selected;

        foreach ($selected as $s) {
            $t = $em->getRepository('AppBundle:B_Section_Listening')->find($s->id);


            if ($t != null) {

                //Recorrer todos los elementos e ir borrando por url
                $recursos = $em->getRepository('AppBundle:Recursos')->findBy(array('idBlistening' => $s->id));
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
