<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Item_Complete
 *
 * @ORM\Table(name="item__complete")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Item_CompleteRepository")
 */
class Item_Complete
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
     *@ORM\ManyToOne(targetEntity="Question", inversedBy="items_complete")
     *@ORM\JoinColumn(name="id_question", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    private $id_question;


    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Respuesta_Completa" , mappedBy="id_completa", cascade={"all"}, orphanRemoval=true)
     */
    private $respuesta_completa;


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
     * @return Item_Complete
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
     * @return Item_Complete
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
     * @param \AppBundle\Entity\Question $idQuestion
     * @return Item_Complete
     */
    public function setIdQuestion(\AppBundle\Entity\Question $idQuestion)
    {
        $this->id_question = $idQuestion;

        return $this;
    }

    /**
     * Get id_question
     *
     * @return \AppBundle\Entity\Question 
     */
    public function getIdQuestion()
    {
        return $this->id_question;
    }
}
