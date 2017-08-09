<?php

namespace papBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;

use papBundle\Form\ParametroType;
use papBundle\Entity\Parametro;

class ParametroController extends Controller
{
    private $sesion;
    
    public function __construct(){
        $this->sesion = new Session();
    }
    
    public function AddAction (Request $request) {
        $Parametro = new Parametro();
        $ParametroForm = $this->createForm(ParametroType::class, $Parametro);
        $ParametroForm->handleRequest($request);
        
        if ($ParametroForm->isSubmitted()){
            if ($ParametroForm->isValid()) {
                
                $EntityManager = $this->getDoctrine()->getManager();
                $Parametro_repo = $EntityManager->getRepository("papBundle:Parametro");
                
                $newParametro = new Parametro();
                $newParametro->setNombre($ParametroForm->get("nombre")->getData());
                $newParametro->setValor($ParametroForm->get("valor")->getData());
                
                $EntityManager->persist($newParametro);
                $flush = $EntityManager->flush();
                
                if ($flush == null){
                    $status = "Parametro creado correctamente";
                } else {
                    $status = "Error en la creación del Parametro";
                }
                
                $this->sesion->getFlashBag()->add("status",$status);
                return $this->redirectToRoute("queryParametro");
            }
        }
        
        return $this->render("papBundle:Parametro:insert.html.twig", array(
            "form" => $ParametroForm->createView()
        ));
        
    }
    
    public function EditAction(Request $request, $id) {
        $EntityManager = $this->getDoctrine()->getManager();
        $Parametro_repo = $EntityManager->getRepository("papBundle:Parametro");
        
        $Parametro = $Parametro_repo->find($id);
        
        $ParametroForm = $this->createForm(ParametroType::class,$Parametro);
        $ParametroForm->handleRequest($request);
        
        if ($ParametroForm->isSubmitted()){
            if ($ParametroForm->isValid()) {
                $Parametro->setNombre($ParametroForm->get("nombre")->getData());
                $Parametro->setValor($ParametroForm->get("valor")->getData());
                
                $EntityManager->persist($Parametro);
                $flush = $EntityManager->flush();
                
                if ($flush == null){
                    $status = "Parametro modificado correctamente";
                } else {
                    $status = "Error en la Actualización";
                }
                
                $this->sesion->getFlashBag()->add("status",$status);
                return $this->redirectToRoute("queryParametro");
            }
        }
        
        return $this->render("papBundle:Parametro:update.html.twig", array(
            "form" => $ParametroForm->createView(),
            "Parametro" => $Parametro
        ));
    }
    
    public function QueryAction() {
        $EntityManager = $this->getDoctrine()->getManager();
        $Parametro_repo = $EntityManager->getRepository("papBundle:Parametro");
        $ParametroAll = $Parametro_repo->findAll();
        
        return $this->render('papBundle:Parametro:query.html.twig' , array (
            "ParametroAll" => $ParametroAll
        ));
    }
    
} 
