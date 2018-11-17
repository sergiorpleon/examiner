<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Entity\User as UserFOS;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User extends UserFOS
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=600, nullable=true)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="anno_curso", type="string", length=600, nullable=true)
     */
    private $anno_curso;

    /**
     * @var string
     *
     * @ORM\Column(name="pin", type="string", length=600, nullable=true)
     */
    private $pin;

    /**
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Respuesta_True_False" , mappedBy="id_estudiante", cascade={"all"}, orphanRemoval=true)
     */
    private $respuesta_true_false;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Respuesta_Simple_Selection" , mappedBy="id_estudiante", cascade={"all"}, orphanRemoval=true)
     */
    private $respuesta_simple_selection;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Respuesta_List_Selection" , mappedBy="id_estudiante", cascade={"all"}, orphanRemoval=true)
     */
    private $respuesta_list_selection;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Respuesta_Multiple_Selection" , mappedBy="id_estudiante", cascade={"all"}, orphanRemoval=true)
     */
    private $respuesta_multiple_selection;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Respuesta_Completa" , mappedBy="id_estudiante", cascade={"all"}, orphanRemoval=true)
     */
    private $respuesta_completa;


    /**
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Test" , mappedBy="id_profesor", cascade={"all"}, orphanRemoval=true)
     */
    private $tests;

    /**
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Evaluaciones" , mappedBy="id_estudiante", cascade={"persist"}, orphanRemoval=true)
     */
    private $evaluaciones;


    /**
     *@ORM\ManyToOne(targetEntity="Estudia", inversedBy="id_user")
     *@ORM\JoinColumn(name="id_estudia", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    private $id_estudia;


    public function __construct()
    {
        //parent::__construct();
        // your own logic
        $this->addRole('ROLE_USER');
        $this->setEnabled(true);
        //$this->locked = false;
        //$this->expired = false;
        //$this->roles = array();
        //$this->credentialsExpired = false;

    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return User
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    public function __toString()
    {
        return $this->getNombre();
    }

    /**
     * Set anno_curso
     *
     * @param string $anno_curso
     * @return User
     */
    public function setAnnoCurso($anno_curso)
    {
        $this->anno_curso = $anno_curso;

        return $this;
    }

    /**
     * Get anno_curso
     *
     * @return string
     */
    public function getAnnoCurso()
    {
        return $this->anno_curso;
    }

    /**
     * Set pin
     *
     * @param string $pin
     * @return User
     */
    public function setPin($pin)
    {
        $this->pin = $pin;

        return $this;
    }

    /**
     * Get pin
     *
     * @return string
     */
    public function getPin()
    {
        return $this->pin;
    }


    /**
     * Add respuesta_list_selection
     *
     * @param \AppBundle\Entity\Respuesta_List_Selection $respuestaListSelection
     * @return User
     */
    public function addRespuestaListSelection(\AppBundle\Entity\Respuesta_List_Selection $respuestaListSelection)
    {
        $this->respuesta_list_selection[] = $respuestaListSelection;

        return $this;
    }

    /**
     * Remove respuesta_list_selection
     *
     * @param \AppBundle\Entity\Respuesta_List_Selection $respuestaListSelection
     */
    public function removeRespuestaListSelection(\AppBundle\Entity\Respuesta_List_Selection $respuestaListSelection)
    {
        $this->respuesta_list_selection->removeElement($respuestaListSelection);
    }

    /**
     * Get respuesta_list_selection
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRespuestaListSelection()
    {
        return $this->respuesta_list_selection;
    }

    /**
     * Add tests
     *
     * @param \AppBundle\Entity\Test $tests
     * @return User
     */
    public function addTest(\AppBundle\Entity\Test $tests)
    {
        $this->tests[] = $tests;

        return $this;
    }

    /**
     * Remove tests
     *
     * @param \AppBundle\Entity\Test $tests
     */
    public function removeTest(\AppBundle\Entity\Test $tests)
    {
        $this->tests->removeElement($tests);
    }

    /**
     * Get tests
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTests()
    {
        return $this->tests;
    }

    /**
     * Add evaluaciones
     *
     * @param \AppBundle\Entity\Evaluaciones $evaluaciones
     * @return User
     */
    public function addEvaluacione(\AppBundle\Entity\Evaluaciones $evaluaciones)
    {
        $this->evaluaciones[] = $evaluaciones;

        return $this;
    }

    /**
     * Remove evaluaciones
     *
     * @param \AppBundle\Entity\Evaluaciones $evaluaciones
     */
    public function removeEvaluacione(\AppBundle\Entity\Evaluaciones $evaluaciones)
    {
        $this->evaluaciones->removeElement($evaluaciones);
    }

    /**
     * Get evaluaciones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEvaluaciones()
    {
        return $this->evaluaciones;
    }

    /**
     * Add respuesta_true_false
     *
     * @param \AppBundle\Entity\Respuesta_True_False $respuestaTrueFalse
     * @return User
     */
    public function addRespuestaTrueFalse(\AppBundle\Entity\Respuesta_True_False $respuestaTrueFalse)
    {
        $this->respuesta_true_false[] = $respuestaTrueFalse;

        return $this;
    }

    /**
     * Remove respuesta_true_false
     *
     * @param \AppBundle\Entity\Respuesta_True_False $respuestaTrueFalse
     */
    public function removeRespuestaTrueFalse(\AppBundle\Entity\Respuesta_True_False $respuestaTrueFalse)
    {
        $this->respuesta_true_false->removeElement($respuestaTrueFalse);
    }

    /**
     * Get respuesta_true_false
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRespuestaTrueFalse()
    {
        return $this->respuesta_true_false;
    }

    /**
     * Add respuesta_simple_selection
     *
     * @param \AppBundle\Entity\Respuesta_Simple_Selection $respuestaSimpleSelection
     * @return User
     */
    public function addRespuestaSimpleSelection(\AppBundle\Entity\Respuesta_Simple_Selection $respuestaSimpleSelection)
    {
        $this->respuesta_simple_selection[] = $respuestaSimpleSelection;

        return $this;
    }

    /**
     * Remove respuesta_simple_selection
     *
     * @param \AppBundle\Entity\Respuesta_Simple_Selection $respuestaSimpleSelection
     */
    public function removeRespuestaSimpleSelection(\AppBundle\Entity\Respuesta_Simple_Selection $respuestaSimpleSelection)
    {
        $this->respuesta_simple_selection->removeElement($respuestaSimpleSelection);
    }

    /**
     * Get respuesta_simple_selection
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRespuestaSimpleSelection()
    {
        return $this->respuesta_simple_selection;
    }

    /**
     * Add respuesta_multiple_selection
     *
     * @param \AppBundle\Entity\Respuesta_Multiple_Selection $respuestaMultipleSelection
     * @return User
     */
    public function addRespuestaMultipleSelection(\AppBundle\Entity\Respuesta_Multiple_Selection $respuestaMultipleSelection)
    {
        $this->respuesta_multiple_selection[] = $respuestaMultipleSelection;

        return $this;
    }

    /**
     * Remove respuesta_multiple_selection
     *
     * @param \AppBundle\Entity\Respuesta_Multiple_Selection $respuestaMultipleSelection
     */
    public function removeRespuestaMultipleSelection(\AppBundle\Entity\Respuesta_Multiple_Selection $respuestaMultipleSelection)
    {
        $this->respuesta_multiple_selection->removeElement($respuestaMultipleSelection);
    }

    /**
     * Get respuesta_multiple_selection
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRespuestaMultipleSelection()
    {
        return $this->respuesta_multiple_selection;
    }

    /**
     * Add respuesta_completa
     *
     * @param \AppBundle\Entity\Respuesta_Completa $respuestaCompleta
     * @return User
     */
    public function addRespuestaCompletum(\AppBundle\Entity\Respuesta_Completa $respuestaCompleta)
    {
        $this->respuesta_completa[] = $respuestaCompleta;

        return $this;
    }

    /**
     * Remove respuesta_completa
     *
     * @param \AppBundle\Entity\Respuesta_Completa $respuestaCompleta
     */
    public function removeRespuestaCompletum(\AppBundle\Entity\Respuesta_Completa $respuestaCompleta)
    {
        $this->respuesta_completa->removeElement($respuestaCompleta);
    }

    /**
     * Get respuesta_completa
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRespuestaCompleta()
    {
        return $this->respuesta_completa;
    }



    /**
     * Set id_estudia
     *
     * @param \AppBundle\Entity\Estudia $idEstudia
     * @return User
     */
    public function setIdEstudia(\AppBundle\Entity\Estudia $idEstudia = null)
    {
        $this->id_estudia = $idEstudia;

        return $this;
    }

    /**
     * Get id_estudia
     *
     * @return \AppBundle\Entity\Estudia 
     */
    public function getIdEstudia()
    {
        return $this->id_estudia;
    }
}
