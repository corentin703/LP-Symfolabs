<?php

namespace App\DataFixtures;

use App\Entity\Promotion;
use App\Entity\PromotionKind;
use App\Entity\User;
use App\Repository\PromotionKindRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class PromotionFixtures extends Fixture implements DependentFixtureInterface
{
    private UserRepository $userRepository;
    private PromotionKindRepository $promotionKindRepository;
    protected Faker\Generator $faker;

    /**
     * @param UserRepository $userRepository
     * @param PromotionKindRepository $promotionKindRepository
     */
    public function __construct(UserRepository $userRepository, PromotionKindRepository $promotionKindRepository)
    {
        $this->userRepository = $userRepository;
        $this->promotionKindRepository = $promotionKindRepository;
        $this->faker = Faker\Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $users = $this->userRepository->findAll();
        $kinds = $this->promotionKindRepository->findAll();

        foreach ($users as $user) {
            $promotion = $this->makeOne($user, $kinds[array_rand($kinds)]);
            $manager->persist($promotion);

            if ($user->getId() % 2 == 0) {
                $promotion = $this->makeOne($user, $kinds[array_rand($kinds)]);
                $manager->persist($promotion);
            }
        }

        $manager->flush();
    }

    protected function makeOne(User $user, PromotionKind $kind): Promotion
    {
        $promotion = new Promotion();
        $promotion->setCreatedAt(new \DateTime('now'));
        $promotion->setAuthor($user);
        $promotion->setKind($kind);
        $promotion->setTitle($this->faker->sentence($user->getId() % 2 === 0 ? 2 : 3));
        $promotion->setCompany($this->faker->sentence($user->getId() % 2 === 0 ? 1 : 2));
        $promotion->setContent($this->faker->sentence(50));
        $promotion->setDeliveryFees($this->faker->randomFloat(2, 0, 15));
        $promotion->setDiscount($this->faker->randomNumber(2));

        return $promotion;
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            PromotionKindFixtures::class,
        ];
    }
}
