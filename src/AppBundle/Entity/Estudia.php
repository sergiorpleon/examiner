<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Estudia
 *
 * @ORM\Table(name="estudia")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EstudiaRepository")
 */
class Estudia
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
     *@ORM\ManyToOne(targetEntity="Institucion", inversedBy="id_estudia")
     *@ORM\JoinColumn(name="id_institucion", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    private $id_institucion;

    /**
     *@ORM\ManyToOne(targetEntity="Carrera", inversedBy="id_estudia")
     *@ORM\JoinColumn(name="id_carrera", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    private $id_carrera;


    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\User" , mappedBy="id_estudia", cascade={"all"}, orphanRemoval=true)
     */
    private $id_user;




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
     * Set id_institucion
     *
     * @param \AppBundle\Entity\Institucion $idInstitucion
     * @return Estudia
     */
    public function setIdInstitucion(\AppBundle\Entity\Institucion $idInstitucion = null)
    {
        $this->id_institucion = $idInstitucion;

        return $this;
    }

    /**
     * Get id_institucion
     *
     * @return \AppBundle\Entity\Institucion 
     */
    public function getIdInstitucion()
    {
        return $this->id_institucion;
    }

    /**
     * Set id_carrera
     *
     * @param \AppBundle\Entity\Carrera $idCarrera
     * @return Estudia
     */
    public function setIdCarrera(\AppBundle\Entity\Carrera $idCarrera = null)
    {
        $this->id_carrera = $idCarrera;

        return $this;
    }

    /**
     * Get id_carrera
     *
     * @return \AppBundle\Entity\Carrera 
     */
    public function getIdCarrera()
    {
        return $this->id_carrera;
    }


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->id_user = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add id_user
     *
     * @param \AppBundle\Entity\User $idUser
     * @return Estudia
     */
    public function addIdUser(\AppBundle\Entity\User $idUser)
    {
        $this->id_user[] = $idUser;

        return $this;
    }

    /**
     * Remove id_user
     *
     * @param \AppBundle\Entity\User $idUser
     */
    public function removeIdUser(\AppBundle\Entity\User $idUser)
    {
        $this->id_user->removeElement($idUser);
    }

    /**
     * Get id_user
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getIdUser()
    {
        return $this->id_user;
    }

    public function __toString()
    {
        return $this->getIdInstitucion()->getNombre().' / '.$this->getIdCarrera()->getNombre();
    }
}
