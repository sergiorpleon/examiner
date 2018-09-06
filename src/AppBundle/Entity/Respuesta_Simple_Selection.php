<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Respuesta_Simple_Selection
 *
 * @ORM\Table(name="respuesta__simple__selection")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Respuesta_Simple_SelectionRepository")
 */
class Respuesta_Simple_Selection
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
     * @ORM\Column(name="respuestaEstudiante", type="string", length=255)
     */
    private $respuestaEstudiante;

    /**
     * @var int
     *
     * @ORM\Column(name="puntos", type="integer")
     */
    private $puntos;


    /**
     *@ORM\ManyToOne(targetEntity="Item_Simple_Selection", inversedBy="respuesta_simple_selection")
     *@ORM\JoinColumn(name="id_simple_selection", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    private $id_item_simple_selection;

    /**
     *@ORM\ManyToOne(targetEntity="User", inversedBy="respuesta_simple_selection")
     *@ORM\JoinColumn(name="id_user", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    private $id_estudiante;



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
     * Set respuestaEstudiante
     *
     * @param string $respuestaEstudiante
     * @return Respuesta_Simple_Selection
     */
    public function setRespuestaEstudiante($respuestaEstudiante)
    {
        $this->respuestaEstudiante = $respuestaEstudiante;

        return $this;
    }

    /**
     * Get respuestaEstudiante
     *
     * @return string
     */
    public function getRespuestaEstudiante()
    {
        return $this->respuestaEstudiante;
    }

    /**
     * Set puntos
     *
     * @param integer $puntos
     * @return Respuesta_Simple_Selection
     */
    public function setPuntos($puntos)
    {
        $this->puntos = $puntos;

        return $this;
    }

    /**
     * Get puntos
     *
     * @return integer
     */
    public function getPuntos()
    {
        return $this->puntos;
    }

    /**
     * Set id_item_simple_selection
     *
     * @param \AppBundle\Entity\Item_Simple_Selection $idItemSimpleSelection
     * @return Respuesta_Simple_Selection
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

    /**
     * Set id_estudiante
     *
     * @param \AppBundle\Entity\User $idEstudiante
     * @return Respuesta_Simple_Selection
     */
    public function setIdEstudiante(\AppBundle\Entity\User $idEstudiante)
    {
        $this->id_estudiante = $idEstudiante;

        return $this;
    }

    /**
     * Get id_estudiante
     *
     * @return \AppBundle\Entity\User
     */
    public function getIdEstudiante()
    {
        return $this->id_estudiante;
    }
}
