<?php

namespace App\Repository;

use App\Core\Container;
use App\Entity\Customer;
use App\Model\AbstractRepository;
use App\Service\DatabaseService;

class CustomerRepository extends AbstractRepository
{
    const TABLE_NAME = "customer";

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
    public function createCustomer(Customer $customer)
    {
        $data = [
            "name" => $customer->getName()
        ];

        $this->databaseService->insert(self::TABLE_NAME, $data);
    }

    /**
     * @throws \Exception
     */
    public function findById(int|string $id): array|bool
    {
        return $this->findById($id);
    }

    /**
     * @throws \Exception
     */
    public function isCustomerExistsByName(string $customerName): bool // this should be an email but there is no email in the file
    {
        $existingCustomer = $this->findBy(["name" => $customerName]);

        if (is_array($existingCustomer) && array_key_exists("id", $existingCustomer)) {
            return true;
        }

        return false;
    }
}