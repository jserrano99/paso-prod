<?php

namespace papBundle\Entity;

/**
 * PasoProd
 */
class PasoProd
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $fecha;

    /**
     * @var string
     */
    private $comentario;

    /**
     * @var \papBundle\Entity\Estado
     */
    private $estado;
    
    private $titulo;
    
    private $usuario;
    
    protected $ElementoaPasar;
    
    protected $PasoProdEncargo;
    
    public function __construct() {
        $this->PasoProdEncargo = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ElementoaPasar = new \Doctrine\Common\Collections\ArrayCollection();
    }
     
    public function getTitulo(){
        return $this->titulo;
    }

    public function setTitulo($titulo) {
        $this->titulo = $titulo;
        return $this;
    }
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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return PasoProd
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set comentario
     *
     * @param string $comentario
     *
     * @return PasoProd
     */
    public function setComentario($comentario)
    {
        $this->comentario = $comentario;

        return $this;
    }

    /**
     * Get comentario
     *
     * @return string
     */
    public function getComentario()
    {
        return $this->comentario;
    }

    /**
     * Set idestado
     *
     * @param \papBundle\Entity\Estado $idestado
     *
     * @return PasoProd
     */
    public function setEstado(\papBundle\Entity\Estado $estado = null)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get idestado
     *
     * @return \papBundle\Entity\Estado
     */
    public function getEstado()
    {
        return $this->estado;
    }
       
 
    public function addPasoProdEncargo(\papBundle\Entity\Encargo $Encargo = null) {
        $this->PasoProdEncargo[] = $Encargo;
        return $this;
    }
    
    public function getPasoProdEncargo() {      
        return $this->PasoProdEncargo;
    }
    
    public function addElementoaPasar(\papBundle\Entity\Elemento $Elemento = null) {
        $this->ElementoaPasar[] = $Elemento;
        return $this;
    }
    
    public function getElementoaPasar() {      
        return $this->ElementoaPasar;
    }
    
    public function setUsuario (\papBundle\Entity\Usuario $Usuario = null) {
        $this->usuario = $Usuario;
        return  $this;
    }
    
    public function getUsuario() {
        return $this->usuario;
    }
        
}

