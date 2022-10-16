<?php

namespace App\Command;

use App\Core\Container;
use App\Model\CommandInterface;
use App\Service\DatabaseService;

class CreateTablesCommand implements CommandInterface
{
    private static string $commandName = "app:create:db:tables";

    const TABLE_CUSTOMER = "customer";
    const TABLE_CUSTOMER_ADDRESS = "customer_address";
    const TABLE_PRODUCT = "product";
    const TABLE_INVOICE = "invoice";
    const TABLE_INVOICE_LINE = "invoice_line";

    const TABLES = [
        self::TABLE_CUSTOMER,
        self::TABLE_CUSTOMER_ADDRESS,
        self::TABLE_PRODUCT,
        self::TABLE_INVOICE,
        self::TABLE_INVOICE_LINE,
    ];

    private Container $container;
    private DatabaseService $databaseService;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->container = new Container();
        try {
            $this->databaseService = $this->container->get(DatabaseService::class);
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    public function runCommand(array $argv): void
    {
        if (isset($argv[1])) {
            if ($argv[1] === self::$commandName) {
                var_dump("heree");
                die();
                try {
                    $this->createTables();
                    return;
                } catch (\Exception $exception) {
                    echo "An error occurred while creating the tables because of this error {$exception->getMessage()}, reverting database to its initial state...";
                    try {
                        $this->revert();
                    } catch (\Exception $exception) {
                        echo "An error occurred while dropping the tables from the database because of this error {$exception->getMessage()}, please drop tables manually from the database";
                    }
                }
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function createTables(): void
    {
        // create customer table
        $tableName = self::TABLE_CUSTOMER;
        $config = [
            "id" => "INT AUTO_INCREMENT NOT NULL PRIMARY KEY",
            "name" => "VARCHAR(255) NOT NULL"
        ];
        $this->databaseService->createTable($tableName, $config);

        // create customer address table
        $tableName = self::TABLE_CUSTOMER_ADDRESS;
        $config = [
            "id" => "INT AUTO_INCREMENT NOT NULL PRIMARY KEY",
            "customer_id" => "INT DEFAULT NULL",
            "building_number" => "INT NOT NULL",
            "province" => "VARCHAR(255) NOT NULL",
            "FOREIGN KEY (customer_id) REFERENCES customer(id)" => ""
        ];
        $this->databaseService->createTable($tableName, $config);

        // create product table
        $tableName = self::TABLE_PRODUCT;
        $config = [
            "id" => "INT AUTO_INCREMENT NOT NULL PRIMARY KEY",
            "name" => "VARCHAR(255) NOT NULL",
            "price" => "DOUBLE PRECISION NOT NULL",
        ];
        $this->databaseService->createTable($tableName, $config);

        // create invoice table
        $tableName = self::TABLE_INVOICE;
        $config = [
            "id" => "INT AUTO_INCREMENT NOT NULL PRIMARY KEY",
            "customer_id" => "INT DEFAULT NULL",
            "date" => "DATETIME NOT NULL",
            "grand_total" => "DOUBLE PRECISION NOT NULL",
            "FOREIGN KEY (customer_id) REFERENCES customer(id)" => ""
        ];
        $this->databaseService->createTable($tableName, $config);

        // create invoice line table (to handle each invoice's item)
        $tableName = self::TABLE_INVOICE_LINE;
        $config = [
            "id" => "INT AUTO_INCREMENT NOT NULL PRIMARY KEY",
            "product_id" => "INT DEFAULT NULL",
            "invoice_id" => "INT DEFAULT NULL",
            "quantity" => "INT NOT NULL",
            "total_price" => "DOUBLE PRECISION NOT NULL",
            "FOREIGN KEY (product_id) REFERENCES product(id)" => "",
            "FOREIGN KEY (invoice_id) REFERENCES invoice(id)" => "",
        ];
        $this->databaseService->createTable($tableName, $config);
    }

    /**
     * @throws \Exception
     */
    public function revert()
    {
        $tablesToDrop = [];
        for ($i = count(self::TABLES) - 1; $i >= 0; $i--) { // for foreign key dependencies
            $tablesToDrop[] = self::TABLES[$i];
        }

        $this->databaseService->dropTables($tablesToDrop);
    }
}
