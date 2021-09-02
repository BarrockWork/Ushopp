<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 */
class Order
{
    /**
     * Status infos
     */
    const STATUS = [
        0 => 'En cours de validation',
        1 => 'Validée',
        2 => 'En cours de préparation',
        3 => 'Expédiée',
        4 => 'Livrée'
    ];


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * The customer
     *
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orders")
     */
    private $user;

    /**
     * Date of the order
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;


    /**
     * Date of updates/editions
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $updatedAt;

    /**
     * Status of the order
     *
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *     min=0,
     *     max=4,
     *     notInRangeMessage="order.status.notInRange"
     * )
     */
    private $status;

    /**
     * Date of payment
     * @ORM\Column(type="datetime")
     */
    private $paymentAt;

    /**
     * Delivery address
     *
     * @ORM\Column(type="text")
     
     */
    private $deliveryAddress;

    /**
     * Details of the order
     *
     * @ORM\OneToMany(targetEntity=OrderDetails::class, mappedBy="orderUser")
     */
    private $orderDetails;

    /**
     * Name of the carrier
     *
     * @ORM\Column(type="string", length=50)
     */
    private $carrier_name;

    /**
     * Price of the carrier (TTC)
     *
     * @ORM\Column(type="float")
     */
    private $carrier_price;

    public function __construct()
    {
        $this->orderDetails = new ArrayCollection();
        // Initialize today's date per default
        $this->createdAt = new \DateTime('now');
    }

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

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPaymentAt(): ?\DateTimeInterface
    {
        return $this->paymentAt;
    }

    public function setPaymentAt(\DateTimeInterface $paymentAt): self
    {
        $this->paymentAt = $paymentAt;

        return $this;
    }

    public function getDeliveryAddress(): ?string
    {
        return $this->deliveryAddress;
    }

    public function setDeliveryAddress(string $deliveryAddress): self
    {
        $this->deliveryAddress = $deliveryAddress;

        return $this;
    }

    /**
     * @return Collection|OrderDetails[]
     */
    public function getOrderDetails(): Collection
    {
        return $this->orderDetails;
    }

    public function addOrderDetail(OrderDetails $orderDetail): self
    {
        if (!$this->orderDetails->contains($orderDetail)) {
            $this->orderDetails[] = $orderDetail;
            $orderDetail->setOrders($this);
        }

        return $this;
    }

    public function removeOrderDetail(OrderDetails $orderDetail): self
    {
        if ($this->orderDetails->removeElement($orderDetail)) {
            // set the owning side to null (unless already changed)
            if ($orderDetail->getOrders() === $this) {
                $orderDetail->setOrders(null);
            }
        }

        return $this;
    }

    public function getCarrierName(): ?string
    {
        return $this->carrier_name;
    }

    public function setCarrierName(string $carrier_name): self
    {
        $this->carrier_name = $carrier_name;

        return $this;
    }

    public function getCarrierPrice(): ?float
    {
        return $this->carrier_price;
    }

    public function setCarrierPrice(float $carrier_price): self
    {
        $this->carrier_price = $carrier_price;

        return $this;
    }
}
