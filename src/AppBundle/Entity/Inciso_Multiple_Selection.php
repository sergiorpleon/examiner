<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Inciso_Multiple_Selection
 *
 * @ORM\Table(name="inciso__multiple__selection")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Inciso_Multiple_SelectionRepository")
 */
class Inciso_Multiple_Selection
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
     * @var boolean
     *
     * @ORM\Column(name="correcta_inciso", type="boolean")
     */
    private $correctaInciso;

    /**
     * @var string
     *
     * @ORM\Column(name="orden_inciso", type="string", length=15)
     */
    private $ordenInciso;

    /**
     * @var string
     *
     * @ORM\Column(name="texto_opcion", type="text")
     */
    private $textoOpcion;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Respuesta_Multiple_Selection" , mappedBy="id_inciso_multiple_selection", cascade={"all"}, orphanRemoval=true)
     */
    private $respuesta_multiple_selection;

    /**
     *@ORM\ManyToOne(targetEntity="Question", inversedBy="incisos_multiple_selection")
     *@ORM\JoinColumn(name="id_question", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    private $id_question;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setOrdenInciso(null);
        $this->setTextoOpcion(null);
        $this->setCorrectaInciso(null);
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
     * Set ordenInciso
     *
     * @param string $ordenInciso
     * @return Inciso_Multiple_Selection
     */
    public function setOrdenInciso($ordenInciso)
    {
        $this->ordenInciso = $ordenInciso;

        return $this;
    }

    /**
     * Get ordenInciso
     *
     * @return string
     */
    public function getOrdenInciso()
    {
        return $this->ordenInciso;
    }

    /**
     * Set textoOpcion
     *
     * @param string $textoOpcion
     * @return Inciso_Multiple_Selection
     */
    public function setTextoOpcion($textoOpcion)
    {
        $this->textoOpcion = $textoOpcion;

        return $this;
    }

    /**
     * Get textoOpcion
     *
     * @return string 
     */
    public function getTextoOpcion()
    {
        return $this->textoOpcion;
    }

    /**
     * Set id_question
     *
     * @param \AppBundle\Entity\Question $idQuestion
     * @return Inciso_Multiple_Selection
     */
    public function setIdQuestion(\AppBundle\Entity\Question $idQuestion)
    {
        $this->id_question = $idQuestion;

        return $this;
    }

    /**
     * Get id_question
     *
     * @return \AppBundle\Entity\Question 
     */
    public function getIdQuestion()
    {
        return $this->id_question;
    }

    /**
     * Set correctaInciso
     *
     * @param boolean $correctaInciso
     * @return Inciso_Multiple_Selection
     */
    public function setCorrectaInciso($correctaInciso)
    {
        $this->correctaInciso = $correctaInciso;

        return $this;
    }

    /**
     * Get correctaInciso
     *
     * @return boolean 
     */
    public function getCorrectaInciso()
    {
        return $this->correctaInciso;
    }
}
