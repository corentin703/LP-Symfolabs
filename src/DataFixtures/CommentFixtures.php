<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Repository\GoodPlanRepository;
use App\Repository\PromotionRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    private GoodPlanRepository $goodPlanRepository;
    private PromotionRepository $promotionRepository;
    private UserRepository $userRepository;
    private Faker\Generator $faker;

    /**
     * @param GoodPlanRepository $goodPlanRepository
     * @param PromotionRepository $promotionRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        GoodPlanRepository $goodPlanRepository,
        PromotionRepository $promotionRepository,
        UserRepository $userRepository
    )
    {
        $this->goodPlanRepository = $goodPlanRepository;
        $this->promotionRepository = $promotionRepository;
        $this->userRepository = $userRepository;
        $this->faker = Faker\Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $goodPlans = $this->goodPlanRepository->findAll();
        $promotions = $this->promotionRepository->findAll();

        $models = [
            ...$goodPlans,
            ...$promotions,
        ];

        $users = $this->userRepository->findAll();

        $N_MIN_COMMENTS_TO_FAKE = 0;
        $N_MAX_COMMENTS_TO_FAKE = 15;

        foreach ($models as $model)
        {
            $nCommentsToFake = rand($N_MIN_COMMENTS_TO_FAKE, $N_MAX_COMMENTS_TO_FAKE);
            for ($i = 0; $i < $nCommentsToFake; ++$i) {
                $comment = new Comment();
                $comment->setPromotion($model);
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
            GoodPlanFixtures::class,
            PromotionFixtures::class,
            UserFixtures::class,
        ];
    }
}
