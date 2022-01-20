<?php

namespace App\Entity;

use App\Repository\SecondaryColorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SecondaryColorRepository::class)
 */
class SecondaryColor
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
    private $secondaryHexa;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $secondaryColorName;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="secondaryColor")
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

    public function getSecondaryHexa(): ?string
    {
        return $this->secondaryHexa;
    }

    public function setSecondaryHexa(string $secondaryHexa): self
    {
        $this->secondaryHexa = $secondaryHexa;

        return $this;
    }

    public function getSecondaryColorName(): ?string
    {
        return $this->secondaryColorName;
    }

    public function setSecondaryColorName(string $secondaryColorName): self
    {
        $this->secondaryColorName = $secondaryColorName;

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
            $product->setSecondaryColor($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getSecondaryColor() === $this) {
                $product->setSecondaryColor(null);
            }
        }

        return $this;
    }
}
