<?php

namespace papBundle\Repository;

class ElementoRepository extends \Doctrine\ORM\EntityRepository
{
    public function verPendientes($pasoprod_id){
        $em = $this->getEntityManager();
        $db = $em->getConnection();

        $query = "select elemento.id , elemento.nombre, elemento.path, elemento.modulo_id, modulo.texto as modulotexto from modulo, elemento " 
                . " where (elemento.modulo_id = modulo.id) and elemento.id not in ( select elemento_id from elementoapasar where pasoprod_id = :pasoprod_id) ";
        $stmt = $db->prepare($query);
        $params = array(":pasoprod_id" => $pasoprod_id);
        $stmt->execute($params);
        $po=$stmt->fetchAll();

        return $po;
    }
}
