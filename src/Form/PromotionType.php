<?php

namespace App\Form;

use App\Entity\Promotion;
use App\Repository\PromotionKindRepository;
use Doctrine\DBAL\Types\DateType;
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
                'required'=> true,
            ])
            ->add('title', "",
                [
                    'required'=> true,
                ])
            ->add('content', "",
                [
                    'required'=> true,
                ])
            ->add('discount', "",
                [
                    'required'=> true,
                ])
            ->add('start_at', DateType::class,
                [
                    'required'=> true,
                ])
            ->add('expires_at', DateType::class,
                [
                    'required'=> true,
                ])
            ->add('delivery_fees', "",
                [
                    'required'=> true,
                ])
            ->add('company', "",
                [
                    'required'=> true,

                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Promotion::class,
            'validation_groups' => ['promoForm'],
        ]);
    }
}
