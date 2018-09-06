<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Valor_List_Selection
 *
 * @ORM\Table(name="valor__list__selection")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Valor_List_SelectionRepository")
 */
class Valor_List_Selection
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
     * @ORM\Column(name="textoOpcion", type="string", length=255)
     */
    private $textoOpcion;

    /**
     *@ORM\ManyToOne(targetEntity="Question", inversedBy="valores_list_selection")
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
     * Set textoOpcion
     *
     * @param string $textoOpcion
     * @return Valor_List_Selection
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
     * @return Valor_List_Selection
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
}
