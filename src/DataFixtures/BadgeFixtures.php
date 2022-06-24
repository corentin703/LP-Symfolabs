<?php

namespace App\DataFixtures;

use App\Entity\Badge;
use App\Entity\Comment;
use App\Entity\Promotion;
use App\Entity\Temperature;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BadgeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $badge = new Badge();
        $badge->setName('Surveillant');
        $badge->setType(Temperature::class);
        $badge->setDelta(10);

        $manager->persist($badge);

        $badge = new Badge();
        $badge->setName('Cobaye');
        $badge->setDelta(10);
        $badge->setType(Promotion::class);
        $manager->persist($badge);

        $badge = new Badge();
        $badge->setName('Rapport de stage');
        $badge->setDelta(10);
        $badge->setType(Comment::class);
        $manager->persist($badge);

        $manager->flush();
    }
}
