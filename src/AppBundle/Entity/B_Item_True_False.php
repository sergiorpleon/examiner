<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * B_Item_True_False
 *
 * @ORM\Table(name="b__item__true__false")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\B_Item_True_FalseRepository")
 */
class B_Item_True_False
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
     * @ORM\Column(name="orden_item", type="string", length=15)
     */
    private $ordenItem;

    /**
     * @var string
     *
     * @ORM\Column(name="texto_item", type="text")
     */
    private $textoItem;

    /**
     * @var string
     *
     * @ORM\Column(name="opcion_correcta", type="string", length=255)
     */
    private $opcionCorrecta;

    /**
     *@ORM\ManyToOne(targetEntity="B_Question", inversedBy="items_true_false", cascade={"persist"})
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
     * Set ordenItem
     *
     * @param string $ordenItem
     * @return B_Item_True_False
     */
    public function setOrdenItem($ordenItem)
    {
        $this->ordenItem = $ordenItem;

        return $this;
    }

    /**
     * Get ordenItem
     *
     * @return string 
     */
    public function getOrdenItem()
    {
        return $this->ordenItem;
    }

    /**
     * Set textoItem
     *
     * @param string $textoItem
     * @return B_Item_True_False
     */
    public function setTextoItem($textoItem)
    {
        $this->textoItem = $textoItem;

        return $this;
    }

    /**
     * Get textoItem
     *
     * @return string 
     */
    public function getTextoItem()
    {
        return $this->textoItem;
    }

    /**
     * Set opcionCorrecta
     *
     * @param string $opcionCorrecta
     * @return B_Item_True_False
     */
    public function setOpcionCorrecta($opcionCorrecta)
    {
        $this->opcionCorrecta = $opcionCorrecta;

        return $this;
    }

    /**
     * Get opcionCorrecta
     *
     * @return string 
     */
    public function getOpcionCorrecta()
    {
        return $this->opcionCorrecta;
    }

    /**
     * Set id_question
     *
     * @param \AppBundle\Entity\B_Question $idQuestion
     * @return B_Item_True_False
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
