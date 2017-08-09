<?php

namespace papBundle\Entity;

/**
 * PasoprodEncargo
 */
class PasoProdEncargo
{
    private $id;

    private $nmencargo;

    private $pasoprod;

    private $encargo;

    public function getId()
    {
        return $this->id;
    }

    public function setNmencargo($nmencargo)
    {
        $this->nmencargo = $nmencargo;
        return $this;
    }

    public function getNmencargo()
    {
        return $this->nmencargo;
    }

    public function setPasoProd(\papBundle\Entity\PasoProd $pasoprod = null)
    {
        $this->pasoprod = $pasoprod;
        return $this;
    }
    
    public function getPasoProd()
    {
        return $this->pasoprod;
    }

    public function setEncargo(\papBundle\Entity\Encargo $encargo = null)
    {
        $this->encargo = $encargo;
        return $this;
    }

    public function getEncargo()
    {
        return $this->encargo;
    }
}

