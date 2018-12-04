<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * B_Inciso_Simple_Selection
 *
 * @ORM\Table(name="b__inciso__simple__selection")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\B_Inciso_Simple_SelectionRepository")
 */
class B_Inciso_Simple_Selection
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
     *@ORM\ManyToOne(targetEntity="B_Item_Simple_Selection", inversedBy="b_incisos_simple_selection")
     *@ORM\JoinColumn(name="id_item_simple_selection", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    private $id_item_simple_selection;


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
     * @return B_Inciso_Simple_Selection
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
     * @return B_Inciso_Simple_Selection
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
     * Set id_item_simple_selection
     *
     * @param \AppBundle\Entity\B_Item_Simple_Selection $idItemSimpleSelection
     * @return B_Inciso_Simple_Selection
     */
    public function setIdItemSimpleSelection(\AppBundle\Entity\B_Item_Simple_Selection $idItemSimpleSelection)
    {
        $this->id_item_simple_selection = $idItemSimpleSelection;

        return $this;
    }

    /**
     * Get id_item_simple_selection
     *
     * @return \AppBundle\Entity\B_Item_Simple_Selection 
     */
    public function getIdItemSimpleSelection()
    {
        return $this->id_item_simple_selection;
    }
}
