<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TestEstudia
 *
 * @ORM\Table(name="test_estudia")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TestEstudiaRepository")
 */
class TestEstudia
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
     *@ORM\ManyToOne(targetEntity="Estudia", inversedBy="id_testestudia")
     *@ORM\JoinColumn(name="id_estudia", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    private $id_estudia;

    /**
     *@ORM\ManyToOne(targetEntity="Test", inversedBy="id_testestudia")
     *@ORM\JoinColumn(name="id_test", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    private $id_test;

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
     * Set id_estudia
     *
     * @param \AppBundle\Entity\Estudia $idEstudia
     * @return TestEstudia
     */
    public function setIdEstudia(\AppBundle\Entity\Estudia $idEstudia = null)
    {
        $this->id_estudia = $idEstudia;

        return $this;
    }

    /**
     * Get id_estudia
     *
     * @return \AppBundle\Entity\Estudia 
     */
    public function getIdEstudia()
    {
        return $this->id_estudia;
    }

    /**
     * Set id_test
     *
     * @param \AppBundle\Entity\Test $idTest
     * @return TestEstudia
     */
    public function setIdTest(\AppBundle\Entity\Test $idTest = null)
    {
        $this->id_test = $idTest;

        return $this;
    }

    /**
     * Get id_test
     *
     * @return \AppBundle\Entity\Test 
     */
    public function getIdTest()
    {
        return $this->id_test;
    }
}
