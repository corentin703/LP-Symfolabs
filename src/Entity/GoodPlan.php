<?php

namespace App\Entity;

use App\Repository\GoodPlanRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GoodPlanRepository::class)
 */
class GoodPlan extends Promotion
{
    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $link;

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }
}
