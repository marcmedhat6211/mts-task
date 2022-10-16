<?php

namespace App\Controller;

use App\Command\CreateTablesCommand;
use App\Core\Container;
use App\Core\Controller;
use App\Repository\InvoiceRepository;
use App\Service\DatabaseService;
use App\Service\ExcelService;

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
        $excelService = $this->container->get(ExcelService::class);
        $invoiceRepo = $this->container->get(InvoiceRepository::class);

        try {
            $excelService->addFileDataToDatabase("data.xlsx");
            echo "data added";
            die();
        } catch (\Exception $exception) {
            echo $exception->getMessage();
            die();
        }


//        $createTablesCommand->revert();
//        $createTablesCommand->createTables();
//        echo "tables created";
//        die();


//
//
//        $result = $databaseService->insert(ProductRepository::TABLE_NAME, [
//            "name" => "test",
//            "price" => 60
//        ]);
//        var_dump($result);
//        die();
//
//        $id = $invoiceRepo->findById(1);
//        $result = $invoiceRepo->isInvoiceExists(1);
//
//
//        $this->view("home/index", [
//            "name" => "marc"
//        ]);
    }
}