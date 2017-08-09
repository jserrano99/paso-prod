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


class ElementoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, array (
                                    "label" => 'Nombre Elemento',
                                    "required" => 'required',
                                    "attr" => array ("class" => "form-nombre form-control")
                                    )
                 )
            ->add('path', TextareaType::class, array(
                                    "label"=>'Path',
                                    "required" => "required",
                                    "attr" => array("class" => "form-path form-control")
                                    )
                )
            ->add('modulo', EntityType::class, array(
                                    "label"=> 'Módulo',
                                    "class" => 'papBundle:Modulo',
                                    "attr" => array("class" => "form-modulo form-control")
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
            'data_class' => 'papBundle\Entity\Elemento'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'papbundle_elemento';
    }


}
