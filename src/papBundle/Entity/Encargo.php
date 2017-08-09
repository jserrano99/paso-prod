<?php

namespace papBundle\Entity;

/**
 * Encargo
 */
class Encargo
{
    private $id;

    private $numero;
            
    private $titulo;

    private $comentario;

    private $numPasoProd;
    
    protected $PasoProdEncargo;

    public function __construct() {
        $this->PasoProdEncargo = new \Doctrine\Common\Collections\ArrayCollection;
    }

    public function __toString() {
        return $this->numero;
    }
    
    public function getId()
    {
        return $this->id;
    }

    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    public function getNumero()
    {
        return $this->numero;
    }

    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }
    
    public function getTitulo()
    {
        return $this->titulo;
    }
    
    public function setComentario($comentario)
    {
        $this->comentario = comentario;

        return $this;
    }
    
    public function getComentario()
    {
        return $this->comentario;
    }  
    
    public function getPasoProdEncargo() {
        return $this->PasoProdEncargo;
    }
    
    public function getNumPasoProd(){
        $this->numPasoProd = count($this->getPasoProdEncargo());
        return $this->numPasoProd;
    }
}

