<?php

namespace App\Entity;

use App\Repository\PromotionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PromotionRepository::class)
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({ "promotion" = "Promotion", "goodPlan" = "GoodPlan" })
 */
class Promotion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(groups={"promoForm"})
     */
    private ?string $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(groups={"promoForm"})
     */
    private ?string $content;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(groups={"promoForm"})
     */
    private ?string $discount;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\NotBlank(groups={"promoForm"})
     * @Assert\Type("\DateTimeInterface")(groups={"promoForm"})
     */
    private ?\DateTimeInterface $start_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\NotBlank(groups={"promoForm"})
     * @Assert\Type("\DateTimeInterface")(groups={"promoForm"})
     */
    private ?\DateTimeInterface $expires_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $became_hot_at;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Assert\NotBlank(groups={"promoForm"})
     */
    private ?float $delivery_fees;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(groups={"promoForm"})
     */
    private ?string $company;

    /**
     * @Ignore()
     * @ORM\OneToMany(targetEntity=Temperature::class, mappedBy="promotion", orphanRemoval=true)
     */
    private Collection $temperatures;

    /**
     * @Ignore()
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="promotion", orphanRemoval=true)
     */
    private Collection $comments;

    /**
     * @Ignore()
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="promotions")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $author;

    /**
     * @Ignore()
     * @ORM\ManyToOne(targetEntity=PromotionKind::class, inversedBy="promotions")
     * @ORM\JoinColumn(nullable=false)
     */
    private PromotionKind $kind;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private ?int $viewCount;

    /**
     * @ORM\Column(type="boolean", options={ "default": false })
     */
    private bool $isDisabled = false;

    public function __construct()
    {
        $this->temperatures = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getDiscount(): ?string
    {
        return $this->discount;
    }

    public function setDiscount(string $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getStartAt(): ?\DateTimeInterface
    {
        return $this->start_at;
    }

    public function setStartAt(?\DateTimeInterface $start_at): self
    {
        $this->start_at = $start_at;

        return $this;
    }

    public function getExpiresAt(): ?\DateTimeInterface
    {
        return $this->expires_at;
    }

    public function setExpiresAt(?\DateTimeInterface $expires_at): self
    {
        $this->expires_at = $expires_at;

        return $this;
    }

    public function getBecameHotAt(): ?\DateTimeInterface
    {
        return $this->became_hot_at;
    }

    public function setBecameHotAt(?\DateTimeInterface $became_hot_at): self
    {
        $this->became_hot_at = $became_hot_at;

        return $this;
    }

    public function getDeliveryFees(): ?float
    {
        return $this->delivery_fees;
    }

    public function setDeliveryFees(?float $delivery_fees): self
    {
        $this->delivery_fees = $delivery_fees;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): self
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @return Collection|Temperature[]
     */
    public function getTemperatures(): Collection
    {
        return $this->temperatures;
    }

    public function addTemperature(Temperature $temperature): self
    {
        if (!$this->temperatures->contains($temperature)) {
            $this->temperatures[] = $temperature;
            $temperature->setPromotion($this);
        }

        return $this;
    }

    public function removeTemperature(Temperature $temperature): self
    {
        if ($this->temperatures->removeElement($temperature)) {
            // set the owning side to null (unless already changed)
            if ($temperature->getPromotion() === $this) {
                $temperature->setPromotion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setPromotion($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPromotion() === $this) {
                $comment->setPromotion(null);
            }
        }

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getKind(): ?PromotionKind
    {
        return $this->kind;
    }

    public function setKind(?PromotionKind $kind): self
    {
        $this->kind = $kind;

        return $this;
    }

    public function getViewCount(): int
    {
        return $this->viewCount ?? 0;
    }

    public function setViewCount(int $viewCount): self
    {
        $this->viewCount = $viewCount;
        return $this;
    }

    public function getIsDisabled(): ?bool
    {
        return $this->isDisabled;
    }

    public function setIsDisabled(bool $isDisabled): self
    {
        $this->isDisabled = $isDisabled;

        return $this;
    }
}
