<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Test
 *
 * @ORM\Table(name="test")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TestRepository")
 */
class Test
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * @var string
     *
     * @ORM\Column(name="texto_orientacion", type="text", nullable=true)
     */
    private $textoOrientacion;

    /**
     * @var boolean
     *
     * @ORM\Column(name="deprueba", type="boolean", nullable=true)
     */
    private $deprueba;

    /**
     *@ORM\ManyToOne(targetEntity="Listening", inversedBy="tests")
     *@ORM\JoinColumn(name="id_listening", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    private $id_listening;


    /**
     *@ORM\ManyToOne(targetEntity="Reading", inversedBy="tests")
     *@ORM\JoinColumn(name="id_reading", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    private $id_reading;




    /**
     *@ORM\ManyToOne(targetEntity="User", inversedBy="tests")
     *@ORM\JoinColumn(name="id_profesor", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    private $id_profesor;

    /**
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Evaluaciones" , mappedBy="id_test", cascade={"persist"}, orphanRemoval=true)
     */
    private $evaluaciones;


    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\TestEstudia" , mappedBy="id_test", cascade={"all"}, orphanRemoval=true)
     */
    private $id_testestudia;


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
     * Set deprueba
     *
     * @param boolean $deprueba
     * @return Test
     */
    public function setDeprueba($deprueba)
    {
        $this->deprueba = $deprueba;

        return $this;
    }

    /**
     * Get deprueba
     *
     * @return string
     */
    public function getDeprueba()
    {
        return $this->deprueba;
    }

    /**
     * Set textoOrientacion
     *
     * @param string $textoOrientacion
     * @return Test
     */
    public function setTextoOrientacion($textoOrientacion)
    {
        $this->textoOrientacion = $textoOrientacion;

        return $this;
    }

    /**
     * Get textoOrientacion
     *
     * @return string 
     */
    public function getTextoOrientacion()
    {
        return $this->textoOrientacion;
    }



    /**
     * Set speaking
     *
     * @param integer $speaking
     * @return Test
     */
    public function setSpeaking($speaking)
    {
        $this->speaking = $speaking;

        return $this;
    }

    /**
     * Get speaking
     *
     * @return integer 
     */
    public function getSpeaking()
    {
        return $this->speaking;
    }

    /**
     * Set writing
     *
     * @param integer $writing
     * @return Test
     */
    public function setWriting($writing)
    {
        $this->writing = $writing;

        return $this;
    }

    /**
     * Get writing
     *
     * @return integer 
     */
    public function getWriting()
    {
        return $this->writing;
    }

    /**
     * Set id_listening
     *
     * @param \AppBundle\Entity\Listening $idListening
     * @return Test
     */
    public function setIdListening(\AppBundle\Entity\Listening $idListening = null)
    {
        $this->id_listening = $idListening;

        return $this;
    }

    /**
     * Get id_listening
     *
     * @return \AppBundle\Entity\Listening 
     */
    public function getIdListening()
    {
        return $this->id_listening;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->evaluaciones = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set id_profesor
     *
     * @param \AppBundle\Entity\User $idProfesor
     * @return Test
     */
    public function setIdProfesor(\AppBundle\Entity\User $idProfesor)
    {
        $this->id_profesor = $idProfesor;

        return $this;
    }

    /**
     * Get id_profesor
     *
     * @return \AppBundle\Entity\User 
     */
    public function getIdProfesor()
    {
        return $this->id_profesor;
    }

    /**
     * Add evaluaciones
     *
     * @param \AppBundle\Entity\Evaluaciones $evaluaciones
     * @return Test
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
     * Set id_reading
     *
     * @param \AppBundle\Entity\Reading $idReading
     * @return Test
     */
    public function setIdReading(\AppBundle\Entity\Reading $idReading = null)
    {
        $this->id_reading = $idReading;

        return $this;
    }

    /**
     * Get id_reading
     *
     * @return \AppBundle\Entity\Reading 
     */
    public function getIdReading()
    {
        return $this->id_reading;
    }

    /**
     * Add id_testestudia
     *
     * @param \AppBundle\Entity\TestEstudia $idTestestudia
     * @return Test
     */
    public function addIdTestestudium(\AppBundle\Entity\TestEstudia $idTestestudia)
    {
        $this->id_testestudia[] = $idTestestudia;

        return $this;
    }

    /**
     * Remove id_testestudia
     *
     * @param \AppBundle\Entity\TestEstudia $idTestestudia
     */
    public function removeIdTestestudium(\AppBundle\Entity\TestEstudia $idTestestudia)
    {
        $this->id_testestudia->removeElement($idTestestudia);
    }

    /**
     * Get id_testestudia
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdTestestudia()
    {
        return $this->id_testestudia;
    }
}
