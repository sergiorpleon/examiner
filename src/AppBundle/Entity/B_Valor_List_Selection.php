<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * B_Valor_List_Selection
 *
 * @ORM\Table(name="b__valor__list__selection")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\B_Valor_List_SelectionRepository")
 */
class B_Valor_List_Selection
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
     *@ORM\ManyToOne(targetEntity="B_Question", inversedBy="valores_list_selection")
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
     * @return B_Valor_List_Selection
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
     * @return B_Valor_List_Selection
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
