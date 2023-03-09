<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("product:read")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups("product:read")]
    private ?string $title = null;

    #[ORM\Column]
    #[Groups("product:read")]
    #[Assert\Regex(
        pattern: '/^\d+(\.\d{1,2})?$/',
        message: 'The price must be a valid number with up to 2 decimal places.'
    )]    
    private ?float $price = null;

    #[ORM\Column]
    #[Groups("product:read")]
    #[Assert\Type(type: 'integer', message: 'The quantity must be a valid integer.')]
    private ?int $Qty = null;

    #[ORM\Column(length: 255)]
    #[Groups("product:read")]
    #[Assert\Url(message: 'The image URL "{{ value }}" is not a valid URL.')]
    private ?string $img = null;

    #[ORM\ManyToOne(inversedBy: 'Product')]
    #[Groups("product:read")]
    private ?Category $category = null;

    #[ORM\Column(nullable: true)]
    #[Groups("product:read")]
    #[Assert\Range(
        min: 0,
        max: 5,
        notInRangeMessage : "The rating must be between {{ min }} and {{ max }}",
        invalidMessage : "The rating must be a valid number"
    )]
    private ?int $rating = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __toString() {
        return $this->title;
    }
    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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

    public function getQty(): ?int
    {
        return $this->Qty;
    }

    public function setQty(int $Qty): self
    {
        $this->Qty = $Qty;

        return $this;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(string $img): self
    {
        $this->img = $img;

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

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(?int $rating): self
    {
        $this->rating = $rating;

        return $this;
    }
}
