<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Respuesta_True_False
 *
 * @ORM\Table(name="respuesta__true__false")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Respuesta_True_FalseRepository")
 */
class Respuesta_True_False
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
     *@ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="respuesta_true_false")
     */

    private $id_estudiante;

    /**
     * @var int
     *
     *@ORM\ManyToOne(targetEntity="AppBundle\Entity\Item_True_False", inversedBy="id_resp")
     */
    private $idItem;

    /**
     * @var int
     *
     * @ORM\Column(name="respuesta", type="integer")
     */
    private $respuesta;

    /**
     * @var int
     *
     * @ORM\Column(name="puntos", type="integer")
     */
    private $puntos;


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
     * Set idUser
     *
     * @param integer $idUser
     * @return Respuesta_True_False
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get idUser
     *
     * @return integer
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set idItem
     *
     * @param integer $idItem
     * @return Respuesta_True_False
     */
    public function setIdItem($idItem)
    {
        $this->idItem = $idItem;

        return $this;
    }

    /**
     * Get idItem
     *
     * @return integer
     */
    public function getIdItem()
    {
        return $this->idItem;
    }

    /**
     * Set respuesta
     *
     * @param integer $respuesta
     * @return Respuesta_True_False
     */
    public function setRespuesta($respuesta)
    {
        $this->respuesta = $respuesta;

        return $this;
    }

    /**
     * Get respuesta
     *
     * @return integer
     */
    public function getRespuesta()
    {
        return $this->respuesta;
    }

    /**
     * Set puntos
     *
     * @param integer $puntos
     * @return Respuesta_True_False
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
     * Set id_estudiante
     *
     * @param \AppBundle\Entity\User $idEstudiante
     * @return Respuesta_List_Selection
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
