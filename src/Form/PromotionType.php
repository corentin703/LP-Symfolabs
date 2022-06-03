<?php

namespace App\Form;

use App\Entity\Promotion;
use App\Repository\PromotionKindRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PromotionType extends AbstractType
{
    private PromotionKindRepository $promotionKindRepository;

    /**
     * @param PromotionKindRepository $promotionKindRepository
     */
    public function __construct(PromotionKindRepository $promotionKindRepository)
    {
        $this->promotionKindRepository = $promotionKindRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $kinds = $this->promotionKindRepository->findAll();
        $kindSelect = [];

        foreach ($kinds as $kind) {
            $kindSelect[$kind->getName()] = $kind->getId();
        }

        $builder
            ->add('kind', ChoiceType::class, [
                'multiple' => false,
                'label' => 'Type',
                'choices' => $kindSelect,
            ])
            ->add('title')
            ->add('content')
            ->add('discount')
            ->add('start_at')
            ->add('expires_at')
            ->add('delivery_fees')
            ->add('company')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Promotion::class,
        ]);
    }
}
