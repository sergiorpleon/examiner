<?php

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Reading;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Reading controller.
 *
 * @Route("/reading")
 */
class ReadingController extends Controller
{
    /**
     * Lists all Reading entities.
     *
     * @Route("/", name="reading_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $readings = $em->getRepository('AppBundle:Reading')->findAll();

        return $this->render('reading/index.html.twig', array(
            'readings' => $readings,
        ));
    }

    /**
     * Lists all Reading entities.
     *
     * @Route("/listReading/{id}/json", name="reading_json")
     * @Method("GET")
     */
    public function listAction($id)
    {
        //listar json de una pregunta
        $em = $this->getDoctrine()->getManager();
        $q= $em->getRepository('AppBundle:Reading')->find($id);

        try {
            $arrayR = array();
            $arrayR['id'] = $q->getId();
            $arrayR['textoInstrucciones'] = $q->getTextoInstrucciones();
            $arrayR['textoInformacion'] = $q->getTextoInformacion();
            $arrayR['horaComienzo'] = $q->getHoraComienzo();
            $arrayR['tiempo'] = $q->getTiempo();

            $seccionesReading = $q->getSeccionesReading();
            $rawdatasecciones = array();
            $j = 0;
            foreach ($seccionesReading as $sr) {
                $rawdatasecciones[$j]['id'] = $sr->getId();
                $rawdatasecciones[$j]['ordenSeccion'] = $sr->getOrdenSeccion();
                $rawdatasecciones[$j]['num'] = '';
                $rawdatasecciones[$j]['textoInstruccion'] = $sr->getTextoInstruccion();
                $rawdatasecciones[$j]['textoReading'] = $sr->getTextoReading();

                $questionSeccionReading = $sr->getQuestionsSeccionReadings();
                $rawdata = array();
                $k = 0;
                foreach ($questionSeccionReading as $objeto) {
                    $rawdata[$k]['id'] = $objeto->getId();
                    $rawdata[$k]['ordenPregunta'] = $objeto->getOrdenPregunta();
                    $rawdata[$k]['textoPregunta'] = $objeto->getTextoPregunta();
                    $rawdata[$k]['tipoPregunta'] = $objeto->getTipoPregunta();
                    $k++;
                }
                $rawdatasecciones[$j]['preguntas'] = $rawdata;

                $j++;
            }
            $arrayR['secciones'] = $rawdatasecciones;

            $response = new Response();
            $response->setContent(json_encode($arrayR));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        } catch(Exception $e) {
            $error = '{"error":{"text":'. $e->getMessage() .'}}';
            $response = new Response();
            $response->setContent(json_encode($error));
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
    }

    /**
     * Finds and displays a Reading entity.
     *
     * @Route("/{id}", name="reading_show")
     * @Method("GET")
     */
    public function showAction(Reading $reading)
    {

        return $this->render('reading/show.html.twig', array(
            'reading' => $reading,
        ));
    }
}
