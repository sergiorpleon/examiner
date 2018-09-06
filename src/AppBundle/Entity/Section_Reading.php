<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Section_Reading
 *
 * @ORM\Table(name="section__reading")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Section_ReadingRepository")
 */
class Section_Reading
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
     *@ORM\ManyToOne(targetEntity="Reading", inversedBy="secciones_reading")
     *@ORM\JoinColumn(name="id_reading", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    private $id_reading;


    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Question" , mappedBy="id_seccion_reading", cascade={"all"}, orphanRemoval=true)
     */
    private $questions_seccion_readings;


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
     * Set ordenSeccion
     *
     * @param string $ordenSeccion
     * @return Section_Reading
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
     * @return Section_Reading
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
     * Constructor
     */
    public function __construct()
    {
        $this->questions_seccion_readings = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add questions_seccion_readings
     *
     * @param \AppBundle\Entity\Question $questionsSeccionReadings
     * @return Section_Reading
     */
    public function addQuestionsSeccionReadings(\AppBundle\Entity\Question $questionsSeccionReadings)
    {
        $this->questions_seccion_readings[] = $questionsSeccionReadings;

        return $this;
    }

    /**
     * Remove questions_seccion_readings
     *
     * @param \AppBundle\Entity\Question $questionsSeccionReadings
     */
    public function removeQuestionsSeccionReadings(\AppBundle\Entity\Question $questionsSeccionReadings)
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
     * Set id_reading
     *
     * @param \AppBundle\Entity\Reading $idReading
     * @return Section_Reading
     */
    public function setIdReading(\AppBundle\Entity\Reading $idReading)
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
}
