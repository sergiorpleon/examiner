<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Respuesta_Multiple_Selection
 *
 * @ORM\Table(name="respuesta__multiple__selection")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Respuesta_Multiple_SelectionRepository")
 */
class Respuesta_Multiple_Selection
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
     * @ORM\Column(name="respuestaEstudiante", type="boolean")
     */
    private $respuestaEstudiante;

    /**
     * @var int
     *
     * @ORM\Column(name="puntos", type="integer")
     */
    private $puntos;


    /**
     *@ORM\ManyToOne(targetEntity="Inciso_Multiple_Selection", inversedBy="respuesta_multiple_selection")
     *@ORM\JoinColumn(name="id_multiple_selection", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    private $id_inciso_multiple_selection;

    /**
     *@ORM\ManyToOne(targetEntity="User", inversedBy="respuesta_multiple_selection")
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
     * @param boolean $respuestaEstudiante
     * @return Respuesta_Multiple_Selection
     */
    public function setRespuestaEstudiante($respuestaEstudiante)
    {
        $this->respuestaEstudiante = $respuestaEstudiante;

        return $this;
    }

    /**
     * Get respuestaEstudiante
     *
     * @return boolean
     */
    public function getRespuestaEstudiante()
    {
        return $this->respuestaEstudiante;
    }

    /**
     * Set puntos
     *
     * @param integer $puntos
     * @return Respuesta_Multiple_Selection
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
     * Set id_inciso_multiple_selection
     *
     * @param \AppBundle\Entity\Inciso_Multiple_Selection $idIncisoMultipleSelection
     * @return Respuesta_Multiple_Selection
     */
    public function setIdIncisoMultipleSelection(\AppBundle\Entity\Inciso_Multiple_Selection $idIncisoMultipleSelection)
    {
        $this->id_inciso_multiple_selection = $idIncisoMultipleSelection;

        return $this;
    }

    /**
     * Get id_inciso_multiple_selection
     *
     * @return \AppBundle\Entity\Inciso_Multiple_Selection
     */
    public function getIdIncisoMultipleSelection()
    {
        return $this->id_inciso_multiple_selection;
    }

    /**
     * Set id_estudiante
     *
     * @param \AppBundle\Entity\User $idEstudiante
     * @return Respuesta_Multiple_Selection
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
