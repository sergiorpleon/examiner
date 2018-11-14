<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reading
 *
 * @ORM\Table(name="reading")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ReadingRepository")
 */
class Reading
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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Test" , mappedBy="id_reading",  cascade={"all"}, orphanRemoval=true)
     */
    private $tests;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Section_Reading" , mappedBy="id_reading", cascade={"all"}, orphanRemoval=true)
     */
    private $secciones_reading;


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
     * @return Reading
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
     * @return Reading
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
     * @return Reading
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
     * @return Reading
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
        $this->secciones_reading = new \Doctrine\Common\Collections\ArrayCollection();
    }




    /**
     * Add secciones_reading
     *
     * @param \AppBundle\Entity\Section_Reading $seccionesReading
     * @return Reading
     */
    public function addSeccionesReading(\AppBundle\Entity\Section_Reading $seccionesReading)
    {
        $this->secciones_reading[] = $seccionesReading;

        return $this;
    }

    /**
     * Remove secciones_reading
     *
     * @param \AppBundle\Entity\Section_Reading $seccionesReading
     */
    public function removeSeccionesReading(\AppBundle\Entity\Section_Reading $seccionesReading)
    {
        $this->secciones_reading->removeElement($seccionesReading);
    }

    /**
     * Get secciones_reading
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSeccionesReading()
    {
        return $this->secciones_reading;
    }







    /**
     * Add tests
     *
     * @param \AppBundle\Entity\Test $tests
     * @return Reading
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
}
