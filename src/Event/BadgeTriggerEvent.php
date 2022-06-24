<?php

namespace App\Event;

use App\Entity\Comment;
use App\Entity\Promotion;
use App\Entity\User;

class BadgeTriggerEvent
{
    const EVENT_DEAL_ADDED = 'EVENT_DEAL_ADDED';
    const EVENT_DEAL_VOTED = 'EVENT_DEAL_VOTED';
    const EVENT_COMMENT_ADDED = 'EVENT_COMMENT_ADDED';

    private string $eventKind;
    private User $user;
    private Promotion $promotion;
    private Comment $comment;

    public function __construct(string $eventKind, User $user, $entity)
    {
        $this->eventKind = $eventKind;
        $this->user = $user;

        if ($entity instanceof Promotion) {
            $this->promotion = $entity;
        } else if ($entity instanceof Comment) {
            $this->comment = $entity;
        }
    }

    /**
     * @return string
     */
    public function getEventKind(): string
    {
        return $this->eventKind;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Promotion
     */
    public function getPromotion(): Promotion
    {
        return $this->promotion;
    }

    /**
     * @return Comment
     */
    public function getComment(): Comment
    {
        return $this->comment;
    }


}