<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Repository\PromotionRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    private PromotionRepository $promotionRepository;
    private UserRepository $userRepository;
    private Faker\Generator $faker;

    /**
     * @param PromotionRepository $promotionRepository
     * @param UserRepository $userRepository
     */
    public function __construct(PromotionRepository $promotionRepository, UserRepository $userRepository)
    {
        $this->promotionRepository = $promotionRepository;
        $this->userRepository = $userRepository;
        $this->faker = Faker\Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $promotions = $this->promotionRepository->findAll();
        $users = $this->userRepository->findAll();

        $N_MIN_COMMENTS_TO_FAKE = 0;
        $N_MAX_COMMENTS_TO_FAKE = 15;

        foreach ($promotions as $promotion)
        {
            $nCommentsToFake = rand($N_MIN_COMMENTS_TO_FAKE, $N_MAX_COMMENTS_TO_FAKE);
            for ($i = 0; $i < $nCommentsToFake; ++$i) {
                $comment = new Comment();
                $comment->setPromotion($promotion);
                $comment->setAuthor($users[array_rand($users)]);
                $comment->setCreatedAt(new \DateTime('now'));
                $comment->setContent($this->faker->sentence(50));

                $manager->persist($comment);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            PromotionFixtures::class,
            UserFixtures::class,
        ];
    }
}
