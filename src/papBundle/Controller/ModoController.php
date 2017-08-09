<?php

namespace papBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use papBundle\Form\ModoType;
use papBundle\Entity\Modo;

use Symfony\Component\HttpFoundation\Session\Session;

class ModoController extends Controller
{
    private $sesion;
    
    public function __construct(){
        $this->sesion = new Session();
    }
    
   public function EditAction(Request $request, $id) {
       $EntityManager = $this->getDoctrine()->getManager();
       $Modo_repo = $EntityManager->getRepository("papBundle:Modo");
       $Modo = $Modo_repo->find($id);
       
       $ModoForm =  $this->createForm(ModoType::class, $Modo);
       $ModoForm->handleRequest($request);
       
       if ($ModoForm->isSubmitted()){
           if ($ModoForm->isValid()){
               $Modo->setNombre($ModoForm->get('nombre')->getData());
               
               $EntityManager->persist($Modo);
               $flush = $EntityManager->flush();
               return $this->redirectToRoute("pap_homepage");
           }
        }
        
        return $this->render("papBundle:Modo:update.html.twig", array(
            "form" => $ModoForm->createView(),
            "Modo" => $Modo
        ));        
   }
}
