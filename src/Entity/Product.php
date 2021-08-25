<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Product's name
     *
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(
     *     message="global.notBlank"
     * )
     * @Assert\Length(
     *     min=3,
     *     max=50,
     *     minMessage="product.name.minLength",
     *     maxMessage="product.name.maxLength"
     * )
     */
    private $name;

    /**
     * The unique name of product
     * @Assert\NotBlank(
     *     message="global.notBlank"
     * )
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $nameSlug;

    /**
     *
     * Product's reference
     *
     * @ORM\Column(type="string", length=50, unique=true)
     * @Assert\NotBlank(
     *     message="global.notBlank"
     * )
     * @Assert\Length(
     *     max=50,
     *     maxMessage="product.reference.maxLength"
     * )
     * @ORM\Column(type="string", length=50)
     */
    private $reference;

    /**
     * Product's price without taxes
     *
     * @ORM\Column(type="float")
     * @Assert\NotBlank(
     *     message="global.notBlank"
     * )
     * @Assert\PositiveOrZero(
     *     message="product.price.maxLength"
     * )
     */
    private $price;

    /**
     * Product's tax(TVA)
     *
     * @ORM\Column(type="float")
     * @Assert\NotBlank(
     *     message="global.notBlank"
     * )
     *  @Assert\PositiveOrZero(
     *     message="product.tax.postivieOrZero"
     * )
     */
    private $tax;

    /**
     * Product's ecotax
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ecotax;

    /**
     * Product's description
     *
     * @ORM\Column(type="text", length=300)
     * @Assert\NotBlank(
     *     message="global.notBlank"
     * )
     * @Assert\Length(
     *     min=3,
     *     max=300,
     *     minMessage="product.description.minLength"
     *     maxMessage="product.description.maxLength"
     * )
     * @Groups({"product:read","product:write"})
     */
    private $description;

    /**
     * Product's Weight
     * @ORM\Column(type="float", nullable=true)
     */
    private $weight;

    /**
     * Product's size for clothes
     *
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Assert\NotBlank(
     *     message="global.notBlank"
     * )
     * @Assert\Length(
     *     max=20,
     *     maxMessage="product.size.maxLength"
     * )
     */
    private $size;

    /**
     * Product's availability
     * @ORM\Column(type="boolean")
     * @Assert\NotBlank(
     *     message="global.notBlank"
     * )
     */
    private $active = true;

    /**
     * Product's comments ids
     *
     * @ORM\ManyToMany(targetEntity=Comment::class, mappedBy="product")
     */
    private $comments;

    /**
     * Category's ID of the product
     *
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="products")
     */
    private $category;

    /**
     * If the product is a best seller
     *
     * @ORM\Column(type="boolean")
     * @Assert\NotBlank(
     *     message="global.notBlank"
     * )
     */
    private $isBest = false;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        // Set nameSlug automatically
        $slugger = new Slugify();
        $this->setNameSlug($slugger->slugify($name));

        return $this;
    }

    public function getNameSlug(): ?string
    {
        return $this->nameSlug;
    }

    public function setNameSlug(string $nameSlug): self
    {
        $this->nameSlug = $nameSlug;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getTax(): ?int
    {
        return $this->tax;
    }

    public function setTax(int $tax): self
    {
        $this->tax = $tax;

        return $this;
    }

    public function getEcotax(): ?int
    {
        return $this->ecotax;
    }

    public function setEcotax(int $ecotax): self
    {
        $this->ecotax = $ecotax;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(?string $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

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
            $comment->addProduct($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            $comment->removeProduct($this);
        }

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getIsBest(): ?bool
    {
        return $this->isBest;
    }

    public function setIsBest(bool $isBest): self
    {
        $this->isBest = $isBest;

        return $this;
    }
}
