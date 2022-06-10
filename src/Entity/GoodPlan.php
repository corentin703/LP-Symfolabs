<?php

namespace App\Entity;

use App\Repository\GoodPlanRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=GoodPlanRepository::class)
 */
class GoodPlan extends Promotion
{
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(groups={"goodPlanForm"})
     * @Assert\Url(groups={"goodPlanForm"})
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
