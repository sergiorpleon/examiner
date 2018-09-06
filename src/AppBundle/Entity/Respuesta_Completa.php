<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Respuesta_Completa
 *
 * @ORM\Table(name="respuesta__completa")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Respuesta_CompletaRepository")
 */
class Respuesta_Completa
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
     *@ORM\ManyToOne(targetEntity="Item_Complete", inversedBy="respuesta_completa")
     *@ORM\JoinColumn(name="id_completa", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    private $id_completa;

    /**
     *@ORM\ManyToOne(targetEntity="User", inversedBy="respuesta_completa")
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
     * Set puntos
     *
     * @param integer $puntos
     * @return Respuesta_Completa
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
     * Set respuestaEstudiante
     *
     * @param string $respuestaEstudiante
     * @return Respuesta_Completa
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
     * Set id_completa
     *
     * @param \AppBundle\Entity\Item_Complete $idCompleta
     * @return Respuesta_Completa
     */
    public function setIdCompleta(\AppBundle\Entity\Item_Complete $idCompleta)
    {
        $this->id_completa = $idCompleta;

        return $this;
    }

    /**
     * Get id_completa
     *
     * @return \AppBundle\Entity\Item_Complete
     */
    public function getIdCompleta()
    {
        return $this->id_completa;
    }

    /**
     * Set id_estudiante
     *
     * @param \AppBundle\Entity\User $idEstudiante
     * @return Respuesta_Completa
     */
    public function setIdEstudiante(\AppBundle\Entity\User $idEstudiante = null)
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
