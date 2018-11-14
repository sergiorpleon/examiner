<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Listening
 *
 * @ORM\Table(name="listening")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ListeningRepository")
 */
class Listening
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
     * @ORM\Column(name="texto_instrucciones", type="text")
     */
    private $textoInstrucciones;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date")
     */
    private $fecha;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="hora_comienzo", type="time")
     */
    private $horaComienzo;

    /**
     * @var int
     *
     * @ORM\Column(name="tiempo", type="integer")
     */
    private $tiempo;

    /**
     * @var int
     *
     * @ORM\Column(name="total_item", type="integer")
     */
    private $totalItem;


    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Test" , mappedBy="id_listening",  cascade={"all"}, orphanRemoval=true)
     */
    private $tests;


    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Section_Listening" , mappedBy="id_listening", cascade={"all"}, orphanRemoval=true)
     */
    private $secciones_listening;


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
     * Set fecha
     *
     * @param \DateTime $fecha
     * @return Test
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set horaComienzo
     *
     * @param \DateTime $horaComienzo
     * @return Listening
     */
    public function setHoraComienzo($horaComienzo)
    {
        $this->horaComienzo = $horaComienzo;

        return $this;
    }

    /**
     * Get horaComienzo
     *
     * @return \DateTime 
     */
    public function getHoraComienzo()
    {
        return $this->horaComienzo;
    }

    /**
     * Set textoInstrucciones
     *
     * @param string $textoInstrucciones
     * @return Listening
     */
    public function setTextoInstrucciones($textoInstrucciones)
    {
        $this->textoInstrucciones = $textoInstrucciones;

        return $this;
    }

    /**
     * Get textoInstrucciones
     *
     * @return string 
     */
    public function getTextoInstrucciones()
    {
        return $this->textoInstrucciones;
    }


    /**
     * Set tiempo
     *
     * @param integer $tiempo
     * @return Listening
     */
    public function setTiempo($tiempo)
    {
        $this->tiempo = $tiempo;

        return $this;
    }

    /**
     * Get tiempo
     *
     * @return integer 
     */
    public function getTiempo()
    {
        return $this->tiempo;
    }

    /**
     * Set totalItem
     *
     * @param integer $totalItem
     * @return Listening
     */
    public function setTotalItem($totalItem)
    {
        $this->totalItem = $totalItem;

        return $this;
    }

    /**
     * Get totalItem
     *
     * @return integer
     */
    public function getTotalItem()
    {
        return $this->totalItem;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tests = new \Doctrine\Common\Collections\ArrayCollection();
        $this->secciones_listening = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add tests
     *
     * @param \AppBundle\Entity\Test $tests
     * @return Listening
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
     * Add secciones_listening
     *
     * @param \AppBundle\Entity\Section_Listening $seccionesListening
     * @return Listening
     */
    public function addSeccionesListening(\AppBundle\Entity\Section_Listening $seccionesListening)
    {
        $this->secciones_listening[] = $seccionesListening;

        return $this;
    }

    /**
     * Remove secciones_listening
     *
     * @param \AppBundle\Entity\Section_Listening $seccionesListening
     */
    public function removeSeccionesListening(\AppBundle\Entity\Section_Listening $seccionesListening)
    {
        $this->secciones_listening->removeElement($seccionesListening);
    }

    /**
     * Get secciones_listening
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSeccionesListening()
    {
        return $this->secciones_listening;
    }
}
