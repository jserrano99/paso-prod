<?php

namespace papBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;

use papBundle\Form\PasoProdType;

use papBundle\Entity\PasoProd;

class PasoProdController extends Controller
{
    private $sesion;
    
    public function __construct(){
        $this->sesion = new Session();
    }
    
    public function QueryAction() {
        $EntityManager = $this->getDoctrine()->getManager();
        $PasoProd_repo = $EntityManager->getRepository("papBundle:PasoProd");
        $PasosProd = $PasoProd_repo->findAll();
        
        return $this->render('papBundle:PasoProd:query.html.twig' , array (
            "PasosProd" => $PasosProd
        ));
    }
    
    public function InsertAction (Request $request) {
        $PasoProd = new PasoProd();
        $PasoProdForm = $this->createForm(PasoProdType::class, $PasoProd);
        $PasoProdForm->handleRequest($request);
        
        if ($PasoProdForm->isSubmitted()){
            if ($PasoProdForm->isValid()) {
                
                $EntityManager = $this->getDoctrine()->getManager();
                $PasoProd_repo = $EntityManager->getRepository("papBundle:PasoProd");
                $Estado_repo = $EntityManager->getRepository("papBundle:Estado");
                
                $newPasoProd = new PasoProd();
                $newPasoProd->setFecha($PasoProdForm->get("fecha")->getData());
                $newPasoProd->setTitulo($PasoProdForm->get("titulo")->getData());
                $newPasoProd->setComentario($PasoProdForm->get("comentario")->getData());
                
                $Usuario = $this->getUser();
                $newPasoProd->setUsuario($Usuario);

                $Estado = $Estado_repo->find($PasoProdForm->get("estado")->getData());
                $newPasoProd->setEstado($Estado);
                
                $EntityManager->persist($newPasoProd);
                $flush = $EntityManager->flush();
                
                if ($flush == null){
                    $status = "Paso a Producción Generado Correctamente";
                } else {
                    $status = "Error en la creación";
                }
                
                
                $PasoProd_repo->savePasoProdEncargo($PasoProdForm->get("encargos")->getData(),$newPasoProd );
                
                $this->sesion->getFlashBag()->add("status",$status);
                return $this->redirectToRoute("queryPasoProd");
            }
        }
        
        return $this->render("papBundle:PasoProd:insert.html.twig", array(
            "form" => $PasoProdForm->createView()
        ));
        
    }
    
    public function UpdateAction (Request $request, $id) {
        $EntityManager = $this->getDoctrine()->getManager();
        $PasoProd_repo = $EntityManager->getRepository("papBundle:PasoProd");
        $Estado_repo = $EntityManager->getRepository("papBundle:Estado");
        $PasoProdEncargo_repo = $EntityManager->getRepository("papBundle:PasoProdEncargo");
        
        $PasoProd = $PasoProd_repo->find($id);
        
        $encargos = "";
        foreach ($PasoProd->getPasoProdEncargo() as $PasoProdEncargo ) {
            $encargos .= $PasoProdEncargo->getEncargo()->getNumero() .", ";
        }
        
        $PasoProdForm = $this->createForm(PasoProdType::class, $PasoProd);
        $PasoProdForm->handleRequest($request);
        
        if ($PasoProdForm->isSubmitted()) {
            if ($PasoProdForm->isValid()){
                $PasoProd->setFecha($PasoProdForm->get("fecha")->getData());
                $PasoProd->setTitulo($PasoProdForm->get("titulo")->getData());
                $PasoProd->setComentario($PasoProdForm->get("comentario")->getData());
                $Estado = $Estado_repo->find($PasoProdForm->get("estado")->getData());
                $PasoProd->setEstado($Estado);
                
                $Usuario = $this->getUser();
                $PasoProd->setUsuario($Usuario);

                $EntityManager->persist($PasoProd);
                $flush = $EntityManager->flush();
                
                if ($flush == null){
                    $status = "Paso a Producción Actualizado Correctamente";
                } else {
                    $status = "Error en la actualización";
                }
                                
                // Eliminamos los Encargos asociados 
                $PasoProdEncargoAll = $PasoProdEncargo_repo->findBy( array("pasoprod" => $PasoProd));
                foreach ($PasoProdEncargoAll as $PasoProdEncargo) {
                    $EntityManager->remove($PasoProdEncargo);
                }
                
                // Actualizamos los nuevos encargos 
                $PasoProd_repo->savePasoProdEncargo($PasoProdForm->get("encargos")->getData(),$PasoProd );
                
                $this->sesion->getFlashBag()->add("status",$status);
                return $this->redirectToRoute("queryPasoProd");
            }
        }
        
        return $this->render("papBundle:PasoProd:update.html.twig", array(
            "form" => $PasoProdForm->createView(),
            "pasoprod" => $PasoProd,
            "encargos" => $encargos
        ));        
    }
    
    public function DeleteAction ($id) {
        $EntityManager = $this->getDoctrine()->getManager();
        $PasoProd_repo = $EntityManager->getRepository("papBundle:PasoProd");
        $PasoProdEncargo_repo = $EntityManager->getRepository("papBundle:PasoProdEncargo");
        $ElementoaPasar_repo = $EntityManager->getRepository("papBundle:ElementoaPasar");
        
        $PasoProd = $PasoProd_repo->find($id);
        
        // Eliminamos los Encargos asociados 
        $PasoProdEncargoAll = $PasoProdEncargo_repo->findBy( array("pasoprod" => $PasoProd));
        foreach ($PasoProdEncargoAll as $PasoProdEncargo) {
            $EntityManager->remove($PasoProdEncargo);
        }
        $ElementosaPasarAll = $ElementoaPasar_repo->findBy( array("pasoprod" => $PasoProd));
        foreach ($ElementosaPasarAll as $ElementosaPasar) {
            $EntityManager->remove($ElementosaPasar);
        }
        
        $EntityManager->remove($PasoProd);
        $EntityManager->flush();
        
        return $this->QueryAction();
    }
}
