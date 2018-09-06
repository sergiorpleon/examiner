<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Item_List_Selection
 *
 * @ORM\Table(name="item__list__selection")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Item_List_SelectionRepository")
 */
class Item_List_Selection
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
     *@ORM\ManyToOne(targetEntity="Question", inversedBy="items_list_selection")
     *@ORM\JoinColumn(name="id_question", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    private $id_question;



    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Respuesta_List_Selection" , mappedBy="id_item_list_selection", cascade={"all"}, orphanRemoval=true)
     */
    private $respuesta_list_selection;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setOrdenItem(null);
        $this->setTextoItem(null);
        $this->setOpcionCorrecta(null);
        //$this->setIdQuestion(null);

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
     * Set ordenItem
     *
     * @param string $ordenItem
     * @return Item_List_Selection
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
     * @return Item_List_Selection
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
     * @return Item_List_Selection
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
     * @param \AppBundle\Entity\Question $idQuestion
     * @return Item_List_Selection
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
