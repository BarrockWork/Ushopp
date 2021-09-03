<?php

namespace App\Entity;

use App\Repository\UserAddressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserAddressRepository::class)
 */
class UserAddress
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * A name for the address
     *
     * @ORM\Column(type="string", length=150)
     * @Assert\NotBlank(
     *     message="global.notBlank"
     * )
     * @Assert\Length(
     *     max=150,
     *     maxMessage="user.name.maxLength"
     * )
     */
    private $name;

    /**
     * User's phone
     *
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank(
     *     message="global.notBlank"
     * )
     */
    private $phoneNumber;

    /**
     * The city
     *
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(
     *     message="global.notBlank"
     * )
     * @Assert\Length(
     *     max=50,
     *     maxMessage="user.address.city.maxLength"
     * )
     */
    private $city;

    /**
     * The address
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message="global.notBlank"
     * )
     * @Assert\Length(
     *     max=255,
     *     maxMessage="user.address.address.maxLength"
     * )
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=5)
     * @Assert\NotBlank(
     *     message="global.notBlank"
     * )
     * @Assert\Length(
     *     max=5,
     *     maxMessage="user.address.zipcode.maxLength"
     * )
     */
    private $zipCode;

    /**
     * @ORM\Column(type="string", length=25)
     * @Assert\NotBlank(
     *     message="global.notBlank"
     * )
     * @Assert\Length(
     *     max=25,
     *     maxMessage="user.address.address.maxLength"
     * )
     */
    private $country;

    /**
     * Receiver's firstname
     *
     * @ORM\Column(type="string", length=25)
     * @Assert\NotBlank(
     *     message="global.notBlank"
     * )
     * @Assert\Length(
     *     max=25,
     *     maxMessage="user.firstname.maxLength"
     * )
     */
    private $firstName;

    /**
     * Receiver's lastname
     * @ORM\Column(type="string", length=25)
     * @Assert\NotBlank(
     *     message="global.notBlank"
     * )
     * @Assert\Length(
     *     max=25,
     *     maxMessage="user.lastname.maxLength"
     * )
     */
    private $lastName;

    /**
     * Other infos
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Length(
     *     max=100,
     *     maxMessage="user.address.others.maxLength"
     * )
     */
    private $others;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userAddress")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * Date of creation (auto generate in the constructor)
     *
     * @ORM\Column(type="datetime")
     *
     * @var \DateTimeInterface|null
     */
    private $createdAt;

    /**
     * Date of updates/editions
     *
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @var \DateTimeInterface|null
     */
    private $updatedAt;

    public function __construct(){
        $this->createdAt = new \DateTime();
    }

    public function getId() :?int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city): void
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address): void
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getZipCode()
    {
        return $this->zipCode;
    }

    /**
     * @param mixed $zipCode
     */
    public function setZipCode($zipCode): void
    {
        $this->zipCode = $zipCode;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country): void
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getOthers()
    {
        return $this->others;
    }

    /**
     * @param mixed $others
     */
    public function setOthers($others): void
    {
        $this->others = $others;
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

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeInterface|null $createdAt
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTimeInterface|null $updatedAt
     */
    public function setUpdatedAt(?\DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * @param mixed $phoneNumber
     */
    public function setPhoneNumber($phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }
}
