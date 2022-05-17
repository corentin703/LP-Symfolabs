<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UsersFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setPseudo('Jeanne !');
        $user->setEmail('jeanne.au-secours@jeanma.fr');
        $user->setPassword('oskour');
        $manager->persist($user);

        $manager->flush();
    }
}
