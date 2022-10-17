<?php

namespace App\Repository;

use App\Core\Container;
use App\Entity\Invoice;
use App\Model\AbstractRepository;
use App\Service\DatabaseService;

class InvoiceRepository extends AbstractRepository
{
    const TABLE_NAME = "invoice";

    private Container $container;
    private DatabaseService $databaseService;

    public function __construct()
    {
        parent::__construct();
        $this->container = new Container();
        $this->databaseService = $this->container->get(DatabaseService::class);
    }

    /**
     * @throws \Exception
     */
    public function createInvoice(Invoice $invoice)
    {
        $data = [
            "id" => $invoice->getId(),
            "customer_id" => $invoice->getCustomerId(),
            "date" => $invoice->getDate()->format("Y-m-d H:m:s"),
            "grand_total" => $invoice->getGrandTotal(),
        ];

        $this->databaseService->insert(self::TABLE_NAME, $data);
    }

    /**
     * @throws \Exception
     */
    public function isInvoiceExists(int|string $invoiceId): bool
    {
        $existingInvoice = $this->findById($invoiceId);

        if (count($existingInvoice) > 0) {
            return true;
        }

        return false;
    }
}