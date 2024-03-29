<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CommentRepository::class)
 */
class Comment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * The message
     *
     * @ORM\Column(type="text", nullable=true)
     * @Assert\Length(
     *     max=500,
     *     maxMessage="Message is too long (max 500)"
     * )
     */
    private $message;

    /**
     * The rating
     *
     * @ORM\Column(type="float")
     * @Assert\Range(
     *     min=1,
     *     max=5,
     *     notInRangeMessage="comment.rating.notInRange"
     * )
     */
    private $rating;

    /**
     * The product
     *
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="comments")
     */
    private $product;

    /**
     * The user
     *
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     */
    private $user;

    /**
     * Date of creation (auto generate in the constructor)
     *
     * @ORM\Column(type="datetime")
     */
    private \DateTimeInterface $createdAt;

    /**
     * Date of updates/editions
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $updatedAt;

    /**
     * Modarate an comment by an administrator
     *
     * @ORM\Column(type="boolean")
     */
    private $isModerate = false;

    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(float $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }


    public function setProduct(Product $product): self
    {
        $this->product = $product;
        return $this;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getIsModerate(): ?bool
    {
        return $this->isModerate;
    }

    public function setIsModerate(bool $isModerate): self
    {
        $this->isModerate = $isModerate;

        return $this;
    }
}
