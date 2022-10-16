<?php

namespace App\Factory;

use App\Entity\CustomerAddress;

class CustomerAddressFactory
{
    public static function create(array $data): CustomerAddress
    {
        $customerAddress = new CustomerAddress();
        $customerAddress->setCustomerId($data["customerId"]);
        $customerAddress->setAddress($data["address"]);

        return $customerAddress;
    }
}