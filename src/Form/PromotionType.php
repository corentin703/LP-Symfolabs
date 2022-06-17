<?php

namespace App\Form;

use App\Entity\Promotion;
use App\Entity\PromotionKind;
use App\Repository\PromotionKindRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
    use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;

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
//        $kinds = $this->promotionKindRepository->findAll();
//        $kindSelect = [];
//
//        foreach ($kinds as $kind) {
//            $kindSelect[$kind->getName()] = $kind->getId();
//        }

        $builder
            ->add('kind', EntityType::class, [
                'class' => PromotionKind::class,
                'label' => 'Type',
                'choices' => $this->promotionKindRepository->findAll(),
                'choice_label' => function (PromotionKind $kind) {
                    return $kind->getName();
                },
            ])
            ->add('title', TextType::class,
                [
                    'required'=> true,
                ])
            ->add('content', TextType::class,
                [
                    'required'=> true,
                ])
            ->add('discount', TextType::class,
                [
                    'required'=> true,
                ])
            ->add('start_at', DateTimeType::class, [

                'required'=> true,
            ])
            ->add('expires_at', DateTimeType::class,
                [
                    'required'=> true,
                ])
            ->add('delivery_fees', NumberType::class,
                [
                    'required'=> true,
                ])
            ->add('company', TextType::class,
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
