<?php

namespace App\Entity;

use App\Repository\OrderItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderItemRepository::class)]
class OrderItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?Product $prod = null;

    #[ORM\Column]
    private ?int $Qty = null;

    #[ORM\ManyToOne(inversedBy: 'orderItem')]
    private ?Order $_order = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProd(): ?Product
    {
        return $this->prod;
    }

    public function setProd(?Product $prod): self
    {
        $this->prod = $prod;

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

    public function getOrder(): ?Order
    {
        return $this->_order;
    }

    public function setOrder(?Order $_order): self
    {
        $this->_order = $_order;

        return $this;
    }
}
