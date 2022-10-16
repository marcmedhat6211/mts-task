<?php

namespace App\Repository;

use App\Core\Container;
use App\Entity\Product;
use App\Model\AbstractRepository;
use App\Service\DatabaseService;

class ProductRepository extends AbstractRepository
{
    const TABLE_NAME = "product";

    private Container $container;
    private DatabaseService $databaseService;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        parent::__construct();
        $this->container = new Container();
        $this->databaseService = $this->container->get(DatabaseService::class);
    }

    /**
     * @throws \Exception
     */
    public function createProduct(Product $product)
    {
        $data = [
            "name" => $product->getName(),
            "price" => $product->getPrice(),
        ];

        $this->databaseService->insert(self::TABLE_NAME, $data);
    }

    /**
     * @throws \Exception
     */
    public function isProductExistsByName(string $name): bool
    {
        $existingProduct = $this->findBy(["name" => $name]);

        if (is_array($existingProduct) && array_key_exists("id", $existingProduct)) {
            return true;
        }

        return false;
    }
}