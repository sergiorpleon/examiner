<?php

namespace AppBundle\Controller;

use AppBundle\Controller\Funciones\Funciones_Completa;
use AppBundle\Entity\Evaluaciones;
use AppBundle\Entity\Respuesta_Completa;
use AppBundle\Entity\Respuesta_List_Selection;
use AppBundle\Entity\Respuesta_Multiple_Selection;
use AppBundle\Entity\Respuesta_Simple_Selection;
use AppBundle\Entity\Respuesta_True_False;
use AppBundle\Repository\TestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class EvaluacionesController extends Controller
{

    /**
     * Lists one Test entities to Studients.
     *
     * @Route("/examen", name="responder_examen")
     * @Method({"GET","POST"})
     */
    public function responderExamen(Request $request)
    {
        /*
                //$em = $this->getDoctrine()->getManager();
                // $tests = $em->getRepository('AppBundle:Test')->findAll();
                $name='name';
                $defaultData = array ( 'message' => 'Type your message here' );

                $builder = $this -> createFormBuilder($defaultData);
                $builder  -> add ( $name , TextType :: class );
                $builder-> add ( 'email' , EmailType :: class );
                $builder-> add ( 'email' , EmailType :: class );
                $builder-> add ( 'message' , TextareaType :: class );
                $builder-> add ( 'send' , SubmitType :: class );


                $form = $builder -> getForm ();
        */
        /*
                $form = $this -> createFormBuilder ( $defaultData )

                    -> add ( $name , TextType :: class )
                    -> add ( 'email' , EmailType :: class )
                    -> add ( 'message' , TextareaType :: class )
                    -> add ( 'send' , SubmitType :: class )
                    -> getForm ();
        */

        $defaultData = array ( 'message' => 'Type your message here' );

        $form = $this -> createFormBuilder ( $defaultData )
            -> add ( 'name' , TextType::class )
            // -> add ( 'email' , EmailType :: class )
            // -> add ( 'message' , TextareaType :: class )
            -> add ( 'send' , SubmitType :: class )
            -> getForm ();
        $form -> handleRequest ( $request );

        if ( $form -> isSubmitted () && $form -> isValid ()) {
            // data is an array with "name", "email", and "message" keys
            $data = $form -> getData ();
            //$message = $request -> request -> get ( 'message' );

            return $this->render('test/evaluacion/show.html.twig'
                , array(
                    'name' => $data['atribute_0'],
                    // 'message' => $data['message'],
                )
            );
        }

        return $this->render('test/evaluacion/index.html.twig'
            , array(
                'form' => $form->createView(),
            )
        );

    }


    /**
     * Lists one Test entities to Studient.
     *
     * @Route("/eval_reading/{id}", name="eval_examen_reading")
     * @Method({"GET","POST"})
     */
    public function evalExamenReadingAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        //$test = $em->getRepository('AppBundle:Test')->findReady($id);
        $test = $em->getRepository('AppBundle:Test')->find($id);
        //$usuarios = $em->getRepository('AppBundle:User')->find(1);
        if ( is_null($test) ) {
            //return new Response("KO");
            return $this->render('global/error.html.twig', array(
                'mensaje' => 'Test null',
            ));
        }


        $defaultData = array('message' => 'Type your message here');
        $builder = $this->createFormBuilder($defaultData);
        //$builder  -> add ( 'name' , TextType :: class );
        //$builder-> add ( 'email' , EmailType :: class );

        $r = $test->getIdReading();


        $datetime = new \DateTime();
        $datetime -> setTimezone(new \DateTimeZone( $this->container->getParameter('zona_horaria')));
        //$datetime -> setTimezone(new \DateTimeZone('America/Caracas'));

        $anno_actual =  $datetime -> format('Y');
        $mes_actual = $datetime -> format('m');
        $dia_actual = $datetime -> format('d');
        $hora_actual = $datetime -> format('H');
        $minuto_actual = $datetime -> format('i');

        //$ahora = time();
        //$anno_actual = date("Y", $ahora);
        //$mes_actual = date("m", $ahora);
        //$dia_actual = date("d", $ahora);
        //$hora_actual = floor(($ahora %(24*60*60))/(60*60));
        //$minuto_actual = floor((($ahora %(24*60*60))/60)%60);

        //$adicionado = $ahora+30*60;

        $date_test = $test->getIdReading()->getFecha();
        $date_test -> setTimezone(new \DateTimeZone( $this->container->getParameter('zona_horaria')));
        $anno_test = $date_test->format('Y');
        $mes_test = $date_test->format('m');
        $dia_test = $date_test->format('d');
        $time_test = $test->getIdReading()->getHoraComienzo();
        $hora_test = $time_test->format('H');
        $minuto_test = $time_test->format('i');

        $tiempo_examen = 90;
        if($test->getDeprueba() == 0){
            if($anno_actual!=$anno_test or $mes_actual!=$mes_test or $dia_actual!=$dia_test){
                if(!(($hora_actual*60+$minuto_actual)>($hora_test*60+$minuto_test)) or !(($hora_actual*60+$minuto_actual)<($hora_test*60+$minuto_test+$tiempo_examen)) ){
                    //return new Response("Examen fuera de tiempo");
                    return $this->render('global/fueradetiempo.html.twig', array(
                    ));
                }
            }
        }

        $seccionesReading = $r->getSeccionesReading();
        $j = 0;
        foreach ($seccionesReading as $sr) {
            $questionSeccionReading = $sr->getQuestionsSeccionReadings();

            $k = 0;
            foreach ($questionSeccionReading as $objetoQ) {

                if ($objetoQ->getTipoPregunta() == 1) {
                    $item_True_False = $objetoQ->getItemsTrueFalse();

                    $indzTF = 0;
                    foreach ($item_True_False as $objeto) {
                        $textoTF = 'p' . '_r' . '_s' . $j . '_q' . $k . '_tf' . $indzTF;
                        $builder->add($textoTF, ChoiceType :: class, array(
                            'choices' => array('0'=>'True','1'=>'False','2'=>'Not Given'),
                            'expanded' => true,
                            'multiple' => false,
                        ));
                        $indzTF++;
                    }

                } else if ($objetoQ->getTipoPregunta() == 2) {
                    $item_Simple_Selection = $objetoQ->getItemsSimpleSelection();

                    $indzSS = 0;
                    foreach ($item_Simple_Selection as $objeto) {
                        $inciso_Simple_Selection = $objeto->getIncisosSimpleSelection();
                        $arrayValoresSS = array();
                        $indxSS = 0;
                        foreach ($inciso_Simple_Selection as $v) {
                            $arrayValoresSS[$v->getOrdenInciso()] = $v->getTextoOpcion();
                            $indxSS++;
                        }
                        $textoSS = 'p' . '_r' . '_s' . $j . '_q' . $k . '_ss' . $indzSS;
                        //$builder  -> add ( $textoSS , TextType :: class );
                        $builder->add($textoSS, ChoiceType :: class, array(
                            'choices' => $arrayValoresSS,
                            'expanded' => true,
                            'multiple' => false,
                        ));

                        $indzSS++;
                    }

                } else if ($objetoQ->getTipoPregunta() == 3) {
                    $item_Multiple_Selection = $objetoQ->getIncisosMultipleSelection();

                    $indzSM = 0;
                    foreach ($item_Multiple_Selection as $objeto) {
                        // $j-indice de la seccion
                        // $k-indice de la pregunta
                        // $indzSM-indice del inciso
                        //$arrayValoresSM = array('expanded' => 'lolo');
                        $arrayValoresSM[0] = $objeto->getTextoOpcion();

                        $textoSM = 'p' . '_r' . '_s' . $j . '_q' . $k . '_sm' . $indzSM;
                        $builder->add($textoSM, ChoiceType :: class, array(
                            'choices' => $arrayValoresSM,
                            'expanded' => true,
                            'multiple' => true,
                        ));
                        $indzSM++;
                    }

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
                    foreach ($item_List_Selections as $objeto) {
                        $textoLS = 'p' . '_r' . '_s' . $j . '_q' . $k . '_ls' . $indzLS;

                        $builder->add($textoLS, ChoiceType :: class, array(
                            'choices' => $arrayValoresLS,
                        ));
                        $indzLS++;
                    }

                } else if ($objetoQ->getTipoPregunta() == 5) {
                    $item_Complete = $objetoQ->getItemsComplete();

                    $indzC = 0;
                    foreach ($item_Complete as $objeto) {
                        $textoC = 'p' . '_r' . '_s' . $j . '_q' . $k . '_c' . $indzC;
                        $builder->add($textoC, TextType :: class);

                        $indzC++;
                    }
                }
                $k++;
            }
            $j++;
        }

        $builder->add('send', SubmitType :: class);
        $form = $builder->getForm();

        //$form = $this->createForm(new EvaluacionPruebaType(), $evalprueba);
        $form->handleRequest($request);

        if ($form->isSubmitted()) { //&& $form->isValid()
            $em = $this->getDoctrine()->getManager();
            $data = $form->getData();

            $user = $this->getUser();

            $totalPuntos = 0;

            $seccionesReading = $r->getSeccionesReading();
            $j = 0;
            foreach ($seccionesReading as $sr) {
                $questionSeccionReading = $sr->getQuestionsSeccionReadings();

                $k = 0;
                foreach ($questionSeccionReading as $objetoQ) {

                    if ($objetoQ->getTipoPregunta() == 1) {
                        $item_True_False = $objetoQ->getItemsTrueFalse();
                        $indzTF = 0;
                        foreach ($item_True_False as $objeto) {
                            $textoTF = 'p' . '_r' . '_s' . $j . '_q' . $k . '_tf' . $indzTF;
                            // $builder->add($textoTF, TextType :: class);
                            //--- ALAIN
                            $puntos = 0;
                            if ($objeto->getOpcionCorrecta() == $data[$textoTF]) {
                                $puntos = 1;
                            }

                            $totalPuntos += $puntos;

                            $respuestaTF = new Respuesta_True_False();
                            $respuestaTF->setIdEstudiante($user);
                            $respuestaTF->setIdItem($objeto);
                            $respuestaTF->setRespuesta($data[$textoTF]);
                            $respuestaTF->setPuntos($puntos);

                            $em->persist($respuestaTF);
                            $em->flush();

                            $indzTF++;
                            //----- ALAIN
                        }

                    } else if ($objetoQ->getTipoPregunta() == 2) {
                        $item_Simple_Selection = $objetoQ->getItemsSimpleSelection();

                        $indzSS = 0;
                        foreach ($item_Simple_Selection as $objeto) {
                            $textoSS = 'p' . '_r' . '_s' . $j . '_q' . $k . '_ss' . $indzSS;
                            $puntos = 0;
                            if ($objeto->getOpcionCorrecta() == $data[$textoSS]) {
                                $puntos = 1;
                            }

                            $totalPuntos += $puntos;

                            $respuestaSS = new Respuesta_Simple_Selection();
                            $respuestaSS->setIdEstudiante($user);
                            $respuestaSS->setIdItemSimpleSelection($objeto);
                            $respuestaSS->setRespuestaEstudiante($data[$textoSS]);
                            $respuestaSS->setPuntos($puntos);

                            $em->persist($respuestaSS);
                            $em->flush();

                            $indzSS++;
                        }

                    } else if ($objetoQ->getTipoPregunta() == 3) {

                        $item_Multiple_Selection = $objetoQ->getIncisosMultipleSelection();

                        $indzSM = 0;
                        foreach ($item_Multiple_Selection as $objeto) {
                            $textoSM = 'p' . '_r' . '_s' . $j . '_q' . $k . '_sm' . $indzSM;
                            $puntos = 0;
                            if ($objeto->getCorrectaInciso() == $data[$textoSM] && $objeto->getCorrectaInciso() == 1) {
                                $puntos = 1;
                            }
                            $respuesta = false;
                            if (true == $data[$textoSM]) {
                                $respuesta = true;
                            }

                            $totalPuntos += $puntos;

//							echo $puntos;
                            $respuestaSM = new Respuesta_Multiple_Selection();
                            $respuestaSM->setIdEstudiante($user);
                            $respuestaSM->setIdIncisoMultipleSelection($objeto);
                            $respuestaSM->setRespuestaEstudiante($respuesta);
                            $respuestaSM->setPuntos($puntos);

                            $em->persist($respuestaSM);
                            $em->flush();

                            $indzSM++;
                        }

                    } else if ($objetoQ->getTipoPregunta() == 4) {
                        $item_List_Selections = $objetoQ->getItemsListSelection();

                        $indzLS = 0;
                        foreach ($item_List_Selections as $objeto) {
                            $textoLS = 'p' . '_r' . '_s' . $j . '_q' . $k . '_ls' . $indzLS;
                            $puntos = 0;
                            if ($objeto->getOpcionCorrecta() == $data[$textoLS]) {
                                $puntos = 1;
                            }

                            $totalPuntos += $puntos;

                            $respuestaLS = new Respuesta_List_Selection();
                            $respuestaLS->setIdEstudiante($user);
                            $respuestaLS->setIdItemListSelection($objeto);
                            $respuestaLS->setRespuestaEstudiante($data[$textoLS]);
                            $respuestaLS->setPuntos($puntos);

                            $em->persist($respuestaLS);
                            $em->flush();

                            $indzLS++;
                        }

                    } else if ($objetoQ->getTipoPregunta() == 5) {
                        $item_Complete = $objetoQ->getItemsComplete();

                        $indzC = 0;
                        foreach ($item_Complete as $objeto) {
                            $textoC = 'p' . '_r' . '_s' . $j . '_q' . $k . '_c' . $indzC;
                            $puntos = 0;

                            $puntos = 0;
                            if (Funciones_Completa::Completa_Comprueba_Respuesta($objeto->getRespuestaCorrecta(),$data[$textoC])){

                            //if ($objeto->getRespuestaCorrecta() == $data[$textoC]) {
                                $puntos = 1;
                            }

                            $totalPuntos += $puntos;

                            $respuestaC = new Respuesta_Completa();
                            $respuestaC->setIdEstudiante($user);
                            $respuestaC->setIdCompleta($objeto);
                            $respuestaC->setRespuestaEstudiante($data[$textoC]);
                            $respuestaC->setPuntos($puntos);

                            $em->persist($respuestaC);
                            $em->flush();

                            $indzC++;
                        }
                    }
                    $k++;
                }
                $j++;
            }

            $evaluacionTest = $em->getRepository('AppBundle:Evaluaciones')->findOneBy(array('id_test' => $test, 'id_estudiante' => $user));

                $evaluacionTest = new Evaluaciones();
                $evaluacionTest->setIdEstudiante($user);
                $evaluacionTest->setIdTest($test);
                $evaluacionTest->setPuntosReading($totalPuntos);
                $em->persist($evaluacionTest);
                $em->flush();

            //return new Response("OK");
            return $this->render('global/pruebaenviada.html.twig', array(
            ));
            //return $this->redirectToRoute("app_list_pregunta");
        }

        $date_test = $test->getIdReading()->getFecha();
        $time_test = $test->getIdReading()->getHoraComienzo();


        return $this->render('test/evaluacion/shownewevalreading.html.twig', array(
            'form' => $form->createView(),
            'prueba' => $test,
            //'ahora'=> $ahora,
            //'adicionado'=> $adicionado,
            'testeo'=> true,
            'zonahoraria'=>$this->container->getParameter('zona_horaria'),

            //'user_nombre' => $user_nombre,
        //'user_pin' => $user_pin,
        //'user_curso' => $user_curso,
            // 'estudiante'=>$usuarios,

        ));
    }



        /**
         * Lists all Test entities to Studient.
         *
         * @Route("/list_reading", name="list_reading")
         * @Method({"GET","POST"})
         */
        public function listExamenReadingAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        //$test = $em->getRepository('AppBundle:Test')->findReadyAll();
        $consulta = $em->createQuery('SELECT t FROM AppBundle:Test t
JOIN t.id_reading r WHERE t.deprueba = 0 AND r.fecha < :fecha1  AND r.fecha >= :fecha2 ORDER BY r.fecha ASC');

        $consulta->setParameter('fecha1', new \DateTime('today'));
        $consulta->setParameter('fecha2', new \DateTime('yesterday'));
        $test = $consulta->getResult();

        //$test = $em->getRepository('AppBundle:Test')->findBy(array('deprueba'=>0));
        //$usuarios = $em->getRepository('AppBundle:User')->find(1) ;


        $datetime = new \DateTime();
        $datetime -> setTimezone(new \DateTimeZone( $this->container->getParameter('zona_horaria')));
        //$datetime -> setTimezone(new \DateTimeZone('America/Caracas'));

        $anno_actual =  $datetime -> format('Y');
        $mes_actual = $datetime -> format('m');
        $dia_actual = $datetime -> format('d');
        $hora_actual = $datetime -> format('H');
        $minuto_actual = $datetime -> format('i');
        $segundo_actual = $datetime -> format('s');

        $datetime = new \DateTime();
        $datetime -> setTimezone(new \DateTimeZone($this->container->getParameter('zona_horaria')));

        $actual = new \DateTime('now');
        $fin = new \DateTime('now - 90 minutes');

        return $this->render('test/evaluacion/showlistreading.html.twig', array(
            //'form' => $form->createView(),
            'pruebas' => $test,
            'anno_actual'=>$anno_actual,
            'mes_actual'=>$mes_actual,
            'dia_actual'=>$dia_actual,
            'hora_actual'=>$hora_actual,
            'minuto_actual'=>$minuto_actual,
            'segundo_actual'=>$segundo_actual,
            'fin'=> $fin->format('H:i'),

            'zonahoraria'=>$this->container->getParameter('zona_horaria'),
            'datetime' => $datetime->format('m/d/Y H:i:s'),
            'testeo'=> true
            // 'estudiante'=>$usuarios,
        ));
    }

    /**
     * Lists all Test entities to Studient.
     *
     * @Route("/list_reading_practice", name="list_reading_practice")
     * @Method({"GET","POST"})
     */
    public function listExamenReadingDPAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        //$test = $em->getRepository('AppBundle:Test')->findReadyAll();
        //$test = $em->getRepository('AppBundle:Test')->findBy(array('deprueba'=>1));
        //$usuarios = $em->getRepository('AppBundle:User')->find(1) ;
        $consulta = $em->createQuery('SELECT t FROM AppBundle:Test t
JOIN t.id_reading r WHERE t.deprueba = 1 AND r.fecha < :fecha1  AND r.fecha >= :fecha2 ORDER BY r.fecha ASC');
        $consulta->setParameter('fecha1', new \DateTime('today'));
        $consulta->setParameter('fecha2', new \DateTime('yesterday'));
        $test = $consulta->getResult();

        $datetime = new \DateTime();
        $datetime -> setTimezone(new \DateTimeZone( $this->container->getParameter('zona_horaria')));
        //$datetime -> setTimezone(new \DateTimeZone('America/Caracas'));

        $anno_actual =  $datetime -> format('Y');
        $mes_actual = $datetime -> format('m');
        $dia_actual = $datetime -> format('d');
        $hora_actual = $datetime -> format('H');
        $minuto_actual = $datetime -> format('i');
        $segundo_actual = $datetime -> format('s');

        //$datetime = new \DateTime();
        //$datetime -> setTimezone(new \DateTimeZone('America/Caracas'));

        //$actual = new \DateTime('now');
        //$fin = new \DateTime('now - 150 minutes');

        return $this->render('test/evaluacion/showlistreading.html.twig', array(
            //'form' => $form->createView(),
            'pruebas' => $test,
            'anno_actual'=>$anno_actual,
            'mes_actual'=>$mes_actual,
            'dia_actual'=>$dia_actual,
            'hora_actual'=>$hora_actual,
            'minuto_actual'=>$minuto_actual,
            'segundo_actual'=>$segundo_actual,
            //'fin'=> $fin->format('H:i'),

            'zonahoraria'=>$this->container->getParameter('zona_horaria'),
            //'datetime' => $datetime->format('m/d/Y H:i:s'),
            'testeo'=> true
            // 'estudiante'=>$usuarios,
        ));
    }


    /**
     * Lists all Test entities.
     *
     * @Route("/eval_listening/{id}", name="eval_examen_listening")
     * @Method({"GET","POST"})
     */
    public function evalExamenListeningAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        //$test = $em->getRepository('AppBundle:Test')->findReady($id);
        $test = $em->getRepository('AppBundle:Test')->find($id);
        //$usuarios = $em->getRepository('AppBundle:User')->find(1);
        if ( is_null($test) ) {
            //return new Response("KO");
            return $this->render('global/error.html.twig', array(
                'mensaje' => 'Test null',
            ));
        }


        $defaultData = array('message' => 'Type your message here');
        $builder = $this->createFormBuilder($defaultData);
        //$builder  -> add ( 'name' , TextType :: class );
        //$builder-> add ( 'email' , EmailType :: class );


        $r = $test->getIdListening();

        $datetime = new \DateTime();
        $datetime -> setTimezone(new \DateTimeZone( $this->container->getParameter('zona_horaria')));
        //$datetime -> setTimezone(new \DateTimeZone('America/Caracas'));

        $anno_actual =  $datetime -> format('Y');
        $mes_actual = $datetime -> format('m');
        $dia_actual = $datetime -> format('d');
        $hora_actual = $datetime -> format('H');
        $minuto_actual = $datetime -> format('i');

        //$ahora = time();
        //$anno_actual = date("Y", $ahora);
        //$mes_actual = date("m", $ahora);
        //$dia_actual = date("d", $ahora);
        //$hora_actual = floor(($ahora %(24*60*60))/(60*60));
        //$minuto_actual = floor((($ahora %(24*60*60))/60)%60);

        //$adicionado = $ahora+30*60;

        $date_test = $test->getIdListening()->getFecha();
        $date_test -> setTimezone(new \DateTimeZone( $this->container->getParameter('zona_horaria')));
        $anno_test = $date_test->format('Y');
        $mes_test = $date_test->format('m');
        $dia_test = $date_test->format('d');
        $time_test = $test->getIdListening()->getHoraComienzo();
        $hora_test = $time_test->format('H');
        $minuto_test = $time_test->format('i');

        $tiempo_examen = 90;
        if($test->getDeprueba() == 0){
            if($anno_actual!=$anno_test or $mes_actual!=$mes_test or $dia_actual!=$dia_test){
                if(!(($hora_actual*60+$minuto_actual)>($hora_test*60+$minuto_test)) or !(($hora_actual*60+$minuto_actual)<($hora_test*60+$minuto_test+$tiempo_examen))){
                    //return new Response("Examen fuera de tiempo");
                    return $this->render('global/fueradetiempo.html.twig', array(
                    ));
                }
            }
        }


        $seccionesListening = $r->getSeccionesListening();

        $j = 0;
        foreach ($seccionesListening as $sr) {
            $questionSeccionListening = $sr->getQuestionsSeccionListenings();


            $audioUrl=$request->getBasePath()."/uploads/audio/";
            $audiourl="http://".$_SERVER['HTTP_HOST'].$audioUrl."Section01.mp3";
            $audio = file_get_contents($audiourl);
            $audioData = base64_encode($audio);
            $sound = "<audio id='audio_player' src='data:audio/mp3;base64,".$audioData."'  controls='controls' autobuffer='autobuffer'></audio>";

            $audioSeccion[$j]=$sound;

            $k = 0;
            foreach ($questionSeccionListening as $objetoQ) {

                if ($objetoQ->getTipoPregunta() == 1) {
                    $item_True_False = $objetoQ->getItemsTrueFalse();

                    $indzTF = 0;
                    foreach ($item_True_False as $objeto) {
                        $textoTF = 'p' . '_r' . '_s' . $j . '_q' . $k . '_tf' . $indzTF;
                        $builder->add($textoTF, ChoiceType :: class, array(
                            'choices' => array('0'=>'True','1'=>'False','2'=>'Not Given'),
                            'expanded' => true,
                            'multiple' => false,
                            'required'    => false,
                        ));
                        $indzTF++;
                    }

                } else if ($objetoQ->getTipoPregunta() == 2) {
                    $item_Simple_Selection = $objetoQ->getItemsSimpleSelection();

                    $indzSS = 0;
                    foreach ($item_Simple_Selection as $objeto) {
                        $inciso_Simple_Selection = $objeto->getIncisosSimpleSelection();
                        $arrayValoresSS = array();
                        $indxSS = 0;
                        foreach ($inciso_Simple_Selection as $v) {
                            $arrayValoresSS[$v->getOrdenInciso()] = $v->getTextoOpcion();
                            $indxSS++;
                        }
                        $textoSS = 'p' . '_r' . '_s' . $j . '_q' . $k . '_ss' . $indzSS;
                        //$builder  -> add ( $textoSS , TextType :: class );
                        $builder->add($textoSS, ChoiceType :: class, array(
                            'choices' => $arrayValoresSS,
                            'expanded' => true,
                            'multiple' => false,
                            'required'    => false,
                        ));

                        $indzSS++;
                    }

                } else if ($objetoQ->getTipoPregunta() == 3) {
                    $item_Multiple_Selection = $objetoQ->getIncisosMultipleSelection();

                    $indzSM = 0;
                    foreach ($item_Multiple_Selection as $objeto) {
                        // $j-indice de la seccion
                        // $k-indice de la pregunta
                        // $indzSM-indice del inciso
                        $arrayValoresSM[0] = $objeto->getTextoOpcion();

                        $textoSM = 'p' . '_r' . '_s' . $j . '_q' . $k . '_sm' . $indzSM;
                        $builder->add($textoSM, ChoiceType :: class, array(
                            'choices' => $arrayValoresSM,
                            'expanded' => true,
                            'multiple' => true,
                            'required' => false,
                        ));
                        $indzSM++;
                    }

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
                    foreach ($item_List_Selections as $objeto) {
                        $textoLS = 'p' . '_r' . '_s' . $j . '_q' . $k . '_ls' . $indzLS;

                        $builder->add($textoLS, ChoiceType :: class, array(
                            'choices' => $arrayValoresLS,
                            'required'    => false,
                        ));
                        $indzLS++;
                    }

                } else if ($objetoQ->getTipoPregunta() == 5) {
                    $item_Complete = $objetoQ->getItemsComplete();

                    $indzC = 0;
                    foreach ($item_Complete as $objeto) {
                        $textoC = 'p' . '_r' . '_s' . $j . '_q' . $k . '_c' . $indzC;
                        $builder->add($textoC, TextType :: class, array(
                            'required'    => false,
                        ));
                        $indzC++;
                    }
                }
                $k++;
            }
            $j++;
        }

        $builder->add('send', SubmitType :: class);
        $form = $builder->getForm();

        //$form = $this->createForm(new EvaluacionPruebaType(), $evalprueba);
        $form->handleRequest($request);

        if ($form->isSubmitted()) { //&& $form->isValid()
            $em = $this->getDoctrine()->getManager();
            $data = $form->getData();

            $user = $this->getUser();

            $seccionesListening = $r->getSeccionesListening();

            $totalPuntos = 0;

            $j = 0;
            foreach ($seccionesListening as $sr) {
                $questionSeccionListening = $sr->getQuestionsSeccionListenings();

                $k = 0;
                foreach ($questionSeccionListening as $objetoQ) {

                    if ($objetoQ->getTipoPregunta() == 1) {
                        $item_True_False = $objetoQ->getItemsTrueFalse();
                        $indzTF = 0;
                        foreach ($item_True_False as $objeto) {
                            $textoTF = 'p' . '_r' . '_s' . $j . '_q' . $k . '_tf' . $indzTF;
                            // $builder->add($textoTF, TextType :: class);
                            //--- ALAIN
                            $puntos = 0;
                            if ($objeto->getOpcionCorrecta() == $data[$textoTF]) {
                                $puntos = 1;
                            }
                            $totalPuntos += $puntos;

                            $respuestaTF = new Respuesta_True_False();
                            $respuestaTF->setIdEstudiante($user);
                            $respuestaTF->setIdItem($objeto);
                            $respuestaTF->setRespuesta($data[$textoTF]);
                            $respuestaTF->setPuntos($puntos);

                            $em->persist($respuestaTF);
                            $em->flush();

                            $indzTF++;
                            //----- ALAIN
                        }

                    } else if ($objetoQ->getTipoPregunta() == 2) {
                        $item_Simple_Selection = $objetoQ->getItemsSimpleSelection();

                        $indzSS = 0;
                        foreach ($item_Simple_Selection as $objeto) {
                            $textoSS = 'p' . '_r' . '_s' . $j . '_q' . $k . '_ss' . $indzSS;
                            $puntos = 0;
                            if ($objeto->getOpcionCorrecta() == $data[$textoSS]) {
                                $puntos = 1;
                            }
                            $totalPuntos += $puntos;

                            $respuestaSS = new Respuesta_Simple_Selection();
                            $respuestaSS->setIdEstudiante($user);
                            $respuestaSS->setIdItemSimpleSelection($objeto);
                            $respuestaSS->setRespuestaEstudiante($data[$textoSS]);
                            $respuestaSS->setPuntos($puntos);

                            $em->persist($respuestaSS);
                            $em->flush();

                            $indzSS++;
                        }

                    } else if ($objetoQ->getTipoPregunta() == 3) {
                        $item_Multiple_Selection = $objetoQ->getIncisosMultipleSelection();

                        $indzSM = 0;
                        foreach ($item_Multiple_Selection as $objeto) {
                            $textoSM = 'p' . '_r' . '_s' . $j . '_q' . $k . '_sm' . $indzSM;
                            $puntos = 0;
                            if ($objeto->getCorrectaInciso() == $data[$textoSM] && $objeto->getCorrectaInciso() == 1) {
                                $puntos = 1;
                            }
                            $totalPuntos += $puntos;

                            $respuesta = false;
                            if (true == $data[$textoSM]) {
                                $respuesta = true;
                            }
//							echo $puntos;
                            $respuestaSM = new Respuesta_Multiple_Selection();
                            $respuestaSM->setIdEstudiante($user);
                            $respuestaSM->setIdIncisoMultipleSelection($objeto);
                            $respuestaSM->setRespuestaEstudiante($respuesta);
                            $respuestaSM->setPuntos($puntos);

                            $em->persist($respuestaSM);
                            $em->flush();

                            $indzSM++;
                        }

                    } else if ($objetoQ->getTipoPregunta() == 4) {
                        $item_List_Selections = $objetoQ->getItemsListSelection();

                        $indzLS = 0;
                        foreach ($item_List_Selections as $objeto) {
                            $textoLS = 'p' . '_r' . '_s' . $j . '_q' . $k . '_ls' . $indzLS;
                            $puntos = 0;
                            if ($objeto->getOpcionCorrecta() == $data[$textoLS]) {
                                $puntos = 1;
                            }
                            $totalPuntos += $puntos;

                            $respuestaLS = new Respuesta_List_Selection();
                            $respuestaLS->setIdEstudiante($user);
                            $respuestaLS->setIdItemListSelection($objeto);
                            $respuestaLS->setRespuestaEstudiante($data[$textoLS]);
                            $respuestaLS->setPuntos($puntos);

                            $em->persist($respuestaLS);
                            $em->flush();

                            $indzLS++;
                        }

                    } else if ($objetoQ->getTipoPregunta() == 5) {
                        $item_Complete = $objetoQ->getItemsComplete();

                        $indzC = 0;
                        foreach ($item_Complete as $objeto) {
                            $textoC = 'p' . '_r' . '_s' . $j . '_q' . $k . '_c' . $indzC;
                            $puntos = 0;
                            if (Funciones_Completa::Completa_Comprueba_Respuesta($objeto->getRespuestaCorrecta(),$data[$textoC])){
                            //if ($objeto->getRespuestaCorrecta() == $data[$textoC]) {
                                $puntos = 1;
                            }
                            $totalPuntos += $puntos;

                            $respuestaC = new Respuesta_Completa();
                            $respuestaC->setIdEstudiante($user);
                            $respuestaC->setIdCompleta($objeto);
                            $respuestaC->setRespuestaEstudiante($data[$textoC]);
                            $respuestaC->setPuntos($puntos);

                            $em->persist($respuestaC);
                            $em->flush();

                            $indzC++;
                        }
                    }
                    $k++;
                }
                $j++;
            }




            $evaluacionTest = $em->getRepository('AppBundle:Evaluaciones')->findOneBy(array('id_test' => $test, 'id_estudiante' => $user));
            //if($evaluacionTest){
            //    $evaluacionTest->setPuntosListening($puntos);
            //    $em->persist($evaluacionTest);
            //    $em->flush();
            //}else{
                $evaluacionTest = new Evaluaciones();
                $evaluacionTest->setIdEstudiante($user);
                $evaluacionTest->setIdTest($test);
                $evaluacionTest->setPuntosListening($puntos);
                $em->persist($evaluacionTest);
                $em->flush();
            //}




            //return new Response("OK");
            return $this->render('global/pruebaenviada.html.twig', array(
            ));
            //return $this->redirectToRoute("app_list_pregunta");
        }

        $comienzo = new \DateTime('now - 1 hour');
        $fin = new \DateTime('now - 10 minutes');


        return $this->render('test/evaluacion/shownewevallistening.html.twig', array(
            'form' => $form->createView(),
            'prueba' => $test,
            'comienzo' => $comienzo->format('H:i'),
            'fin'=> $fin->format('H:i'),
            'testeo'=> true,
            'zonahoraria'=>$this->container->getParameter('zona_horaria'),
            'audioSeccion'=> $audioSeccion
            // 'estudiante'=>$usuarios,

        ));
    }

    /**
     * Lists all Test entities.
     *
     * @Route("/list_listening", name="list_listening")
     * @Method({"GET","POST"})
     */
    public function listExamenListeningAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        //$test = $em->getRepository('AppBundle:Test')->findReadyAll();
        //$test = $em->getRepository('AppBundle:Test')->findBy(array('deprueba'=>0));
        $consulta = $em->createQuery('SELECT t FROM AppBundle:Test t
JOIN t.id_listening l WHERE t.deprueba = 0 AND l.fecha < :fecha1  AND l.fecha >= :fecha2 ORDER BY l.fecha ASC');

        $consulta->setParameter('fecha1', new \DateTime('today'));
        $consulta->setParameter('fecha2', new \DateTime('yesterday'));
        $test = $consulta->getResult();

        $fecha = new \DateTime();
        $fecha -> setTimezone(new \DateTimeZone( $this->container->getParameter('zona_horaria')));
        //$fecha -> setTimezone(new \DateTimeZone('America/Caracas'));

        //$fecha = $fecha->format("Y-m-d-H-i");
        $anno_actual = $fecha->format('Y');
        $mes_actual = $fecha->format('m');
        $dia_actual = $fecha->format('d');
        $hora_actual = $fecha->format('H');
        $minuto_actual = $fecha->format('i');
        $segundo_actual = $fecha->format('s');


        //$comienzo = new \DateTime('now - 1 hour');
        //$fin = new \DateTime('now - 150 minutes');

        return $this->render('test/evaluacion/showlistlistening.html.twig', array(
            //'form' => $form->createView(),
            'pruebas' => $test,
            'anno_actual'=>$anno_actual,
            'mes_actual'=>$mes_actual,
            'dia_actual'=>$dia_actual,
            'hora_actual'=>$hora_actual,
            'minuto_actual'=>$minuto_actual,
            'segundo_actual'=>$segundo_actual,
            //'fin'=> $fin->format('H:i'),

            'zonahoraria'=>$this->container->getParameter('zona_horaria'),
            'testeo'=> true
            // 'estudiante'=>$usuarios,
        ));
    }

    /**
     * Lists all Test entities.
     *
     * @Route("/list_listening_practice", name="list_listening_practice")
     * @Method({"GET","POST"})
     */
    public function listExamenListeningDPAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        //$test = $em->getRepository('AppBundle:Test')->findReadyAll();
        //$test = $em->getRepository('AppBundle:Test')->findBy(array('deprueba'=>1));
        //$usuarios = $em->getRepository('AppBundle:User')->find(1) ;
        $consulta = $em->createQuery('SELECT t FROM AppBundle:Test t
JOIN t.id_listening l WHERE t.deprueba = 1 AND l.fecha < :fecha1  AND l.fecha >= :fecha2 ORDER BY l.fecha ASC');

        $consulta->setParameter('fecha1', new \DateTime('today'));
        $consulta->setParameter('fecha2', new \DateTime('yesterday'));
        $test = $consulta->getResult();

        $fecha = new \DateTime();
        $fecha -> setTimezone(new \DateTimeZone( $this->container->getParameter('zona_horaria')));
        //$fecha -> setTimezone(new \DateTimeZone('America/Caracas'));

        //$fecha = $fecha->format("Y-m-d-H-i");
        $anno_actual = $fecha->format('Y');
        $mes_actual = $fecha->format('m');
        $dia_actual = $fecha->format('d');
        $hora_actual = $fecha->format('H');
        $minuto_actual = $fecha->format('i');
        $segundo_actual = $fecha->format('s');


        //$comienzo = new \DateTime('now - 1 hour');
        //$fin = new \DateTime('now - 150 minutes');

        return $this->render('test/evaluacion/showlistlistening.html.twig', array(
            //'form' => $form->createView(),
            'pruebas' => $test,
            'anno_actual'=>$anno_actual,
            'mes_actual'=>$mes_actual,
            'dia_actual'=>$dia_actual,
            'hora_actual'=>$hora_actual,
            'minuto_actual'=>$minuto_actual,
            'segundo_actual'=>$segundo_actual,
            //'fin'=> $fin->format('H:i'),

            'zonahoraria'=>$this->container->getParameter('zona_horaria'),
            'testeo'=> true
            // 'estudiante'=>$usuarios,
        ));
    }

}