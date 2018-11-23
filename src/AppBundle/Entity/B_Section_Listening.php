<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * B_Section_Listening
 *
 * @ORM\Table(name="b__section__listening")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\B_Section_ListeningRepository")
 */
class B_Section_Listening
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
     * @ORM\Column(name="orden_seccion", type="string", length=15)
     */
    private $ordenSeccion;

    /**
     * @var string
     *
     * @ORM\Column(name="texto_instruccion", type="text")
     */
    private $textoInstruccion;

    /**
     * @var string
     *
     * @ORM\Column(name="url_audio", type="string", length=255)
     */
    private $urlAudio;


    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\B_Question" , mappedBy="id_seccion_listening", cascade={"all"}, orphanRemoval=true)
     */
    private $questions_seccion_listenings;


    /**
     *@ORM\ManyToOne(targetEntity="User", inversedBy="blistening")
     *@ORM\JoinColumn(name="id_profesor", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    private $id_profesor;

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
     * Constructor
     */
    public function __construct()
    {
        $this->questions_seccion_listenings = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set ordenSeccion
     *
     * @param string $ordenSeccion
     * @return B_Section_Listening
     */
    public function setOrdenSeccion($ordenSeccion)
    {
        $this->ordenSeccion = $ordenSeccion;

        return $this;
    }

    /**
     * Get ordenSeccion
     *
     * @return string 
     */
    public function getOrdenSeccion()
    {
        return $this->ordenSeccion;
    }

    /**
     * Set textoInstruccion
     *
     * @param string $textoInstruccion
     * @return B_Section_Listening
     */
    public function setTextoInstruccion($textoInstruccion)
    {
        $this->textoInstruccion = $textoInstruccion;

        return $this;
    }

    /**
     * Get textoInstruccion
     *
     * @return string 
     */
    public function getTextoInstruccion()
    {
        return $this->textoInstruccion;
    }

    /**
     * Set urlAudio
     *
     * @param string $urlAudio
     * @return B_Section_Listening
     */
    public function setUrlAudio($urlAudio)
    {
        $this->urlAudio = $urlAudio;

        return $this;
    }

    /**
     * Get urlAudio
     *
     * @return string 
     */
    public function getUrlAudio()
    {
        return $this->urlAudio;
    }

    /**
     * Add questions_seccion_listenings
     *
     * @param \AppBundle\Entity\B_Question $questionsSeccionListenings
     * @return B_Section_Listening
     */
    public function addQuestionsSeccionListening(\AppBundle\Entity\B_Question $questionsSeccionListenings)
    {
        $this->questions_seccion_listenings[] = $questionsSeccionListenings;

        return $this;
    }

    /**
     * Remove questions_seccion_listenings
     *
     * @param \AppBundle\Entity\B_Question $questionsSeccionListenings
     */
    public function removeQuestionsSeccionListening(\AppBundle\Entity\B_Question $questionsSeccionListenings)
    {
        $this->questions_seccion_listenings->removeElement($questionsSeccionListenings);
    }

    /**
     * Get questions_seccion_listenings
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getQuestionsSeccionListenings()
    {
        return $this->questions_seccion_listenings;
    }

    /**
     * Set id_profesor
     *
     * @param \AppBundle\Entity\User $id_profesor
     * @return User
     */
    public function setIdProfesor(\AppBundle\Entity\User $id_profesor)
    {
        $this->id_profesor = $id_profesor;

        return $this;
    }

    /**
     * Get id_profesor
     *
     * @return \AppBundle\Entity\User
     */
    public function getIdProfesor()
    {
        return $this->id_profesor;
    }
}
