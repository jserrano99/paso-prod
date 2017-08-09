<?php

namespace papBundle\Entity;

/**
 * Modulo
 */
class Modulo
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $texto;

    public function __toString() {
        return $this->texto;
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
     * Set texto
     *
     * @param string $texto
     *
     * @return Modulo
     */
    public function setTexto($texto)
    {
        $this->texto = $texto;

        return $this;
    }

    /**
     * Get texto
     *
     * @return string
     */
    public function getTexto()
    {
        return $this->texto;
    }
}

