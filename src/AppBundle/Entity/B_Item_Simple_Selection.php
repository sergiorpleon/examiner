<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * B_Item_Simple_Selection
 *
 * @ORM\Table(name="b__item__simple__selection")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\B_Item_Simple_SelectionRepository")
 */
class B_Item_Simple_Selection
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
     *@ORM\ManyToOne(targetEntity="B_Question", inversedBy="items_simple_selection")
     *@ORM\JoinColumn(name="id_question", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    private $id_question;


    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\B_Inciso_Simple_Selection" , mappedBy="id_item_simple_selection", cascade={"all"}, orphanRemoval=true)
     */
    private $b_incisos_simple_selection;


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
     * @return B_Item_Simple_Selection
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
     * @return B_Item_Simple_Selection
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
     * @return B_Item_Simple_Selection
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
     * @return B_Item_Simple_Selection
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

    /**
     * Add b_incisos_simple_selection
     *
     * @param \AppBundle\Entity\B_Inciso_Simple_Selection $b_incisosSimpleSelection
     * @return B_Item_Simple_Selection
     */
    public function addBIncisosSimpleSelection(\AppBundle\Entity\B_Inciso_Simple_Selection $b_incisosSimpleSelection)
    {
        $this->b_incisos_simple_selection[] = $b_incisosSimpleSelection;

        return $this;
    }

    /**
     * Remove b_incisos_simple_selection
     *
     * @param \AppBundle\Entity\B_Inciso_Simple_Selection $b_incisosSimpleSelection
     */
    public function removeBIncisosSimpleSelection(\AppBundle\Entity\B_Inciso_Simple_Selection $b_incisosSimpleSelection)
    {
        $this->b_incisos_simple_selection->removeElement($b_incisosSimpleSelection);
    }

    /**
     * Get b_incisos_simple_selection
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBIncisosSimpleSelection()
    {
        return $this->b_incisos_simple_selection;
    }
}
