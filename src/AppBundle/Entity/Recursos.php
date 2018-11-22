<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Recursos
 *
 * @ORM\Table(name="recursos")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RecursosRepository")
 */
class Recursos
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
     * @ORM\Column(name="id_test", type="string", length=255, nullable=true)
     */
    private $idTest;

    /**
     * @var string
     *
     * @ORM\Column(name="id_breading", type="string", length=255, nullable=true)
     */
    private $idBreading;

    /**
     * @var string
     *
     * @ORM\Column(name="id_blistening", type="string", length=255, nullable=true)
     */
    private $idBlistening;

    /**
     * @var string
     *
     * @ORM\Column(name="id_recurso", type="string", length=255)
     */
    private $idRecurso;


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
     * Set idTest
     *
     * @param string $idTest
     * @return Recursos
     */
    public function setIdTest($idTest)
    {
        $this->idTest = $idTest;

        return $this;
    }

    /**
     * Get idTest
     *
     * @return string 
     */
    public function getIdTest()
    {
        return $this->idTest;
    }

    /**
     * Set idBreading
     *
     * @param string $idBreading
     * @return Recursos
     */
    public function setIdBreading($idBreading)
    {
        $this->idBreading = $idBreading;

        return $this;
    }

    /**
     * Get idBreading
     *
     * @return string 
     */
    public function getIdBreading()
    {
        return $this->idBreading;
    }

    /**
     * Set idBlistening
     *
     * @param string $idBlistening
     * @return Recursos
     */
    public function setIdBlistening($idBlistening)
    {
        $this->idBlistening = $idBlistening;

        return $this;
    }

    /**
     * Get idBlistening
     *
     * @return string 
     */
    public function getIdBlistening()
    {
        return $this->idBlistening;
    }

    /**
     * Set url
     *
     * @param string idRecurso
     * @return Recursos
     */
    public function setIdRecurso($idRecurso)
    {
        $this->idRecurso = $idRecurso;

        return $this;
    }

    /**
     * Get idRecurso
     *
     * @return string 
     */
    public function getIdRecurso()
    {
        return $this->idRecurso;
    }
}
