<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * B_Section_Reading
 *
 * @ORM\Table(name="b__section__reading")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\B_Section_ReadingRepository")
 */
class B_Section_Reading
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
     * @ORM\Column(name="orden_seccion", type="string", length=15)
     */
    private $ordenSeccion;

    /**
     * @var string
     *
     * @ORM\Column(name="texto_instruccion", type="text")
     */
    private $textoInstruccion;


    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\B_Question" , mappedBy="id_seccion_reading", cascade={"all"}, orphanRemoval=true)
     */
    private $questions_seccion_readings;

    /**
     *@ORM\ManyToOne(targetEntity="User", inversedBy="breading")
     *@ORM\JoinColumn(name="id_profesor", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    private $id_profesor;


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
     * Constructor
     */
    public function __construct()
    {
        $this->questions_seccion_readings = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set ordenSeccion
     *
     * @param string $ordenSeccion
     * @return B_Section_Reading
     */
    public function setOrdenSeccion($ordenSeccion)
    {
        $this->ordenSeccion = $ordenSeccion;

        return $this;
    }

    /**
     * Get ordenSeccion
     *
     * @return string 
     */
    public function getOrdenSeccion()
    {
        return $this->ordenSeccion;
    }

    /**
     * Set textoInstruccion
     *
     * @param string $textoInstruccion
     * @return B_Section_Reading
     */
    public function setTextoInstruccion($textoInstruccion)
    {
        $this->textoInstruccion = $textoInstruccion;

        return $this;
    }

    /**
     * Get textoInstruccion
     *
     * @return string 
     */
    public function getTextoInstruccion()
    {
        return $this->textoInstruccion;
    }

    /**
     * Add questions_seccion_readings
     *
     * @param \AppBundle\Entity\B_Question $questionsSeccionReadings
     * @return B_Section_Reading
     */
    public function addQuestionsSeccionReading(\AppBundle\Entity\B_Question $questionsSeccionReadings)
    {
        $this->questions_seccion_readings[] = $questionsSeccionReadings;

        return $this;
    }

    /**
     * Remove questions_seccion_readings
     *
     * @param \AppBundle\Entity\B_Question $questionsSeccionReadings
     */
    public function removeQuestionsSeccionReading(\AppBundle\Entity\B_Question $questionsSeccionReadings)
    {
        $this->questions_seccion_readings->removeElement($questionsSeccionReadings);
    }

    /**
     * Get questions_seccion_readings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getQuestionsSeccionReadings()
    {
        return $this->questions_seccion_readings;
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



}
