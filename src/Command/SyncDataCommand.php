<?php

namespace App\Command;

use App\Core\Container;
use App\Model\CommandInterface;
use App\Service\ExcelService;

class SyncDataCommand implements CommandInterface
{
    private static string $commandName = "app:sync:data";
    private Container $container;
    private ExcelService $excelService;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->container = new Container();
        $this->excelService = $this->container->get(ExcelService::class);
    }

    /**
     * @throws \Exception
     */
    public function runCommand(array $argv): void
    {
        if (isset($argv[1])) {
            if ($argv[1] === self::$commandName) {
                $this->doRun();
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function doRun(): void
    {
        try {
            $this->excelService->addFileDataToDatabase("data.xlsx");
        } catch (\Exception $e) {
            throw new \Exception("An error occurred while adding the data to the database because of {$e->getMessage()}, please make sure you ran the command './public/exec-command app:create:db:tables' to create the database tables");
        }
    }
}
