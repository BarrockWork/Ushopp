<?php

namespace App\Entity;

use App\Repository\OrderDetailsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=OrderDetailsRepository::class)
 */
class OrderDetails
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * The product
     *
     * @ORM\ManyToOne(targetEntity=Product::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * The quantity
     *
     * @ORM\Column(type="integer")
     * @Assert\PositiveOrZero(
     *     message="order.quantity.positiveOrZero"
     * )
     */
    private $quantity;

    /**
     * The current price
     *
     * @ORM\Column(type="float")
     * @Assert\PositiveOrZero(
     *     message="order.price.positiveOrZero"
     * )
     */
    private $price;

    /**
     * The order (entity) of the customer
     *
     * @ORM\ManyToOne(targetEntity=OrderShop::class, inversedBy="orderDetails")
     * @ORM\JoinColumn(nullable=false)
     */
    private $orderShop;

    /**
     * Price with total taxes included
     *
     * @ORM\Column(type="float")
     * @Assert\PositiveOrZero(
     *     message="order.priceTTC.positiveOrZero"
     * )
     */
    private $priceTTC;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

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

    public function getOrderShop(): ?OrderShop
    {
        return $this->orderShop;
    }

    public function setOrderShop(?OrderShop $orderShop): self
    {
        $this->orderShop = $orderShop;

        return $this;
    }

    public function getPriceTtc(): ?float
    {
        return $this->priceTTC;
    }

    public function setPriceTtc(float $priceTTC): self
    {
        $this->priceTTC = $priceTTC;

        return $this;
    }
}
