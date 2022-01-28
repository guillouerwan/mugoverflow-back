<?php

namespace App\Entity;

use App\Repository\MainColorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=MainColorRepository::class)
 */
class MainColor
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank
     */
    private $mainHexa;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank
     * @Groups({"get_products", "get_product"})
     */
    private $mainColorName;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     * @Groups({"get_products", "get_product"})
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="mainColor")
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMainHexa(): ?string
    {
        return $this->mainHexa;
    }

    public function setMainHexa(string $mainHexa): self
    {
        $this->mainHexa = $mainHexa;

        return $this;
    }

    public function getMainColorName(): ?string
    {
        return $this->mainColorName;
    }

    public function setMainColorName(string $mainColorName): self
    {
        $this->mainColorName = $mainColorName;

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

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setMainColor($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getMainColor() === $this) {
                $product->setMainColor(null);
            }
        }

        return $this;
    }
}
