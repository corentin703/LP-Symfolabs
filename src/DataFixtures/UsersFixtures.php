<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UsersFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setPseudo('Corentin VÉROT');
        $user->setEmail('corentin.verot@outlook.com');
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, '123456789')
        );
        $user->setRoles([
            "ROLE_ADMIN",
        ]);
        $manager->persist($user);

        $user = new User();
        $user->setPseudo('Nicolas MORIN');
        $user->setEmail('Nicolas.MORIN@etu.uca.fr');
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, '123456789')
        );
        $user->setRoles([
            "ROLE_ADMIN",
        ]);
        $manager->persist($user);

        // Generated by https://www.fakenamegenerator.com/gen-random-fr-fr.php
        $user = new User();
        $user->setPseudo('Émile Barrière');
        $user->setEmail('EmileBarriere@jourrapide.com');
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, 'ooH8shu6')
        );
        $manager->persist($user);

        $user = new User();
        $user->setPseudo('Aubrey Trépanier');
        $user->setEmail('AubreyTrepanier@armyspy.com');
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, 'Aigohgahkau5')
        );
        $manager->persist($user);

        $user = new User();
        $user->setPseudo('Guillaume Faubert');
        $user->setEmail('GuillaumeFaubert@dayrep.com');
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, 're1rojaW')
        );
        $manager->persist($user);

        $user = new User();
        $user->setPseudo('Hélène Carrière');
        $user->setEmail('HeleneCarriere@rhyta.com');
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, 'mueRie4ew')
        );
        $manager->persist($user);

        $manager->flush();
    }
}