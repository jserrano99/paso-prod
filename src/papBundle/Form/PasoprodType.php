<?php

namespace papBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class PasoProdType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('fecha', DateType::class, array (
                                    "label" => 'Fecha',
                                    "required" => 'required',
                                    'widget' => 'single_text',
                                    "attr" => array (
                                        'class' => 'form-control js-datepicker',
                                        'data-date-format' => 'dd-mm-yyyy'
                                       )
                                    )
                 )
                
                ->add('titulo', TextType::class, array (
                                    "label" => 'Titulo',
                                    "required" => 'required',
                                    "attr" => array ("class" => "form-titulo form-control")
                                    )
                 )
                ->add('encargos',  TextType::class, array(
                                    "label"=>'Encargos que Soluciona', 
                                    "mapped"=>false,
                                    "required" => "required",
                                    "attr" => array("class" => "form-encargos form-control")
                                )
                    )
                ->add('comentario', TextareaType::class, array(
                                    "label"=>'Observaciones',
                                    "required" => "required",
                                    "attr" => array("class" => "form-comentario form-control")
                                    )
                )
                ->add('estado', EntityType::class, array(
                                    "label"=> 'Estado',
                                    "class" => 'papBundle:Estado',
                                    "attr" => array("class" => "form-estado form-control")
                                    )
                )
                ->add('Guardar', SubmitType::class, array(
                                    "attr" => array("class" => "form-submit btn btn-success")
                                )
                )
           ;
    }
     /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'papBundle\Entity\PasoProd'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'papbundle_PasoProd';
    }


}
