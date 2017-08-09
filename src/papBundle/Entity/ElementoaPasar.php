<?php

namespace papBundle\Entity;

/**
 * Elementoapasar
 */
class ElementoaPasar
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var boolean
     */
    private $itpaso;

    /**
     * @var \DateTime
     */
    private $fecha;

    /**
     * @var \papBundle\Entity\Elemento
     */
    private $elemento;

    /**
     * @var \papBundle\Entity\PasoProd
     */
    private $pasoprod;


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
     * Set itpaso
     *
     * @param boolean $itpaso
     *
     * @return Elementoapasar
     */
    public function setItpaso($itpaso)
    {
        $this->itpaso = $itpaso;

        return $this;
    }

    /**
     * Get itpaso
     *
     * @return boolean
     */
    public function getItpaso()
    {
        return $this->itpaso;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return Elementoapasar
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
     * Set idelemento
     *
     * @param \papBundle\Entity\Elemento $idelemento
     *
     * @return Elementoapasar
     */
    public function setElemento(\papBundle\Entity\Elemento $elemento = null)
    {
        $this->elemento = $elemento;

        return $this;
    }

    /**
     * Get idelemento
     *
     * @return \papBundle\Entity\Elemento
     */
    public function getElemento()
    {
        return $this->elemento;
    }

    /**
     * Set idPasoProd
     *
     * @param \papBundle\Entity\PasoProd $idPasoProd
     *
     * @return Elementoapasar
     */
    public function setPasoProd(\papBundle\Entity\PasoProd $PasoProd = null)
    {
        $this->pasoprod = $PasoProd;

        return $this;
    }

    /**
     * Get idPasoProd
     *
     * @return \papBundle\Entity\PasoProd
     */
    public function getPasoProd()
    {
        return $this->pasoprod;
    }
}

