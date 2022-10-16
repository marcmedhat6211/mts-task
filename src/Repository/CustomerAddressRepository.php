<?php

namespace App\Repository;

use App\Core\Container;
use App\Entity\CustomerAddress;
use App\Model\AbstractRepository;
use App\Service\DatabaseService;

class CustomerAddressRepository extends AbstractRepository
{
    const TABLE_NAME = "customer_address";

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
    public function createCustomerAddress(CustomerAddress $customerAddress)
    {
        $data = [
            "customer_id" => $customerAddress->getCustomerId(),
            "address" => $customerAddress->getAddress()
        ];

        $this->databaseService->insert(self::TABLE_NAME, $data);
    }

    /**
     * @throws \Exception
     */
    public function isCustomerAddressExistsByAddress(string $address): bool // this should be an email but there is no email in the file
    {
        $existingCustomerAddress = $this->findBy(["address" => $address]);

        if (is_array($existingCustomerAddress) && array_key_exists("id", $existingCustomerAddress)) {
            return true;
        }

        return false;
    }
}