<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private UserPasswordHasherInterface $passwordHasher;
    private Faker\Generator $faker;

    /**
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
        $this->faker = Faker\Factory::create('fr_FR');
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

        $N_USER_TO_FAKE = 15;
        for ($i = 0; $i < $N_USER_TO_FAKE; ++$i) {
            $user = new User();
            $user->setPseudo($this->faker->userName);
            $user->setEmail($this->faker->email);
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, '123456789')
            );
            $manager->persist($user);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
          BadgeFixtures::class,
        ];
    }
}
