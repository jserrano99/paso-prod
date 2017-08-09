<?php

namespace papBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;

use papBundle\Entity\PasoProd;
use papBundle\Entity\Elemento;
use papBundle\Entity\ElementoaPasar;
use papBundle\Entity\Estado;

use phpseclib\Net\SFTP;


class ElementoaPasarController extends Controller
{
    private $sesion;
    
    public function __construct(){
        $this->sesion = new Session();
    }
    
    public function QueryAction($id) {
        $EntityManager = $this->getDoctrine()->getManager();
        $PasoProd_repo = $EntityManager->getRepository("papBundle:PasoProd");
        $PasoProd = $PasoProd_repo->find($id);
        
        $Elemento_repo = $EntityManager->getRepository("papBundle:Elemento");
        
        $ElementosPendientes = $Elemento_repo->verPendientes($PasoProd->getId());
        
        $ElementoaPasar_repo = $EntityManager->getRepository("papBundle:ElementoaPasar");
        $ElementoaPasarAll = $ElementoaPasar_repo->findBy( array("pasoprod" =>$PasoProd));
        
        
        return $this->render('papBundle:ElementoaPasar:query.html.twig' , array (
            "ElementoaPasarAll" => $ElementoaPasarAll,
            "PasoProd" => $PasoProd,
            "num" => count($ElementoaPasarAll),
            "ElementosPendientes" => $ElementosPendientes
        ));
    }
    
    public function addAction($id, $pasoprod_id) {
        $EntityManager = $this->getDoctrine()->getManager();
        $PasoProd_repo = $EntityManager->getRepository("papBundle:PasoProd");
        $PasoProd = $PasoProd_repo->find($pasoprod_id);
        $Elemento_repo = $EntityManager->getRepository("papBundle:Elemento");
        $Elemento = $Elemento_repo->find($id);
        
        
        
        $newElementoaPasar = new ElementoaPasar;
        $newElementoaPasar->setElemento($Elemento);
        $newElementoaPasar->setPasoProd($PasoProd);
        $EntityManager->persist($newElementoaPasar);
        $EntityManager->flush();
        
        return $this->QueryAction($pasoprod_id);
        
    }
    
    public function quitarAction($id) {
        $EntityManager = $this->getDoctrine()->getManager();
        $ElementoaPasar_repo = $EntityManager->getRepository("papBundle:ElementoaPasar");
        $ElementoaPasar =$ElementoaPasar_repo->find($id);
        $pasoprod_id= $ElementoaPasar->getPasoProd()->getId();
        
        $EntityManager->remove($ElementoaPasar);
        $EntityManager->flush();
        
        return $this->QueryAction($pasoprod_id);
    }    
    
    public function eliminarDir($carpeta) {
        foreach(glob($carpeta . "/*") as $archivos_carpeta)
        {
            if (is_dir($archivos_carpeta))
            {
                eliminarDir($archivos_carpeta);
            }
            else
            {
                unlink($archivos_carpeta);
            }
        }

        rmdir($carpeta);
    }
    public function ejecutarPasoAction($id) {
    
        $EntityManager = $this->getDoctrine()->getManager();
        $Parametro_repo = $EntityManager->getRepository("papBundle:Parametro");
        $PasoProd_repo = $EntityManager->getRepository("papBundle:PasoProd");
        $Estado_repo = $EntityManager->getRepository("papBundle:Estado");
        $ElementoaPasar_repo = $EntityManager->getRepository("papBundle:ElementoaPasar");
        
        $PasoProd = $PasoProd_repo->find($id);
        $ElementoaPasarAll = $ElementoaPasar_repo->findBy(array("pasoprod" => $PasoProd));
        $CONSTANTES = array();
        
        $CONSTANTES["HOST_VAL"] = $Parametro_repo->findOneBy( array ("nombre" => "HOST_VAL"))->getValor();
        $CONSTANTES["HOST_PROD"] = $Parametro_repo->findOneBy( array ("nombre" => "HOST_PROD"))->getValor();
        $CONSTANTES["USUARIO"] = $Parametro_repo->findOneBy( array ("nombre" => "USUARIO"))->getValor();
        $CONSTANTES["PASSWORD"] = $Parametro_repo->findOneBy( array ("nombre" => "PASSWORD"))->getValor();
        $CONSTANTES["HOST_ROOT"] = $Parametro_repo->findOneBy( array ("nombre" => "HOST_ROOT"))->getValor();
        $CONSTANTES["LOCAL_ROOT"] = $Parametro_repo->findOneBy( array ("nombre" => "LOCAL_ROOT"))->getValor();
        $CONSTANTES["TEST_ROOT"] = $Parametro_repo->findOneBy( array ("nombre" => "TEST_ROOT"))->getValor();
        $CONSTANTES["MODO"] = $Parametro_repo->findOneBy( array ("nombre" => "MODO"))->getValor();
        
        if ($CONSTANTES["MODO"] == 'REAL') { 
            $root_prod = $CONSTANTES["HOST_ROOT"];
        } else {
            $root_prod = $CONSTANTES["TEST_ROOT"];
        }
        
        $conn_val = new SFTP($CONSTANTES["HOST_VAL"]);
        $conn_prod = new SFTP($CONSTANTES["HOST_PROD"]);
        
        $login_val = $conn_val->login($CONSTANTES["USUARIO"],$CONSTANTES["PASSWORD"]);
        $login_prod = $conn_prod->login($CONSTANTES["USUARIO"],$CONSTANTES["PASSWORD"]);
        
        // SI NO EXISTE EL DIRECTORIO EN LOCAL SE CREA 
        if (!file_exists($CONSTANTES["LOCAL_ROOT"])) {
            mkdir($CONSTANTES["LOCAL_ROOT"]);
        }
        
        $path_local = $CONSTANTES["LOCAL_ROOT"]."/Paso-".$PasoProd->getId();
        if (file_exists($path_local)) {
            $this->eliminarDir($path_local);
        }
        mkdir($path_local,777);
        
        foreach ( $ElementoaPasarAll as $ElementoaPasar ){
            $path_prod =  $root_prod
                        ."/"
                        .$ElementoaPasar->getElemento()->getPath();
            
            if (!$conn_prod->file_exists($path_prod)) {   // SI NO EXISTE EL PATH EN EL HOST REMOTO SE CREA 
                $conn_prod->mkdir($path_prod);
            }
            
            $fileRemoto = $CONSTANTES["HOST_ROOT"]
                        ."/"
                        .$ElementoaPasar->getElemento()->getPath()
                        ."/"
                        .$ElementoaPasar->getElemento()->getNombre();
            
            $fileLocal =  $path_local.
                         "/"
                         .$ElementoaPasar->getElemento()->getNombre();
            
            $conn_val->get($fileRemoto,$fileLocal);
            $fileRemoto = $path_prod
                        ."/"
                        .$ElementoaPasar->getElemento()->getNombre();
            
            $fileRemotoCopia = $fileRemoto."-bck-".$PasoProd->getId();
            if ( $conn_prod->file_exists($fileRemoto)){
                $conn_prod->exec('mv '.$fileRemoto.' '.$fileRemotoCopia);
                $conn_prod->put($fileRemoto, file_get_contents($fileLocal));
        	} else {
                $conn_prod->put($fileRemoto, file_get_contents($fileLocal));
            }
            
            $fecha = getdate ();
            $fechaActual = $fecha ['mday'] . '-' . $fecha ['mon'] . '-' . $fecha ['year'] . ' ' . $fecha ['hours'] . ':' . $fecha ['minutes'] . ':' . $fecha ['seconds'];
            $ElementoaPasar->setFecha($fechaActual);
            $ElementoaPasar->setItpaso(1);
            $EntityManager->persist($ElementoaPasar);
            
        }
        $idEstado = 4;
        $Estado = $Estado_repo->find($idEstado);
        
        $PasoProd->setEstado($Estado);
        $EntityManager->persist($PasoProd);
        $EntityManager->flush();
        
        $status = "Paso Ejecutado Correctamente";
        $this->sesion->getFlashBag()->add("status",$status);
        
        return $this->QueryAction($id);
    }
    
