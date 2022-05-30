<?php

namespace App\Form;

use App\Entity\GoodPlan;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GoodPlanType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('discount')
            ->add('created_at')
            ->add('start_at')
            ->add('expires_at')
            ->add('became_hot_at')
            ->add('delivery_fees')
            ->add('company')
            ->add('link')
            ->add('author')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GoodPlan::class,
        ]);
    }
}
