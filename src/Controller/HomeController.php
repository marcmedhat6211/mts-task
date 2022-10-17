<?php

namespace App\Controller;

use App\Command\CreateTablesCommand;
use App\Command\SyncDataCommand;
use App\Core\Container;
use App\Core\Controller;
use App\Service\ObjectService;

class HomeController extends Controller
{
    private Container $container;
    private CreateTablesCommand $createTablesCommand;
    private SyncDataCommand $syncDataCommand;
    private ObjectService $objectService;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->container = new Container();
        $this->createTablesCommand = $this->container->get(CreateTablesCommand::class);
        $this->syncDataCommand = $this->container->get(SyncDataCommand::class);
        $this->objectService = $this->container->get(ObjectService::class);
    }

    /**
     * @throws \Exception
     */
    public function index()
    {
        $this->createTablesCommand->revert(); // removes all tables if exists
        try {
            $this->createTablesCommand->createTables(); // creates the database tables
            echo "Created 5 database tables successfully";
            echo "</br>";
            echo "Table Names are: ";echo "</br>";
            echo "customer";echo "</br>";
            echo "customer_address";echo "</br>";
            echo "invoice";echo "</br>";
            echo "invoice_line";echo "</br>";
            echo "product";echo "</br>";
            echo "=====================================================================";echo "</br>";echo "</br>";echo "</br>";
        } catch (\Exception $exception) {
            echo "A Server Error Occurred while creating the tables because of {$exception->getMessage()}, now reverting database to the initial state...";
            try {
                $this->createTablesCommand->revert(); // removes all tables if exists
                echo "All tables where removed from database successfully";
                die();
            } catch (\Exception $exception) {
                echo "A Server Error Occurred while reverting the database state because of {$exception->getMessage()}, please remove the tables manually from the database";
                die();
            }
        }

        try {
            $this->syncDataCommand->doRun(); // takes the data from the Excel file, maps them, and inserts them to the database
            echo "Data was fetched from the excel file and was persisted successfully to the newly created tables in the database";echo "</br>";
            echo "==================================================================================================================";echo "</br>";echo "</br>";echo "</br>";
            echo "<h1>Invoices Data</h1>";echo "</br>";
        } catch (\Exception $exception) {
            echo "An error occurred while fetching data and adding it to the database because of {$exception->getMessage()}";
            die();
        }

        $invoices = json_encode($this->objectService->getDataInArray());
        $this->view("home/index", [
            "invoices" => $invoices
        ]);
    }
}