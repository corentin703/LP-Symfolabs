<?php

namespace App\EventSubscriber;

use App\Event\TemperatureAddedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TemperatureAddedSubscriber implements EventSubscriberInterface
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function onTemperatureAdded(TemperatureAddedEvent $event)
    {
        $promotion = $event->getPromotion();

        if ($promotion->getTemperatures()->count() >= 100 && $promotion->getBecameHotAt() === null) {
            $promotion->setBecameHotAt(new \DateTime());
        } else {
            $promotion->setBecameHotAt(null);
        }

        $this->entityManager->flush();
    }

    public static function getSubscribedEvents()
    {
        return [
            'temperature.added' => 'onTemperatureAdded',
        ];
    }
}
