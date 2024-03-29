<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * The user's email
     *
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(
     *     message="global.notBlank"
     * )
     * @Assert\Email(
     *     message = "user.email.valid"
     * )
     */
    private $email;

    /**
     * The users's roles
     *
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * The users's password encoded
     *
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * The user's plain password ( not persisted in the database)
     * @var string
     * @Assert\Length(
     *  min=8,
     *  max = 15,
     *  minMessage = "user.plainPassword.minLength",
     *  maxMessage = "user.plainPassword.maxLength"
     * )
     */
    private $plainPassword;

    /**
     * Current password before edit
     *
     * @var string
     */
    private $currentPassword;

    /**
     * User's firstname
     *
     * @ORM\Column(type="string", length=25)
     * @Assert\NotBlank(
     *     message="global.notBlank"
     * )
     * @Assert\Length(
     *     min=2,
     *     max=25,
     *     minMessage="user.firstname.minLength",
     *     maxMessage="user.firstname.maxLength"
     * )
     */
    private $firstName;

    /**
     * User's lastname
     * @ORM\Column(type="string", length=25)
     * @Assert\NotBlank(
     *     message="global.notBlank"
     * )
     * @Assert\Length(
     *     min=2,
     *     max=25,
     *     minMessage="user.lastname.minLength",
     *     maxMessage="user.lastname.maxLength"
     * )
     */
    private $lastName;

    /**
     * User's phone
     *
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank(
     *     message="global.notBlank"
     * )
     * @Assert\Length(
     *     min=8,
     *     max=20,
     *     minMessage="user.phoneNumber.minLength",
     *     maxMessage="user.phoneNumber.maxLength"
     * )
     * @Assert\Regex("/^[0-9]+$/")
     */
    private $phoneNumber;

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

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="user")
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity=OrderShop::class, mappedBy="User")
     */
    private $orderShops;

    /**
     * @ORM\OneToMany(targetEntity=UserAddress::class, mappedBy="user")
     */
    private $userAddress;

    /**
     * @ORM\OneToOne(targetEntity=UserAvatar::class, mappedBy="user", cascade={"persist"})
     */
    private $userAvatar;

    public function __construct()
    {
        $this->userAddress = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->orderShops = new ArrayCollection();
        $this->createdAt = new \DateTime('now');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    /**
     * @return mixed
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string)$this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    /**
     * @return string
     */
    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword(string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return string
     */
    public function getCurrentPassword(): string
    {
        return $this->currentPassword;
    }

    /**
     * @param string $currentPassword
     */
    public function setCurrentPassword(string $currentPassword): void
    {
        $this->currentPassword = $currentPassword;
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
            $comment->setUser($this);
        }
        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUser() === $this) {
                $comment->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|OrderShop[]
     */
    public function getOrderShops(): Collection
    {
        return $this->orderShops;
    }

    public function addOrderShop(OrderShop $orderShop): self
    {
        if (!$this->orderShops->contains($orderShop)) {
            $this->orderShops[] = $orderShop;
            $orderShop->setUser($this);
        }
        return $this;
    }


    public function removeOrderShop(OrderShop $orderShop): self
    {
        if ($this->orderShops->removeElement($orderShop)) {
            // set the owning side to null (unless already changed)
            if ($orderShop->getUser() === $this) {
                $orderShop->setUser(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection|UserAddress[]
     */
    public function getUserAddress(): Collection
    {
        return $this->userAddress;
    }

    public function addUserAddress(UserAddress $userAddress): self
    {
        if (!$this->userAddress->contains($userAddress)) {
            $this->userAddress[] = $userAddress;
            $userAddress->setUser($this);
        }

        return $this;
    }

    public function removeUserAddress(UserAddress $userAddress): self
    {
        if ($this->userAddress->removeElement($userAddress)) {
            // set the owning side to null (unless already changed)
            if ($userAddress->getUser() === $this) {
                $userAddress->setUser(null);
            }
        }

        return $this;
    }

    public function getUserAvatar(): ?UserAvatar
    {
        return $this->userAvatar;
    }

    public function setUserAvatar(?UserAvatar $userAvatar): self
    {
        // unset the owning side of the relation if necessary
        if ($userAvatar === null && $this->userAvatar !== null) {
            $this->userAvatar->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($userAvatar !== null && $userAvatar->getUser() !== $this) {
            $userAvatar->setUser($this);
        }

        $this->userAvatar = $userAvatar;

        return $this;
    }
}
