<?php

namespace AppBundle\Controller;

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

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $tests = $em->getRepository('AppBundle:Test')->findBy(array('deprueba'=>0));
        $depruebas = $em->getRepository('AppBundle:Test')->findBy(array('deprueba'=>1));
        /*$query = $em->createQuery('
        SELECT COUNT(p.id)
        FROM AppBundle:Test p
        ');
        $tests = $query->getResult();
*/
        return $this->render('test/evaluacion/index.html.twig', array(
            //'form' => $form->createView(),
            'pruebas' => $tests,
            'depruebas' => $depruebas,
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
     * @Route("/postacceptor", name="postacceptor")
     * @Method({"GET", "POST"})
     */
    public function postacceptorAction(Request $r){

//echo $_SERVER["HTTP_HOST"];
//echo 'aa'.$_SERVER['HTTP_ORIGIN'];
        /*******************************************************
         * Only these origins will be allowed to upload images *
         ******************************************************/
        //$accepted_origins = array("http://localhost", "http://192.168.1.1","http://192.168.43.1", "http://192.168.43.73","http://example.com");

        /*********************************************
         * Change this line to set the upload folder *
         *********************************************/
        //esta carpeta la obtenemos del config, aqui definimos donde queremos las imagenes
        $carpeta=$this->container->getParameter('images_location');

        //$imageFolder = __DIR__.'/../../../../web/uploads/images/';
        $imageFolder = $this->container->getParameter('directorio.imagenes').$carpeta."/";
        //$imageFolder = $_SERVER["DOCUMENT_ROOT"]."/".$carpeta."/";
        //echo $this->getRootDir();
        //exit;
        //$imageUrl=$_SERVER["HTTP_HOST"]."/".$carpeta."/";
        $imageUrl=$r->getBasePath()."/".$carpeta."/";

        //$imageFolder = $_SERVER["DOCUMENT_ROOT"]."/images/images/";
        //$imageUrl=$_SERVER["HTTP_HOST"]."/images/images/";
        $imageUrl=$r->getBasePath()."/".$carpeta."/";




        /*
        echo $imageUrl;
        echo '<br>';
        echo $imageFolder;
        */
        reset ($_FILES);
        $temp = current($_FILES);
        //echo json_encode(array('location' => 'http://localhost/images/blobid1506221658855.jpg'));
        if (is_uploaded_file($temp['tmp_name'])){
            /*  if (isset($_SERVER['HTTP_ORIGIN'])) {
              // same-origin requests won't set an origin. If the origin is set, it must be valid.
              if (in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins)) {
                header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
              } else {
                header("HTTP/1.0 403 Origin Denied");
                return;
              }

            }*/

            /*
              If your script needs to receive cookies, set images_upload_credentials : true in
              the configuration and enable the following two headers.
            */
            //header('Access-Control-Allow-Credentials: true');
            //header('P3P: CP="There is no P3P policy."');

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

            // Accept upload if there was no origin, or if it is an accepted origin
            $filetowrite = $imageFolder . $temp['name'];
            move_uploaded_file($temp['tmp_name'], $filetowrite);

            $imagenurl="http://".$_SERVER['HTTP_HOST'].$imageUrl.$temp['name'];
            //$imagenurl=$r->getBasePath()."/".$carpeta."/".$temp['name'];
            // Respond to the successful upload with JSON.
            // Use a location key to specify the path to the saved image resource.
            // { location : '/your/uploaded/image/file'}
            echo json_encode(array('location' => $imagenurl));
            //echo json_encode(array('location' => 'http://localhost/images/blobid1506221658855.jpg'));

        } else {
            // Notify editor that the upload failed
            header("HTTP/1.0 500 Server");
        }
        return new Response('');

    }


    /**
     * @Route("/loadAudio/{id}", name="loadaudio")
     *     @Method({"GET", "POST"})
     */
    public function loadAction(Request $r, $id)
    {

        //$uploaddir = '/var/www/uploads/';
        $uploaddir = $this->getParameter('audio_directory').'/'.$id.'/';
        $curr = current($_FILES);
        $uploadfile = $uploaddir. basename($curr['name']);

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
                    $audioUrl = $r->getBasePath() . "/uploads/audio/".$id."/";
                    $audiourl = "http://" . $_SERVER['HTTP_HOST'] . $audioUrl . $curr['name'];
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
