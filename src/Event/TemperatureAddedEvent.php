<?php

namespace App\Event;

use App\Entity\Promotion;
use Symfony\Contracts\EventDispatcher\Event;

class TemperatureAddedEvent extends Event
{
    private Promotion $promotion;

    /**
     * @param Promotion $promotion
     */
    public function __construct(Promotion $promotion)
    {
        $this->promotion = $promotion;
    }

    /**
     * @return Promotion
     */
    public function getPromotion(): Promotion
    {
        return $this->promotion;
    }
}