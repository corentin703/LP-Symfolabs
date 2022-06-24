<?php

namespace App\EventSubscriber;

use App\Entity\Badge;
use App\Entity\Comment;
use App\Entity\Promotion;
use App\Entity\Temperature;
use App\Entity\User;
use App\Event\BadgeTriggerEvent;
use App\Repository\BadgeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class BadgeTriggerSubscriber implements EventSubscriberInterface
{
    private BadgeRepository $badgeRepository;
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(BadgeRepository $badgeRepository, EntityManagerInterface $entityManager)
    {
        $this->badgeRepository = $badgeRepository;
        $this->entityManager = $entityManager;
    }

    public function onBadgeTrigger(BadgeTriggerEvent $event)
    {
        $user = $event->getUser();
        $badges = $this->badgeRepository->findAll();

        dd($user->getBadges()->getValues());

        foreach ($badges as $badge) {
            if ($user->getBadges()->contains($badge)) {
                continue;
            }

            if ($this->evaluate($badge->getType(), $badge->getDelta(), $user)) {
                $user->addBadge($badge);
            }
        }

        $this->entityManager->flush();
    }

    /**
     * @param string $type
     * @param string $delta
     * @param User $user
     * @return bool True if count >= delta
     */
    public function evaluate(string $type, string $delta, User $user): bool {
        switch ($type) {
            case Promotion::class:
                $count = $user->getPromotions()->count();
                break;
            case Comment::class:
                $count = $user->getComments()->count();
                break;
            case Temperature::class:
                $count = $user->getTemperatures()->count();
                break;
            default:
                return false;
        }

        return $count >= $delta;
    }

    public static function getSubscribedEvents()
    {
        return [
            'badge.trigger' => 'onBadgeTrigger',
        ];
    }
}
