<?php

namespace papBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;

use papBundle\Form\ElementoType;
use papBundle\Entity\Elemento;

class ElementoController extends Controller
{
    private $sesion;
    
    public function __construct(){
        $this->sesion = new Session();
    }
    
    public function DeleteAction ($id) {
        $EntityManager = $this->getDoctrine()->getManager();
        $Elemento_repo = $EntityManager->getRepository("papBundle:Elemento");
        $Elemento = $Elemento_repo->find($id);
        
        $EntityManager->remove($Elemento);
        $flush = $EntityManager->flush();
                
        if ($flush == null){
            $status = "Elemento ELIMINADO correctamente";
        } else {
            $status = "Error en la Eliminación del Elemento";
        }

        $this->sesion->getFlashBag()->add("status",$status);
        return $this->redirectToRoute("queryElemento");
    }
    
    public function InsertAction (Request $request) {
        $Elemento = new Elemento();
        $elementoForm = $this->createForm(ElementoType::class, $Elemento);
        $elementoForm->handleRequest($request);
        
        if ($elementoForm->isSubmitted()){
            if ($elementoForm->isValid()) {
                
                $EntityManager = $this->getDoctrine()->getManager();
                $Elemento_repo = $EntityManager->getRepository("papBundle:Elemento");
                $Modulo_repo = $EntityManager->getRepository("papBundle:Modulo");
                
                $newElemento = new Elemento();
                $newElemento->setNombre($elementoForm->get("nombre")->getData());
                $newElemento->setPath($elementoForm->get("path")->getData());
                
                $Modulo = $Modulo_repo->find($elementoForm->get("modulo")->getData());
                $newElemento->setModulo($Modulo);
                
                $EntityManager->persist($newElemento);
                $flush = $EntityManager->flush();
                
                if ($flush == null){
                    $status = "Elemento creado correctamente";
                } else {
                    $status = "Error en la ceación del elemento";
                }
                
                $this->sesion->getFlashBag()->add("status",$status);
                return $this->redirectToRoute("queryElemento");
            }
        }
        
        return $this->render("papBundle:Elemento:insert.html.twig", array(
            "form" => $elementoForm->createView()
        ));
        
    }
    
    public function UpdateAction(Request $request, $id) {
        $EntityManager = $this->getDoctrine()->getManager();
        $Elemento_repo = $EntityManager->getRepository("papBundle:Elemento");
        $Modulo_repo = $EntityManager->getRepository("papBundle:Modulo");
        
        $Elemento = $Elemento_repo->find($id);
        
        $elementoForm = $this->createForm(ElementoType::class,$Elemento);
        $elementoForm->handleRequest($request);
        
        if ($elementoForm->isSubmitted()){
            if ($elementoForm->isValid()) {
                $Elemento->setNombre($elementoForm->get("nombre")->getData());
                $Elemento->setPath($elementoForm->get("path")->getData());
                
                $Modulo = $Modulo_repo->find($elementoForm->get("modulo")->getData());
                $Elemento->setModulo($Modulo);
                
                $EntityManager->persist($Elemento);
                $flush = $EntityManager->flush();
                
                if ($flush == null){
                    $status = "Elemento modificado correctamente";
                } else {
                    $status = "Error en la Actualización";
                }
                
                $this->sesion->getFlashBag()->add("status",$status);
                return $this->redirectToRoute("queryElemento");
            }
        }
        
        return $this->render("papBundle:Elemento:update.html.twig", array(
            "form" => $elementoForm->createView(),
            "elemento" => $Elemento
        ));
    }
    
    public function QueryAction() {
        $EntityManager = $this->getDoctrine()->getManager();
        $Elemento_repo = $EntityManager->getRepository("papBundle:Elemento");
        $Elementos = $Elemento_repo->findAll();
        
        return $this->render('papBundle:Elemento:query.html.twig' , array (
            "elementos" => $Elementos
        ));
    }
    
    public function verPasosAction($id) {
        $EntityManager = $this->getDoctrine()->getManager();
        $Elemento_repo =$EntityManager->getRepository("papBundle:Elemento");
        $Elemento = $Elemento_repo->find($id);
        
        $ElementoaPasar_repo = $EntityManager->getRepository("papBundle:ElementoaPasar");
        $ElementoaPasarAll = $ElementoaPasar_repo->findBy(array("elemento" => $Elemento));
        return $this->render("papBundle:Elemento:verElementoPaso.html.twig", array(
            "ElementoaPasarAll" => $ElementoaPasarAll,
            "Elemento" => $Elemento,
            "num" => count($ElementoaPasarAll)
        ));
    }
    
} 
