<?php

namespace App\Entity;

use App\Repository\TemperatureRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=TemperatureRepository::class)
 */
class Temperature
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="temperatures")
     * @ORM\JoinColumn(nullable=false)
//     * @Assert\NotBlank(groups={"promoForm"})
     */
    private ?User $user;

    /**
     * @ORM\ManyToOne(targetEntity=Promotion::class, inversedBy="temperatures")
     * @ORM\JoinColumn(nullable=false)
//     * @Assert\NotBlank(groups={"promoForm"})
     */
    private ?Promotion $promotion;

    /**
     * @ORM\Column(type="boolean")
     */
    private $positive;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPromotion(): ?Promotion
    {
        return $this->promotion;
    }

    public function setPromotion(?Promotion $promotion): self
    {
        $this->promotion = $promotion;

        return $this;
    }

    public function getPositive(): ?bool
    {
        return $this->positive;
    }

    public function setPositive(bool $positive): self
    {
        $this->positive = $positive;

        return $this;
    }
}
