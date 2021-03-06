<?php

namespace AppBundle\Form;

use AppBundle\Entity\Initiative;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class InitiativeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,array(
                'attr'   =>  array(
                    'placeholder' => 'Add an initiative',
                    'class'   => 'text-center'
                ))
            )
            ->add('value', NumberType::class,array(

                'attr'   =>  array(
                    'placeholder' => 'e.g. 1000',
                    'class'   => 'text-center'
                ))
            )
            ->add('save', SubmitType::class,
                array(
                    'label' => 'Create Initiative',
                    'attr'   =>  array(
                        'class'   => 'btn btn-primary'
                    )
                )
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Initiative::class
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_initiative_form_type';
    }
}


