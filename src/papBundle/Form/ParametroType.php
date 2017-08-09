<?php

namespace papBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ParametroType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class, array (
                                    "label" => 'Parámetro',
                                    "required" => 'required',
                                    "attr" => array ("class" => "form-nombre form-control")
                                    )
                 )
            ->add('valor', TextType::class, array(
                                    "label"=>'Valor',
                                    "required" => "required",
                                    "attr" => array("class" => "form-path form-control")
                                    )
                )
            ->add('Guardar', SubmitType::class, array(
                                    "attr" => array("class" => "form-submit btn btn-success")
                                )
                );
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'papBundle\Entity\Parametro'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'papbundle_parametro';
    }


}
