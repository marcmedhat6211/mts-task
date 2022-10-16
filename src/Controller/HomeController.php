<?php

namespace App\Controller;

use App\Command\CreateTablesCommand;
use App\Core\Container;
use App\Core\Controller;
use App\Service\DatabaseService;

/**
 * @Inject databaseService
 */
class HomeController extends Controller
{
    private Container $container;

    public function __construct()
    {
        $this->container = new Container();
    }

    /**
     * @throws \Exception
     */
    public function index()
    {
        $databaseService = $this->container->get(DatabaseService::class);
        $createTablesCommand =  $this->container->get(CreateTablesCommand::class);

        try {
//            $createTablesCommand->createTables();
            $createTablesCommand->revert();
            echo "Tables Droped";
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
            die();
            echo "An error occurred while adding the tables to the database, now reverting to the initial state...";
            $createTablesCommand->revert();
            exit();
        }
//        // @todo:test
//        // create customer table
//        $tableName = "customer";
//        $config = [
//            "id" => "INT AUTO_INCREMENT NOT NULL PRIMARY KEY",
//            "name" => "VARCHAR(255) NOT NULL"
//        ];
//        $databaseService->createTable($tableName, $config);
//
//// create customer address table
//        $tableName = "customer_address";
//        $config = [
//            "id" => "INT AUTO_INCREMENT NOT NULL PRIMARY KEY",
//            "customer_id" => "INT DEFAULT NULL",
//            "building_number" => "INT NOT NULL",
//            "province" => "VARCHAR(255) NOT NULL",
//            "FOREIGN KEY (customer_id) REFERENCES customer(id)" => ""
//        ];
//        $databaseService->createTable($tableName, $config);
//
//// create product table
//        $tableName = "product";
//        $config = [
//            "id" => "INT AUTO_INCREMENT NOT NULL PRIMARY KEY",
//            "name" => "VARCHAR(255) NOT NULL",
//            "price" => "DOUBLE PRECISION NOT NULL",
//        ];
//        $databaseService->createTable($tableName, $config);
//
//// create invoice table
//        $tableName = "invoice";
//        $config = [
//            "id" => "INT AUTO_INCREMENT NOT NULL PRIMARY KEY",
//            "customer_id" => "INT DEFAULT NULL",
//            "date" => "DATETIME NOT NULL",
//            "grand_total" => "DOUBLE PRECISION NOT NULL",
//            "FOREIGN KEY (customer_id) REFERENCES customer(id)" => ""
//        ];
//        $databaseService->createTable($tableName, $config);
//
//// create invoice line table (to handle each invoice's item)
//        $tableName = "invoice_line";
//        $config = [
//            "id" => "INT AUTO_INCREMENT NOT NULL PRIMARY KEY",
//            "product_id" => "INT DEFAULT NULL",
//            "invoice_id" => "INT DEFAULT NULL",
//            "quantity" => "INT NOT NULL",
//            "total_price" => "DOUBLE PRECISION NOT NULL",
//            "FOREIGN KEY (product_id) REFERENCES product(id)" => "",
//            "FOREIGN KEY (invoice_id) REFERENCES invoice(id)" => "",
//        ];
//        $databaseService->createTable($tableName, $config);
//
//        echo "tables added successfully";
//        // @todo:end test


        $this->view("home/index", [
            "name" => "marc"
        ]);
//        exec()
    }
}