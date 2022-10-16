<?php

namespace App\Entity;

class Invoice
{
    private int $id;

    private ?\DateTime $date = null;

    private float $grandTotal = 0.0;

    private ?int $customerId = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getGrandTotal(): ?float
    {
        return $this->grandTotal;
    }

    public function setGrandTotal(float $grandTotal): self
    {
        $this->grandTotal = $grandTotal;

        return $this;
    }

    public function getCustomerId(): ?int
    {
        return $this->customerId;
    }

    public function setCustomer(?int $customerId): self
    {
        $this->customerId = $customerId;

        return $this;
    }
}