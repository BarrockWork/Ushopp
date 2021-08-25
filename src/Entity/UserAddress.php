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
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank
     * @Assert\Length(
     *     max=50,
     *     maxMessage="Veuillez respectez le nombre {{limit}} de caractère maximum"
     * )
     */
    private $city;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Assert\Length(
     *     max=25,
     *     maxMessage="Veuillez respectez le nombre {{limit}} de caractère maximum"
     * )
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=5)
     * @Assert\NotBlank
     * @Assert\Length(
     *     max=5,
     *     maxMessage="Veuillez respectez le nombre {{limit}} de caractère maximum"
     * )
     */
    private $zipCode;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank
     * @Assert\Length(
     *     max=25,
     *     maxMessage="Veuillez respectez le nombre {{limit}} de caractère maximum"
     * )
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=25)
     * @Assert\NotBlank
     * @Assert\Length(
     *     max=25,
     *     maxMessage="Veuillez respectez le nombre {{limit}} de caractère maximum"
     * )
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=25)
     * @Assert\NotBlank
     * @Assert\Length(
     *     max=25,
     *     maxMessage="Veuillez respectez le nombre {{limit}} de caractère maximum"
     * )
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\Length(
     *     max=50,
     *     maxMessage="Veuillez respectez le nombre {{limit}} de caractère maximum"
     * )
     */
    private $others;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="userAddress")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->user = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
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

    /**
     * @return ArrayCollection
     */
    public function getUser(): ArrayCollection
    {
        return $this->user;
    }

    /**
     * @param ArrayCollection $user
     */
    public function setUser(ArrayCollection $user): void
    {
        $this->user = $user;
    }


}
