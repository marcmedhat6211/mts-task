<?php

namespace App\Factory;

use App\Entity\Product;

class ProductFactory
{
    public static function create(array $data): Product
    {
        $product = new Product();
        $product->setName($data["name"]);
        $product->setPrice($data["price"]);

        return $product;
    }
}