<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * B_Item_Complete
 *
 * @ORM\Table(name="b__item__complete")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\B_Item_CompleteRepository")
 */
class B_Item_Complete
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
     * @ORM\Column(name="orden_item", type="string", length=15)
     */
    private $ordenItem;

    /**
     * @var string
     *
     * @ORM\Column(name="respuesta_correcta", type="string", length=255)
     */
    private $respuestaCorrecta;

    /**
     *@ORM\ManyToOne(targetEntity="B_Question", inversedBy="items_complete")
     *@ORM\JoinColumn(name="id_question", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    private $id_question;

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
     * Set ordenItem
     *
     * @param string $ordenItem
     * @return B_Item_Complete
     */
    public function setOrdenItem($ordenItem)
    {
        $this->ordenItem = $ordenItem;

        return $this;
    }

    /**
     * Get ordenItem
     *
     * @return string 
     */
    public function getOrdenItem()
    {
        return $this->ordenItem;
    }

    /**
     * Set respuestaCorrecta
     *
     * @param string $respuestaCorrecta
     * @return B_Item_Complete
     */
    public function setRespuestaCorrecta($respuestaCorrecta)
    {
        $this->respuestaCorrecta = $respuestaCorrecta;

        return $this;
    }

    /**
     * Get respuestaCorrecta
     *
     * @return string 
     */
    public function getRespuestaCorrecta()
    {
        return $this->respuestaCorrecta;
    }

    /**
     * Set id_question
     *
     * @param \AppBundle\Entity\B_Question $idQuestion
     * @return B_Item_Complete
     */
    public function setIdQuestion(\AppBundle\Entity\B_Question $idQuestion)
    {
        $this->id_question = $idQuestion;

        return $this;
    }

    /**
     * Get id_question
     *
     * @return \AppBundle\Entity\B_Question 
     */
    public function getIdQuestion()
    {
        return $this->id_question;
    }
}
