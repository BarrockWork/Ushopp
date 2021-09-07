<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CompanyRepository::class)
 */
class Company
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
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
     * @ORM\Column(type="string", length=255)
     */
    private $nameSlug;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank(
     *     message="global.notBlank"
     * )
     */
    private $phoneNumber;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(
     *     message="global.notBlank"
     * )
     * @Assert\Email(
     *     message = "user.email.valid"
     * )
     */
    private $email;

    /**
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
    private $zipcode;

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
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Length(
     *     max=100,
     *     maxMessage="user.address.others.maxLength"
     * )
     */
    private $others;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message="global.notBlank"
     * )
     * @Assert\Length(
     *     max=255,
     *     maxMessage="company.siretLength"
     * )
     */
    private $siretNumber;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message="global.notBlank"
     * )
     * @Assert\Length(
     *     max=255,
     *     maxMessage="company.tvaLength"
     * )
     */
    private $tvaNumber;

    public function __construct() {
        $this->createdAt = new \DateTimeImmutable('now');
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

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getOthers(): ?string
    {
        return $this->others;
    }

    public function setOthers(?string $others): self
    {
        $this->others = $others;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getSiretNumber(): ?string
    {
        return $this->siretNumber;
    }

    public function setSiretNumber(string $siretNumber): self
    {
        $this->siretNumber = $siretNumber;

        return $this;
    }

    public function getTvaNumber(): ?string
    {
        return $this->tvaNumber;
    }

    public function setTvaNumber(string $tvaNumber): self
    {
        $this->tvaNumber = $tvaNumber;

        return $this;
    }
}
