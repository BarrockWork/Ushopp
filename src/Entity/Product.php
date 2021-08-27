<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @Vich\Uploadable
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
     *     minMessage="product.description.minLength",
     *     maxMessage="product.description.maxLength"
     * )
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
     */
    private $active = true;


    /**
     * If the product is a best seller
     *
     * @ORM\Column(type="boolean")
     */
    private $isBest = false;

    /**
     * Product's thumbnail
     *
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="products_thumbnail", fileNameProperty="imageName", size="imageSize", originalName="originalName", mimeType="mimeType")
     *
     * @var File|null
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imageName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $originalName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Image(
     *     mimeTypes="image/jpeg, image/png",
     *     mimeTypesMessage="user.mimeType"
     * )
     */
    private $mimeType;

    /**
     * @ORM\Column(type="integer")
     */
    private $imageSize;

    /**
     * The date of creation (auto-generate in the constructor)
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $createdAt;

    /**
     * The date of updates/editions
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $updatedAt;

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
     * Stock of the product
     *
     * @ORM\OneToOne(targetEntity=ProductStock::class, mappedBy="product", cascade={"persist", "remove"})
     */
    private $productStock;

    /**
     * Multiple images for the product
     *
     * @ORM\OneToMany(targetEntity=ProductImage::class, mappedBy="product", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $productImages;

    /**
     * Stock entry for the forms
     *
     * @var integer
     */
    private $stock;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->productImages = new ArrayCollection();
        $this->createdAt = new \DateTime('now');
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

    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|UploadedFile|null $imageFile
     */

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    /**
     * @return mixed
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * @param mixed $imageName
     */
    public function setImageName($imageName): void
    {
        $this->imageName = $imageName;
    }

    /**
     * @return mixed
     */
    public function getOriginalName()
    {
        return $this->originalName;
    }

    /**
     * @param mixed $originalName
     */
    public function setOriginalName($originalName): void
    {
        $this->originalName = $originalName;
    }

    /**
     * @return mixed
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @param mixed $mimeType
     */
    public function setMimeType($mimeType): void
    {
        $this->mimeType = $mimeType;
    }

    /**
     * @return mixed
     */
    public function getImageSize()
    {
        return $this->imageSize;
    }

    /**
     * @param mixed $imageSize
     */
    public function setImageSize($imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getProductStock(): ?ProductStock
    {
        return $this->productStock;
    }

    public function setProductStock(ProductStock $productStock): self
    {
        // set the owning side of the relation if necessary
        if ($productStock->getProduct() !== $this) {
            $productStock->setProduct($this);
        }

        $this->productStock = $productStock;

        return $this;
    }

    /**
     * @return Collection|ProductImage[]
     */
    public function getProductImages(): Collection
    {
        return $this->productImages;
    }

    public function addProductImage(ProductImage $productImage): self
    {
        if (!$this->productImages->contains($productImage)) {
            $this->productImages[] = $productImage;
            $productImage->setProduct($this);
        }

        return $this;
    }

    public function removeProductImage(ProductImage $productImage): self
    {
        if ($this->productImages->removeElement($productImage)) {
            // set the owning side to null (unless already changed)
            if ($productImage->getProduct() === $this) {
                $productImage->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getStock(): int
    {
        return $this->stock;
    }

    /**
     * @param int $stock
     */
    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }

}
