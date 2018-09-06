<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Inciso_Simple_Selection
 *
 * @ORM\Table(name="inciso__simple__selection")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Inciso_Simple_SelectionRepository")
 */
class Inciso_Simple_Selection
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
     *@ORM\ManyToOne(targetEntity="Item_Simple_Selection", inversedBy="incisos_simple_selection")
     *@ORM\JoinColumn(name="id_item_simple_selection", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    private $id_item_simple_selection;


    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set ordenInciso
     *
     * @param string $ordenInciso
     * @return Inciso_Simple_Selection
     */
    public function setOrdenInciso($ordenInciso)
    {
        $this->ordenInciso = $ordenInciso;

        return $this;
    }

    /**
     * Get ordenInciso
     *
     * @return integer 
     */
    public function getOrdenInciso()
    {
        return $this->ordenInciso;
    }

    /**
     * Set textoOpcion
     *
     * @param string $textoOpcion
     * @return Inciso_Simple_Selection
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
     * @param \AppBundle\Entity\Item_Simple_Selection $idItemSimpleSelection
     * @return Inciso_Simple_Selection
     */
    public function setIdItemSimpleSelection(\AppBundle\Entity\Item_Simple_Selection $idItemSimpleSelection)
    {
        $this->id_item_simple_selection = $idItemSimpleSelection;

        return $this;
    }

    /**
     * Get id_item_simple_selection
     *
     * @return \AppBundle\Entity\Item_Simple_Selection 
     */
    public function getIdItemSimpleSelection()
    {
        return $this->id_item_simple_selection;
    }
}
