<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Recursos;
use AppBundle\Entity\Urlrecurso;
use AppBundle\Entity\User;
use AppBundle\Form\ResetPwFormType;
use AppBundle\Form\UserChangePasswordType;
use FOS\UserBundle\Form\Model\ChangePassword;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Exception\AccountStatusException;
use Symfony\Component\Security\Core\User\UserInterface;

//lolo
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $consulta = $em->createQuery('SELECT t FROM AppBundle:Test t
JOIN t.id_reading r WHERE t.deprueba = 0 AND r.fecha < :fecha1  AND r.fecha >= :fecha2 ORDER BY r.fecha ASC');
        $consulta->setParameter('fecha1', new \DateTime('today'));
        $consulta->setParameter('fecha2', new \DateTime('yesterday'));
        $testsr = $consulta->getResult();

        $consulta = $em->createQuery('SELECT t FROM AppBundle:Test t
JOIN t.id_listening l WHERE t.deprueba = 0 AND l.fecha < :fecha1  AND l.fecha >= :fecha2 ORDER BY l.fecha ASC');
        $consulta->setParameter('fecha1', new \DateTime('today'));
        $consulta->setParameter('fecha2', new \DateTime('yesterday'));
        $testsl = $consulta->getResult();

        $consulta = $em->createQuery('SELECT t FROM AppBundle:Test t
JOIN t.id_reading r WHERE t.deprueba = 1 AND r.fecha < :fecha1  AND r.fecha >= :fecha2 ORDER BY r.fecha ASC');
        $consulta->setParameter('fecha1', new \DateTime('today'));
        $consulta->setParameter('fecha2', new \DateTime('yesterday'));
        $depruebasr = $consulta->getResult();

        $consulta = $em->createQuery('SELECT t FROM AppBundle:Test t
JOIN t.id_listening l WHERE t.deprueba = 1 AND l.fecha < :fecha1  AND l.fecha >= :fecha2 ORDER BY l.fecha ASC');
        $consulta->setParameter('fecha1', new \DateTime('today'));
        $consulta->setParameter('fecha2', new \DateTime('yesterday'));
        $depruebasl = $consulta->getResult();

        //$tests = $em->getRepository('AppBundle:Test')->findBy(array('deprueba'=>0));
        //$depruebas = $em->getRepository('AppBundle:Test')->findBy(array('deprueba'=>1));
        /*$query = $em->createQuery('
        SELECT COUNT(p.id)
        FROM AppBundle:Test p
        ');
        $tests = $query->getResult();
*/
        return $this->render('test/evaluacion/index.html.twig', array(
            //'form' => $form->createView(),
            'pruebasr' => $testsr,
            'pruebasl' => $testsl,
            'depruebasr' => $depruebasr,
            'depruebasl' => $depruebasl,
            //'comienzo' => $comienzo->format('H:i'),
            //'fin'=> $fin->format('H:i'),
            //'testeo'=> true,
            //'audioSeccion'=> $audioSeccion
            // 'estudiante'=>$usuarios,

        ));
        // replace this example code with whatever you need
        //return $this->render('default/index.html.twig', array(
        //    'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
        //));
    }


    /**
     * @Route("/postacceptort/{type}/{id}", name="postacceptort")
     * @Method({"GET", "POST"})
     */
    public function postacceptortAction(Request $r, $type, $id){
        /*********************************************
         * Change this line to set the upload folder *
         *********************************************/
        //esta carpeta la obtenemos del config, aqui definimos donde queremos las imagenes
        $carpeta=$this->container->getParameter('images_location');

        //$imageFolder = __DIR__.'/../../../../web/uploads/images/';
        $imageFolder = $this->container->getParameter('directorio.imagenes').$carpeta."/";

        $imageUrl=$r->getBasePath()."/".$carpeta."/";

        reset ($_FILES);
        $temp = current($_FILES);
        //echo json_encode(array('location' => 'http://localhost/images/blobid1506221658855.jpg'));
        if (is_uploaded_file($temp['tmp_name'])){
            // Sanitize input
            if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
                header("HTTP/1.0 500 Invalid file name.");
                return;
            }

            // Verify extension
            if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png"))) {
                header("HTTP/1.0 500 Invalid extension.");
                return;
            }

            $fechaa = new \DateTime("now");
            $date1 = $fechaa->format('Y')
                .($fechaa->format('m'))
                .$fechaa->format('d')
                .($fechaa->format('H'))
                .($fechaa->format('i'))
                .$fechaa->format('s');
            // Accept upload if there was no origin, or if it is an accepted origin
            $filetowrite = $imageFolder.$date1.'_'.$temp['name'];
            move_uploaded_file($temp['tmp_name'], $filetowrite);

            $imagenurl="http://".$_SERVER['HTTP_HOST'].$imageUrl.$date1.'_'.$temp['name'];

            $em = $this->getDoctrine()->getManager();

            $urlrecurso = new Urlrecurso();
            $urlrecurso->setUrl($filetowrite);
            $em->persist($urlrecurso);
            $em->flush();


            $recurso = new Recursos();

            if($type == 't'){
                $recurso->setIdTest($id);
                //$recurso->setIdTest(-1);
                $recurso->setIdBreading('-1');
                $recurso->setIdBlistening('-1');
            }else if($type == 'r'){
                $recurso->setIdBreading($id);
                $recurso->setIdTest('-1');
                //$recurso->setIdBreading(-1);
                $recurso->setIdBlistening('-1');
            }else if($type == 'l'){
                $recurso->setIdBlistening($id);
                $recurso->setIdTest('-1');
                $recurso->setIdBreading('-1');
                //$recurso->setIdBlistening(-1);
            }
            $recurso->setIdRecurso($urlrecurso->getId());

            $em->persist($recurso);
            $em->flush();

            echo json_encode(array('location' => $imagenurl));
            //echo json_encode(array('location' => 'http://localhost/images/blobid1506221658855.jpg'));

        } else {
            // Notify editor that the upload failed
            header("HTTP/1.0 500 Server");
        }
        return new Response('');

    }




    /**
     * @Route("/loadAudio/{type}/{id}", name="loadaudio")
     *     @Method({"GET", "POST"})
     */
    public function loadAction(Request $r, $type, $id)
    {

        $fechaa = new \DateTime("now");
        $date1 = $fechaa->format('Y')
            .($fechaa->format('m'))
            .$fechaa->format('d')
            .($fechaa->format('H'))
            .($fechaa->format('i'))
            .$fechaa->format('s');

        //$uploaddir = '/var/www/uploads/';
        $uploaddir = $this->getParameter('audio_directory').'/';
        $curr = current($_FILES);
        $uploadfile = $uploaddir. $date1.'_'.basename($curr['name']);

        $val = 'lolo';
        $tipo_archivo = $curr['type'];
        $tamano_archivo = $curr['size'];
        if(! is_dir ( $uploaddir) ){
            if (!mkdir($uploaddir, 0777, true)) {
                return new Response("Error, creación del directorio ".$uploaddir." no permitido");
            }
        }


        if($tamano_archivo < 100000000) {



                if (move_uploaded_file($curr['tmp_name'], $uploadfile)) {
                    // return $this->render('AppBundle:Default:product/ok.html.twig');
                    $audioUrl = $r->getBasePath() . "/uploads/audio/";
                    $audiourl = "http://" . $_SERVER['HTTP_HOST'] . $audioUrl .''.$date1.'_'.$curr['name'];

                    $em = $this->getDoctrine()->getManager();

                    $urlrecurso = new Urlrecurso();
                    $urlrecurso->setUrl($uploadfile);
                    $em->persist($urlrecurso);
                    $em->flush();


                    $recurso = new Recursos();
                    $recurso->setIdRecurso($urlrecurso->getId());

                    if($type == 't'){
                        $recurso->setIdTest($id);
                        //$recurso->setIdTest(-1);
                        $recurso->setIdBreading('-1');
                        $recurso->setIdBlistening('-1');
                    }else if($type == 'r'){
                        $recurso->setIdBreading($id);
                        $recurso->setIdTest('-1');
                        //$recurso->setIdBreading(-1);
                        $recurso->setIdBlistening('-1');
                    }else if($type == 'l'){
                        $recurso->setIdBlistening($id);
                        $recurso->setIdTest('-1');
                        $recurso->setIdBreading('-1');
                        //$recurso->setIdBlistening(-1);
                    }

                    $em->persist($recurso);
                    $em->flush();

                    return new Response($audiourl);
                } else {

                    // Notify editor that the upload failed
                    $message = "No fue posible mover el archivo a la siguiente ruta " . $uploadfile;
                }

        }else{
            $message = "El archivo excede el tamaño de archivo máximo permitido que es de 10 Mb";
        }
        //return $this->render('AppBundle:Default:product/ko.html.twig',array('nombre' => $curr['name']));
        return new Response($message);

    }

    /**
     * @Route("/myrequest/", name="myrequest")
     *     @Method({"GET", "POST"})
     */
    public function myrequestAction(Request $r)
    {

        $task = new User();
        $form = $this->createForm('AppBundle\Form\UserPINType', $task);

        $form->handleRequest($r);
        if ($form->isSubmitted() && $form->isValid()) {
            // ... perform some action, such as saving the task to the database
            //$nombre = $form->get('nombre')->getData();
            $username = $task->getUsername();
            $pin = $task->getPin();
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('AppBundle:User')->findOneBy(array('username' => $username, 'pin'=> $pin));
            if ($user != null) {
                //return $this->render('FOSUserBundle:Resetting:ok.html.twig', array('nombre' => $nombre));
                //$this->get('security.context')->getToken()->setUser(new User($user));
                //return $this->redirectToRoute('fos_user_change_password');
                //return $this->redirectToRoute('ok');
                $r->getSession()->set('usuario', $user->getUsername());
                return $this->redirectToRoute('myreset',array(
                ));
                //$response = new RedirectResponse($this->container->get('router')->generate('fos_user_change_password'));

                /*$this->container->get('fos_user.security.login_manager')->loginUser(
                    $this->container->getParameter('fos_user.firewall_name'),
                    $user,
                    $response);*/

                //return $response;
            }else{
                return $this->render('FOSUserBundle:Resetting:myrequest.html.twig', array(
                    'form' => $form->createView(),
                ));
            }
        }
        return $this->render('FOSUserBundle:Resetting:myrequest.html.twig', array(
            'form' => $form->createView(),
        ));

    }


    /**
     * @Route("/myreset/", name="myreset")
     *     @Method({"GET", "POST"})
     */
    public function myresetAction(Request $r)
    {

        $task = new ChangePassword();
        $form = $this->createForm('AppBundle\Form\MyResettingFormType', $task);

        $form->handleRequest($r);
        if ($form->isSubmitted() && $form->isValid()) {
            // ... perform some action, such as saving the task to the database
            //$nombre = $form->get('nombre')->getData();

            if($r->getSession()->get('usuario') && $r->getSession()->get('usuario')!=''){
                $username = $r->getSession()->get('usuario');
                $em = $this->getDoctrine()->getManager();
                $user = $em->getRepository('AppBundle:User')->findOneByUsername($username);
                if ($user != null) {
                    //return $this->render('FOSUserBundle:Resetting:ok.html.twig', array('nombre' => $nombre));
                    //$this->get('security.context')->getToken()->setUser(new User($user));
                    //return $this->redirectToRoute('fos_user_change_password');
                    $newpassword = $form->get('new')->getData();

                    $r->getSession()->set('usuario', '');
                    $encoder = $this->get('security.encoder_factory')
                        ->getEncoder($user);
                    $password = $encoder->encodePassword(
                        $newpassword,
                        $user->getSalt()
                    );

                    $user->setPassword($password);
                    $em->persist($user);
                    $em->flush();
                    return $this->redirectToRoute('fos_user_security_login');
                    /*
                    return $this->render('FOSUserBundle:Resetting:ok.html.twig', array(
                        'user' => $user,
                    ));
                    */
                    //$response = new RedirectResponse($this->container->get('router')->generate('fos_user_change_password'));

                    /*$this->container->get('fos_user.security.login_manager')->loginUser(
                        $this->container->getParameter('fos_user.firewall_name'),
                        $user,
                        $response);*/

                    //return $response;
                }else{
                    return $this->render('FOSUserBundle:Resetting:myreset.html.twig', array(
                        'form' => $form->createView(),
                    ));
                }
            }else{
                return $this->render('FOSUserBundle:Resetting:myreset.html.twig', array(
                    'form' => $form->createView(),
                ));
            }
        }
        return $this->render('FOSUserBundle:Resetting:myreset.html.twig', array(
            'form' => $form->createView(),
        ));
    }


    /**
     * Lists all Test entities.
     *
     * @Route("/ok", name="ok")
     * @Method("GET")
     */
    public function okAction()
    {
        //$em = $this->getDoctrine()->getManager();

        // $tests = $em->getRepository('AppBundle:Test')->findAll();

        return $this->render('FOSUserBundle:Resetting:ok.html.twig', array(
            'user' => 'user',
        ));
    }
}
