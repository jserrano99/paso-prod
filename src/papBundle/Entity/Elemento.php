<?php

namespace papBundle\Entity;

/**
 * Elemento
 */
class Elemento
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $nombre;

    /**
     * @var string
     */
    private $path;

    /**
     * @var \papBundle\Entity\Modulo
     */
    private $modulo;


    private $numPasoProd;
    
    protected $ElementoaPasar;

    public function __construct() {
        $this->ElementoaPasar = new \Doctrine\Common\Collections\ArrayCollection;
    }

    public function __toString() {
        return $this->nombre;
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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Elemento
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return Elemento
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set idmodulo
     *
     * @param \papBundle\Entity\Modulo $idmodulo
     *
     * @return Elemento
     */
    public function setModulo(\papBundle\Entity\Modulo $modulo = null)
    {
        $this->modulo = $modulo;

        return $this;
    }

    /**
     * Get idmodulo
     *
     * @return \papBundle\Entity\Modulo
     */
    public function getModulo()
    {
        return $this->modulo;
    }
    
    public function getElementoaPasar() {
        return $this->ElementoaPasar;
    }
    
    public function getNumPasoProd(){
        $this->numPasoProd = count($this->getElementoaPasar());
        return $this->numPasoProd;
    }
}

