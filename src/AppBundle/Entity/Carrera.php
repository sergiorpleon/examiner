<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Carrera
 *
 * @ORM\Table(name="carrera")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CarreraRepository")
 */
class Carrera
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
     * @ORM\Column(name="nombre", type="string", length=255, nullable=true)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Estudia" , mappedBy="id_carrera", cascade={"all"}, orphanRemoval=true)
     */
    private $id_estudia;


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
     * Set nombre
     *
     * @param string $nombre
     * @return Carrera
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->id_estudia = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add id_estudia
     *
     * @param \AppBundle\Entity\Estudia $idEstudia
     * @return Carrera
     */
    public function addIdEstudium(\AppBundle\Entity\Estudia $idEstudia)
    {
        $this->id_estudia[] = $idEstudia;

        return $this;
    }

    /**
     * Remove id_estudia
     *
     * @param \AppBundle\Entity\Estudia $idEstudia
     */
    public function removeIdEstudium(\AppBundle\Entity\Estudia $idEstudia)
    {
        $this->id_estudia->removeElement($idEstudia);
    }

    /**
     * Get id_estudia
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdEstudia()
    {
        return $this->id_estudia;
    }
}
