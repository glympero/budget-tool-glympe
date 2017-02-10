<?php

namespace AppBundle\Form;

use AppBundle\Entity\Budget;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class BudgetFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,array(

                'attr'   =>  array(
                    'placeholder' => 'e.g. Moving to U.K.',
                    'class'   => 'text-center'
                )
            ))
            ->add('value', NumberType::class,array(

                'attr'   =>  array(
                    'placeholder' => 'e.g. 1000',
                    'class'   => 'text-center'
                )
            ))
            ->add('startDate', DateType::class, array(
                'format' => 'dd MM yyyy',
            ))
            ->add('endDate', DateType::class, array(
                'format' => 'dd MM yyyy',
            ))
            ->add('save', SubmitType::class,
                array(
                    'label' => 'Create Budget',
                    'attr'   =>  array(
                        'class'   => 'btn btn-primary'
                    )

                ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Budget::class
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_budget_form_type';
    }
}
