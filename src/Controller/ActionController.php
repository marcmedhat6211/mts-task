<?php

namespace App\Controller;

use App\Command\CreateTablesCommand;
use App\Command\SyncDataCommand;
use App\Core\Container;
use App\Core\Controller;
use App\Service\ObjectService;

class ActionController extends Controller
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

    public function create()
    {
        try {
            $this->createTablesCommand->createTables(); // creates the database tables
            echo "Tables Created Successfully!";
        } catch (\Exception $exception) {
            echo "A Server Error Occurred while creating the tables because of {$exception->getMessage()}";
        }
    }

    public function drop()
    {
        try {
            $this->createTablesCommand->revert(); // drops the tables from the database
            echo "Tables were removed successfully!";
        } catch (\Exception $exception) {
            echo "A Server Error Occurred while dropping the tables because of {$exception->getMessage()}";
        }
    }

    public function sync()
    {
        try {
            $this->syncDataCommand->doRun(); // syncs the data from the Excel file to the created database
            echo "Data synced successfully!";
        } catch (\Exception $exception) {
            echo "A Server Error Occurred while syncing the data to the database because of {$exception->getMessage()}";
        }
    }

    public function print()
    {
        $invoices = json_encode($this->objectService->getDataInArray());

        $this->view("action/index", [
            "invoices" => $invoices
        ]);
    }
}