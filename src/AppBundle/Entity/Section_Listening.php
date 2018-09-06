<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Section_Listening
 *
 * @ORM\Table(name="section__listening")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Section_ListeningRepository")
 */
class Section_Listening
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
     *@ORM\ManyToOne(targetEntity="Listening", inversedBy="secciones_listening")
     *@ORM\JoinColumn(name="id_listening", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     */
    private $id_listening;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Question" , mappedBy="id_seccion_listening", cascade={"all"}, orphanRemoval=true)
     */
    private $questions_seccion_listenings;


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
     * Set ordenSeccion
     *
     * @param string $ordenSeccion
     * @return Section_Listening
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
     * @return Section_Listening
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
     * @return Section_Listening
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
     * Constructor
     */
    public function __construct()
    {
        $this->questions_seccion_listenings = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add questions_seccion_listenings
     *
     * @param \AppBundle\Entity\Question $questionsSeccionListenings
     * @return Section_Listening
     */
    public function addQuestionsSeccionListening(\AppBundle\Entity\Question $questionsSeccionListenings)
    {
        $this->questions_seccion_listenings[] = $questionsSeccionListenings;

        return $this;
    }

    /**
     * Remove questions_seccion_listenings
     *
     * @param \AppBundle\Entity\Question $questionsSeccionListenings
     */
    public function removeQuestionsSeccionListening(\AppBundle\Entity\Question $questionsSeccionListenings)
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
     * Set id_listening
     *
     * @param \AppBundle\Entity\Listening $idListening
     * @return Section_Listening
     */
    public function setIdListening(\AppBundle\Entity\Listening $idListening)
    {
        $this->id_listening = $idListening;

        return $this;
    }

    /**
     * Get id_listening
     *
     * @return \AppBundle\Entity\Listening 
     */
    public function getIdListening()
    {
        return $this->id_listening;
    }



    public function subirFoto()
    {
        if (null === $this->urlAudio) {
            return;
        }
        $directorioDestino = __DIR__.'/../../../../web/uploads/audios';
        $nombreArchivoAudio = uniqid('cupon-').$this->getUrlAudio();
        $this->foto->move($directorioDestino, $nombreArchivoAudio);
        $this->setFoto($nombreArchivoAudio);
    }
}
