<?php

namespace papBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class ModoType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('nombre', ChoiceType::class, array(
                                    "label"=>'Modo',
                                    "required" => "required",
                                    "choices" => array( "REAL" => 'REAL',
                                                        "PRUEBAS" => 'PRUEBAS' ),
                                   "attr"=> array("class" => "form-estado form-control"
                )))
                ->add('Guardar', SubmitType::class, array(
                                    "attr" => array("class" => "form-submit btn btn-success")
                ))
                ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'papBundle\Entity\Modo'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'papbundle_modo';
    }


}
