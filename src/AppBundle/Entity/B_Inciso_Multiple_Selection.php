<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * B_Inciso_Multiple_Selection
 *
 * @ORM\Table(name="b__inciso__multiple__selection")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\B_Inciso_Multiple_SelectionRepository")
 */
class B_Inciso_Multiple_Selection
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
     *@ORM\ManyToOne(targetEntity="B_Question", inversedBy="incisos_multiple_selection")
     *@ORM\JoinColumn(name="id_question", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    private $id_question;


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
     * Set correctaInciso
     *
     * @param boolean $correctaInciso
     * @return B_Inciso_Multiple_Selection
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

    /**
     * Set ordenInciso
     *
     * @param string $ordenInciso
     * @return B_Inciso_Multiple_Selection
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
     * @return B_Inciso_Multiple_Selection
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
     * @param \AppBundle\Entity\B_Question $idQuestion
     * @return B_Inciso_Multiple_Selection
     */
    public function setIdQuestion(\AppBundle\Entity\B_Question $idQuestion)
    {
        $this->id_question = $idQuestion;

        return $this;
    }

    /**
     * Get id_question
     *
     * @return \AppBundle\Entity\B_Question 
     */
    public function getIdQuestion()
    {
        return $this->id_question;
    }
}
