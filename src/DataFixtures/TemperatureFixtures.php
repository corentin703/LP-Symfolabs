<?php

namespace App\DataFixtures;

use App\Entity\Promotion;
use App\Entity\Temperature;
use App\Entity\User;
use App\Repository\GoodPlanRepository;
use App\Repository\PromotionRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class TemperatureFixtures extends Fixture implements DependentFixtureInterface
{
    private UserRepository $userRepository;
    private PromotionRepository $promotionRepository;
    private GoodPlanRepository $goodPlanRepository;
    private Faker\Generator $faker;

    /**
     * @param UserRepository $userRepository
     * @param PromotionRepository $promotionRepository
     * @param GoodPlanRepository $goodPlanRepository
     */
    public function __construct(UserRepository $userRepository, PromotionRepository $promotionRepository, GoodPlanRepository $goodPlanRepository)
    {
        $this->userRepository = $userRepository;
        $this->promotionRepository = $promotionRepository;
        $this->goodPlanRepository = $goodPlanRepository;
        $this->faker = Faker\Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $users = $this->userRepository->findAll();

        $promotions = array_merge(
            $this->promotionRepository->findAll(),
            $this->goodPlanRepository->findAll()
        );

        foreach ($promotions as $promotion) {
            $temperatureCount = $this->faker->randomNumber(2);
            $counter = 0;

            while ($counter < $temperatureCount) {
                $temperature = $this->makeTemperature($promotion, $users[array_rand($users)]);
                $manager->persist($temperature);

                $counter++;
            }
        }

        $manager->flush();
    }

    private function makeTemperature(Promotion $promotion, User $user): Temperature {
        $temperature = new Temperature();
        $temperature->setPositive($this->faker->boolean());
        $temperature->setUser($user);
        $temperature->setPromotion($promotion);

        return $temperature;
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            PromotionFixtures::class,
            GoodPlanFixtures::class,
        ];
    }
}
