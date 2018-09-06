<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Evaluaciones
 *
 * @ORM\Table(name="evaluaciones")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EvaluacionesRepository")
 */
class Evaluaciones
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
     * @var int
     *
     * @ORM\Column(name="puntos_reading", type="integer", nullable=true)
     */
    private $puntosReading;

    /**
     * @var int
     *
     * @ORM\Column(name="puntos_listening", type="integer", nullable=true)
     */
    private $puntosListening;

    /**
     * @var string
     *
     * @ORM\Column(name="banda", type="string", length=255, nullable=true)
     */
    private $banda;

    /**
     *@ORM\ManyToOne(targetEntity="User", inversedBy="evaluaciones")
     *@ORM\JoinColumn(name="id_estudiante", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    private $id_estudiante;

    /**
     *@ORM\ManyToOne(targetEntity="Test", inversedBy="evaluaciones")
     *@ORM\JoinColumn(name="id_test", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    private $id_test;


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
     * Set puntosListening
     *
     * @param integer $puntosListening
     * @return Evaluaciones
     */
    public function setTiempo($puntosListening)
    {
        $this->puntosListening = $puntosListening;

        return $this;
    }

    /**
     * Get puntosListening
     *
     * @return integer 
     */
    public function getPuntosListening()
    {
        return $this->puntosListening;
    }

    /**
     * Set puntosReading
     *
     * @param integer $puntosReading
     * @return Evaluaciones
     */
    public function setPuntosReading($puntosReading)
    {
        $this->puntosReading = $puntosReading;

        return $this;
    }

    /**
     * Get puntosReading
     *
     * @return integer 
     */
    public function getPuntosReading()
    {
        return $this->puntosReading;
    }

    /**
     * Set banda
     *
     * @param string $banda
     * @return Evaluaciones
     */
    public function setBanda($banda)
    {
        $this->banda = $banda;

        return $this;
    }

    /**
     * Get banda
     *
     * @return string 
     */
    public function getBanda()
    {
        return $this->banda;
    }


    /**
     * Set id_estudiante
     *
     * @param \AppBundle\Entity\User $idEstudiante
     * @return Evaluaciones
     */
    public function setIdEstudiante(\AppBundle\Entity\User $idEstudiante = null)
    {
        $this->id_estudiante = $idEstudiante;

        return $this;
    }

    /**
     * Get id_estudiante
     *
     * @return \AppBundle\Entity\User 
     */
    public function getIdEstudiante()
    {
        return $this->id_estudiante;
    }

    /**
     * Set id_test
     *
     * @param \AppBundle\Entity\Test $idTest
     * @return Evaluaciones
     */
    public function setIdTest(\AppBundle\Entity\Test $idTest = null)
    {
        $this->id_test = $idTest;

        return $this;
    }

    /**
     * Get id_test
     *
     * @return \AppBundle\Entity\Test 
     */
    public function getIdTest()
    {
        return $this->id_test;
    }
}
