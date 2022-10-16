<?php

namespace App\Factory;

use App\Entity\Customer;

class CustomerFactory
{
    public static function create(array $data): Customer
    {
        $customer = new Customer();
        $customer->setName($data["name"]);

        return $customer;
    }
}