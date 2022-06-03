<?php

namespace App\DataFixtures;

use App\Entity\PromotionKind;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class PromotionKindFixtures extends Fixture
{
    private Faker\Generator $faker;

    public function __construct()
    {
        $this->faker = Faker\Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $KIND_TO_SEED = 10;

        for ($number = 0; $number < $KIND_TO_SEED; ++$number) {
            $kind = new PromotionKind();
            $kind->setName($this->faker->word());
            $manager->persist($kind);
        }

        $manager->flush();
    }
}
