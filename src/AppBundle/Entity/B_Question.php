<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * B_Question
 *
 * @ORM\Table(name="b__question")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\B_QuestionRepository")
 */
class B_Question
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
     * @ORM\Column(name="orden_pregunta", type="string", length=15)
     */
    private $ordenPregunta;

    /**
     * @var string
     *
     * @ORM\Column(name="texto_pregunta", type="text")
     */
    private $textoPregunta;

    /**
     * @var int
     *
     * @ORM\Column(name="tipo_pregunta", type="integer")
     */
    private $tipoPregunta;

    /**
     * @var int
     *
     * @ORM\Column(name="total_item", type="integer")
     */
    private $totalItem;

    /**
     * @var string
     *
     * @ORM\Column(name="vista_html_completa", type="text", nullable=true)
     */
    private $vistaHtmlCompleta;

    /**
     *@ORM\ManyToOne(targetEntity="B_Section_Listening", inversedBy="questions_seccion_listenings")
     *@ORM\JoinColumn(name="id_seccion_listening", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */

    private $id_seccion_listening;
    /**
     *@ORM\ManyToOne(targetEntity="B_Section_Reading", inversedBy="questions_seccion_readings")
     *@ORM\JoinColumn(name="id_seccion_reading", referencedColumnName="id", onDelete="CASCADE", nullable=true)
     */
    private $id_seccion_reading;



    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\B_Item_Simple_Selection" , mappedBy="id_question", cascade={"all"}, orphanRemoval=true)
     */
    private $items_simple_selection;



    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\B_Item_True_False" , mappedBy="id_question", cascade={"all"}, orphanRemoval=true)
     */
    private $items_true_false;


    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\B_Inciso_Multiple_Selection" , mappedBy="id_question", cascade={"all"}, orphanRemoval=true)
     */
    private $incisos_multiple_selection;


    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\B_Item_List_Selection" , mappedBy="id_question", cascade={"all"}, orphanRemoval=true)
     */
    private $items_list_selection;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\B_Valor_List_Selection" , mappedBy="id_question", cascade={"all"}, orphanRemoval=true)
     */
    private $valores_list_selection;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\B_Item_Complete" , mappedBy="id_question", cascade={"all"}, orphanRemoval=true)
     */
    private $items_complete;



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
        $this->items_simple_selection = new \Doctrine\Common\Collections\ArrayCollection();
        $this->items_true_false = new \Doctrine\Common\Collections\ArrayCollection();
        $this->items_multiple_selection = new \Doctrine\Common\Collections\ArrayCollection();
        $this->incisos_multiple_selection = new \Doctrine\Common\Collections\ArrayCollection();
        $this->items_list_selection = new \Doctrine\Common\Collections\ArrayCollection();
        $this->valores_list_selection = new \Doctrine\Common\Collections\ArrayCollection();
        $this->items_complete = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set ordenPregunta
     *
     * @param string $ordenPregunta
     * @return B_Question
     */
    public function setOrdenPregunta($ordenPregunta)
    {
        $this->ordenPregunta = $ordenPregunta;

        return $this;
    }

    /**
     * Get ordenPregunta
     *
     * @return string 
     */
    public function getOrdenPregunta()
    {
        return $this->ordenPregunta;
    }

    /**
     * Set textoPregunta
     *
     * @param string $textoPregunta
     * @return B_Question
     */
    public function setTextoPregunta($textoPregunta)
    {
        $this->textoPregunta = $textoPregunta;

        return $this;
    }

    /**
     * Get textoPregunta
     *
     * @return string 
     */
    public function getTextoPregunta()
    {
        return $this->textoPregunta;
    }

    /**
     * Set tipoPregunta
     *
     * @param integer $tipoPregunta
     * @return B_Question
     */
    public function setTipoPregunta($tipoPregunta)
    {
        $this->tipoPregunta = $tipoPregunta;

        return $this;
    }

    /**
     * Get tipoPregunta
     *
     * @return integer 
     */
    public function getTipoPregunta()
    {
        return $this->tipoPregunta;
    }

    /**
     * Set totalItem
     *
     * @param integer $totalItem
     * @return B_Question
     */
    public function setTotalItem($totalItem)
    {
        $this->totalItem = $totalItem;

        return $this;
    }

    /**
     * Get totalItem
     *
     * @return integer 
     */
    public function getTotalItem()
    {
        return $this->totalItem;
    }

    /**
     * Set vistaHtmlCompleta
     *
     * @param string $vistaHtmlCompleta
     * @return B_Question
     */
    public function setVistaHtmlCompleta($vistaHtmlCompleta)
    {
        $this->vistaHtmlCompleta = $vistaHtmlCompleta;

        return $this;
    }

    /**
     * Get vistaHtmlCompleta
     *
     * @return string 
     */
    public function getVistaHtmlCompleta()
    {
        return $this->vistaHtmlCompleta;
    }

    /**
     * Set id_seccion_listening
     *
     * @param \AppBundle\Entity\B_Section_Listening $idSeccionListening
     * @return B_Question
     */
    public function setIdSeccionListening(\AppBundle\Entity\B_Section_Listening $idSeccionListening = null)
    {
        $this->id_seccion_listening = $idSeccionListening;

        return $this;
    }

    /**
     * Get id_seccion_listening
     *
     * @return \AppBundle\Entity\B_Section_Listening 
     */
    public function getIdSeccionListening()
    {
        return $this->id_seccion_listening;
    }

    /**
     * Set id_seccion_reading
     *
     * @param \AppBundle\Entity\B_Section_Reading $idSeccionReading
     * @return B_Question
     */
    public function setIdSeccionReading(\AppBundle\Entity\B_Section_Reading $idSeccionReading = null)
    {
        $this->id_seccion_reading = $idSeccionReading;

        return $this;
    }

    /**
     * Get id_seccion_reading
     *
     * @return \AppBundle\Entity\B_Section_Reading 
     */
    public function getIdSeccionReading()
    {
        return $this->id_seccion_reading;
    }

    /**
     * Add items_simple_selection
     *
     * @param \AppBundle\Entity\B_Item_Simple_Selection $itemsSimpleSelection
     * @return B_Question
     */
    public function addItemsSimpleSelection(\AppBundle\Entity\B_Item_Simple_Selection $itemsSimpleSelection)
    {
        $this->items_simple_selection[] = $itemsSimpleSelection;

        return $this;
    }

    /**
     * Remove items_simple_selection
     *
     * @param \AppBundle\Entity\B_Item_Simple_Selection $itemsSimpleSelection
     */
    public function removeItemsSimpleSelection(\AppBundle\Entity\B_Item_Simple_Selection $itemsSimpleSelection)
    {
        $this->items_simple_selection->removeElement($itemsSimpleSelection);
    }

    /**
     * Get items_simple_selection
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getItemsSimpleSelection()
    {
        return $this->items_simple_selection;
    }

    /**
     * Add items_true_false
     *
     * @param \AppBundle\Entity\B_Item_True_False $itemsTrueFalse
     * @return B_Question
     */
    public function addItemsTrueFalse(\AppBundle\Entity\B_Item_True_False $itemsTrueFalse)
    {
        $this->items_true_false[] = $itemsTrueFalse;

        return $this;
    }

    /**
     * Remove items_true_false
     *
     * @param \AppBundle\Entity\B_Item_True_False $itemsTrueFalse
     */
    public function removeItemsTrueFalse(\AppBundle\Entity\B_Item_True_False $itemsTrueFalse)
    {
        $this->items_true_false->removeElement($itemsTrueFalse);
    }

    /**
     * Get items_true_false
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getItemsTrueFalse()
    {
        return $this->items_true_false;
    }



    /**
     * Add incisos_multiple_selection
     *
     * @param \AppBundle\Entity\B_Inciso_Multiple_Selection $incisosMultipleSelection
     * @return B_Question
     */
    public function addIncisosMultipleSelection(\AppBundle\Entity\B_Inciso_Multiple_Selection $incisosMultipleSelection)
    {
        $this->incisos_multiple_selection[] = $incisosMultipleSelection;

        return $this;
    }

    /**
     * Remove incisos_multiple_selection
     *
     * @param \AppBundle\Entity\B_Inciso_Multiple_Selection $incisosMultipleSelection
     */
    public function removeIncisosMultipleSelection(\AppBundle\Entity\B_Inciso_Multiple_Selection $incisosMultipleSelection)
    {
        $this->incisos_multiple_selection->removeElement($incisosMultipleSelection);
    }

    /**
     * Get incisos_multiple_selection
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIncisosMultipleSelection()
    {
        return $this->incisos_multiple_selection;
    }

    /**
     * Add items_list_selection
     *
     * @param \AppBundle\Entity\B_Item_List_Selection $itemsListSelection
     * @return B_Question
     */
    public function addItemsListSelection(\AppBundle\Entity\B_Item_List_Selection $itemsListSelection)
    {
        $this->items_list_selection[] = $itemsListSelection;

        return $this;
    }

    /**
     * Remove items_list_selection
     *
     * @param \AppBundle\Entity\B_Item_List_Selection $itemsListSelection
     */
    public function removeItemsListSelection(\AppBundle\Entity\B_Item_List_Selection $itemsListSelection)
    {
        $this->items_list_selection->removeElement($itemsListSelection);
    }

    /**
     * Get items_list_selection
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getItemsListSelection()
    {
        return $this->items_list_selection;
    }

    /**
     * Add valores_list_selection
     *
     * @param \AppBundle\Entity\B_Valor_List_Selection $valoresListSelection
     * @return B_Question
     */
    public function addValoresListSelection(\AppBundle\Entity\B_Valor_List_Selection $valoresListSelection)
    {
        $this->valores_list_selection[] = $valoresListSelection;

        return $this;
    }

    /**
     * Remove valores_list_selection
     *
     * @param \AppBundle\Entity\B_Valor_List_Selection $valoresListSelection
     */
    public function removeValoresListSelection(\AppBundle\Entity\B_Valor_List_Selection $valoresListSelection)
    {
        $this->valores_list_selection->removeElement($valoresListSelection);
    }

    /**
     * Get valores_list_selection
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getValoresListSelection()
    {
        return $this->valores_list_selection;
    }

    /**
     * Add items_complete
     *
     * @param \AppBundle\Entity\B_Item_Complete $itemsComplete
     * @return B_Question
     */
    public function addItemsComplete(\AppBundle\Entity\B_Item_Complete $itemsComplete)
    {
        $this->items_complete[] = $itemsComplete;

        return $this;
    }

    /**
     * Remove items_complete
     *
     * @param \AppBundle\Entity\B_Item_Complete $itemsComplete
     */
    public function removeItemsComplete(\AppBundle\Entity\B_Item_Complete $itemsComplete)
    {
        $this->items_complete->removeElement($itemsComplete);
    }

    /**
     * Get items_complete
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getItemsComplete()
    {
        return $this->items_complete;
    }
}