    public function retrocederPasoAction($id){
        
        $EntityManager = $this->getDoctrine()->getManager();
        $Parametro_repo = $EntityManager->getRepository("papBundle:Parametro");
        $PasoProd_repo = $EntityManager->getRepository("papBundle:PasoProd");
        $Estado_repo = $EntityManager->getRepository("papBundle:Estado");
        $ElementoaPasar_repo = $EntityManager->getRepository("papBundle:ElementoaPasar");
        
        $PasoProd = $PasoProd_repo->find($id);
        $ElementoaPasarAll = $ElementoaPasar_repo->findBy(array("pasoprod" => $PasoProd));
        $CONSTANTES = array();
        $CONSTANTES["HOST_PROD"] = $Parametro_repo->findOneBy( array ("nombre" => "HOST_PROD"))->getValor();
        $CONSTANTES["USUARIO"] = $Parametro_repo->findOneBy( array ("nombre" => "USUARIO"))->getValor();
        $CONSTANTES["PASSWORD"] = $Parametro_repo->findOneBy( array ("nombre" => "PASSWORD"))->getValor();
        $CONSTANTES["HOST_ROOT"] = $Parametro_repo->findOneBy( array ("nombre" => "HOST_ROOT"))->getValor();
        $CONSTANTES["LOCAL_ROOT"] = $Parametro_repo->findOneBy( array ("nombre" => "LOCAL_ROOT"))->getValor();
        $CONSTANTES["TEST_ROOT"] = $Parametro_repo->findOneBy( array ("nombre" => "TEST_ROOT"))->getValor();
        
        $conn_prod = new SFTP($CONSTANTES["HOST_PROD"]);
        $conn_prod->login($CONSTANTES["USUARIO"],$CONSTANTES["PASSWORD"]);
        
        foreach ( $ElementoaPasarAll as $ElementoaPasar ){
            $fileRemoto = $CONSTANTES["TEST_ROOT"]
                        ."/"
                        .$ElementoaPasar->getElemento()->getPath()
                        ."/"
                        .$ElementoaPasar->getElemento()->getNombre();
            
            $fileRemotoCopia = $fileRemoto."-bck-".$PasoProd->getId();
        
            if ($conn_prod->file_exists($fileRemotoCopia)) {
                $conn_prod->exec('mv '.$fileRemoto.' '.$fileRemoto.'-Retroceso');
                $conn_prod->exec('mv '.$fileRemotoCopia.' '.$fileRemoto);
                $fechaActual = "";
                $ElementoaPasar->setFecha($fechaActual);
                $ElementoaPasar->setItpaso(0);
                $EntityManager->persist($ElementoaPasar);
            }
        }
        $idEstado = 3;
        $Estado = $Estado_repo->find($idEstado);
        
        $PasoProd->setEstado($Estado);
        $EntityManager->persist($PasoProd);
        $EntityManager->flush();
        
        $status = "Retrocesión Ejecutada Correctamente";
        $this->sesion->getFlashBag()->add("status",$status);
        
        return $this->QueryAction($id);
    }
}   
