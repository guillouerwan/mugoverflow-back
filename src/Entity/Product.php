<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;



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
     * @ORM\Column(type="string", length=64)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Groups({"get_products", "get_product"})

     */
    private $description;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     * @Groups({"get_products", "get_product"})
     */
    private $mockupFront;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $mockupBack;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $image;

    /**
     * @ORM\Column(type="string", length=128, nullable=true)
     */
    private $logo;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=MainColor::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     */
    private $mainColor;

    /**
     * @ORM\ManyToOne(targetEntity=SecondaryColor::class, inversedBy="products")
     */
    private $secondaryColor;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="products")
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity=Category::class, inversedBy="products")
     */
    private $category;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->category = new ArrayCollection();
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

    public function getMockupFront(): ?string
    {
        return $this->mockupFront;
    }

    public function setMockupFront(?string $mockupFront): self
    {
        $this->mockupFront = $mockupFront;

        return $this;
    }

    public function getMockupBack(): ?string
    {
        return $this->mockupBack;
    }

    public function setMockupBack(?string $mockupBack): self
    {
        $this->mockupBack = $mockupBack;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

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

    public function getMainColor(): ?MainColor
    {
        return $this->mainColor;
    }

    public function setMainColor(?MainColor $mainColor): self
    {
        $this->mainColor = $mainColor;

        return $this;
    }

    public function getSecondaryColor(): ?SecondaryColor
    {
        return $this->secondaryColor;
    }

    public function setSecondaryColor(?SecondaryColor $secondaryColor): self
    {
        $this->secondaryColor = $secondaryColor;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->user->removeElement($user);

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategory(): Collection
    {
        return $this->category;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->category->contains($category)) {
            $this->category[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->category->removeElement($category);

        return $this;
    }
}
