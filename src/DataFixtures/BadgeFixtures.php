<?php

namespace App\DataFixtures;

use App\Entity\Badge;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BadgeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $badge = new Badge();
        $badge->setName('Surveillant');
        $manager->persist($badge);

        $badge = new Badge();
        $badge->setName('Cobaye');
        $manager->persist($badge);

        $badge = new Badge();
        $badge->setName('Rapport de stage');
        $manager->persist($badge);

        $manager->flush();
    }
}
