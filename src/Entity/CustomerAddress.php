<?php

namespace App\Entity;

class CustomerAddress
{
    private int $id;

    private int $buildingNumber = 0;

    private string $province = '';

    private int $customerId = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBuildingNumber(): ?int
    {
        return $this->buildingNumber;
    }

    public function setBuildingNumber(int $buildingNumber): self
    {
        $this->buildingNumber = $buildingNumber;

        return $this;
    }

    public function getProvince(): ?string
    {
        return $this->province;
    }

    public function setProvince(string $province): self
    {
        $this->province = $province;

        return $this;
    }

    public function getCustomerId(): ?int
    {
        return $this->customerId;
    }

    public function setCustomerId(?int $customerId): self
    {
        $this->customerId = $customerId;

        return $this;
    }
}