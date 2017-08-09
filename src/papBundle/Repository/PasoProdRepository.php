<?php

namespace papBundle\Repository;

use papBundle\Entity\Encargo;
use papBundle\Entity\PasoProdEncargo;

class PasoProdRepository  extends \Doctrine\ORM\EntityRepository {
    
    public function savePasoProdEncargo($nmEncargos=null,$PasoProd=null) {
        $EM = $this->getEntityManager();
        $Encargo_repo = $EM->getRepository("papBundle:Encargo");
        
        //rompe las tags por las comas 
        $nmEncargos = explode(",",$nmEncargos);
        foreach ( $nmEncargos as $nmEncargo) {
            //comprobamos si ya existe el número de encargo 
            $existeEncargo = $Encargo_repo->findOneBy(array("numero" => $nmEncargo));
            
            if ( $existeEncargo == null ) {
                $newEncargo  = new Encargo();
                $newEncargo->setNumero($nmEncargo);
                $newEncargo->setTitulo("Titulo Encargo: ".$nmEncargo);
                $EM->persist($newEncargo);
                $EM->flush();
            }
            
            $Encargo = $Encargo_repo->findOneBy(array("numero" => $nmEncargo));
            
            $newPasoProdEncargo = new PasoProdEncargo();
            $newPasoProdEncargo->setEncargo($Encargo);
            $newPasoProdEncargo->setPasoProd($PasoProd);
            $EM->persist($newPasoProdEncargo);
            $EM->flush();
        }
        
        return true;
        
    }
    //put your code here
}
