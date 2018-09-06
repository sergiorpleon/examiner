<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Item_Simple_Selection
 *
 * @ORM\Table(name="item__simple__selection")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Item_Simple_SelectionRepository")
 */
class Item_Simple_Selection
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
     * @ORM\Column(name="opcion_correcta", type="text")
     */
    private $opcionCorrecta;

    /**
     *@ORM\ManyToOne(targetEntity="Question", inversedBy="items_simple_selection")
     *@ORM\JoinColumn(name="id_question", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    private $id_question;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Inciso_Simple_Selection" , mappedBy="id_item_simple_selection", cascade={"all"}, orphanRemoval=true)
     */
    private $incisos_simple_selection;


    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Respuesta_Simple_Selection" , mappedBy="id_item_simple_selection", cascade={"all"}, orphanRemoval=true)
     */
    private $respuesta_simple_selection;

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
     * @return Item_Simple_Selection
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
     * @return Item_Simple_Selection
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
     * @return Item_Simple_Selection
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
     * Constructor
     */
    public function __construct()
    {
        $this->incisos_simple_selection = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add incisos_simple_selection
     *
     * @param \AppBundle\Entity\Inciso_Simple_Selection $incisosSimpleSelection
     * @return Item_Simple_Selection
     */
    public function addIncisosSimpleSelection(\AppBundle\Entity\Inciso_Simple_Selection $incisosSimpleSelection)
    {
        $this->incisos_simple_selection[] = $incisosSimpleSelection;

        return $this;
    }

    /**
     * Remove incisos_simple_selection
     *
     * @param \AppBundle\Entity\Inciso_Simple_Selection $incisosSimpleSelection
     */
    public function removeIncisosSimpleSelection(\AppBundle\Entity\Inciso_Simple_Selection $incisosSimpleSelection)
    {
        $this->incisos_simple_selection->removeElement($incisosSimpleSelection);
    }

    /**
     * Get incisos_simple_selection
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIncisosSimpleSelection()
    {
        return $this->incisos_simple_selection;
    }

    /**
     * Set id_question
     *
     * @param \AppBundle\Entity\Question $idQuestion
     * @return Item_Simple_Selection
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
