<?php

namespace App\DataFixtures;

use App\Entity\GoodPlan;
use App\Entity\Promotion;
use App\Entity\PromotionKind;
use App\Entity\User;
use App\Repository\PromotionKindRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class GoodPlanFixtures extends Fixture implements DependentFixtureInterface
{
    private UserRepository $userRepository;
    private PromotionKindRepository $promotionKindRepository;
    private Faker\Generator $faker;

    /**
     * @param UserRepository $userRepository
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
            $goodPlan = $this->makeGoodPlan($user, $kinds[array_rand($kinds)]);
            $manager->persist($goodPlan);

            if ($user->getId() % 2 == 0) {
                $goodPlan = $this->makeGoodPlan($user, $kinds[array_rand($kinds)]);
                $manager->persist($goodPlan);
            }
        }

        $manager->flush();
    }

    private function makeGoodPlan(User $user, PromotionKind $kind): Promotion
    {
        $goodPlan = new GoodPlan();
        $goodPlan->setCreatedAt(new \DateTime('now'));
        $goodPlan->setAuthor($user);
        $goodPlan->setKind($kind);
        $goodPlan->setTitle($this->faker->sentence($user->getId() % 2 === 0 ? 2 : 3));
        $goodPlan->setCompany($this->faker->sentence($user->getId() % 2 === 0 ? 1 : 2));
        $goodPlan->setContent($this->faker->sentence(50));
        $goodPlan->setDeliveryFees($this->faker->randomFloat(2, 0, 15));
        $goodPlan->setDiscount($this->faker->randomNumber(2));
        $goodPlan->setLink($this->faker->url());

        return $goodPlan;
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            PromotionKindFixtures::class,
        ];
    }
}
