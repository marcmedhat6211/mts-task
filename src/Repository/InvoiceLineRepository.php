<?php

namespace App\Repository;

use App\Core\Container;
use App\Entity\InvoiceLine;
use App\Service\DatabaseService;

class InvoiceLineRepository
{
    const TABLE_NAME = "invoice_line";

    private Container $container;
    private DatabaseService $databaseService;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->container = new Container();
        $this->databaseService = $this->container->get(DatabaseService::class);
    }

    /**
     * @throws \Exception
     */
    public function createInvoiceLine(InvoiceLine $invoiceLine)
    {
        $data = [
            "product_id" => $invoiceLine->getProductId(),
            "invoice_id" => $invoiceLine->getInvoiceId(),
            "quantity" => $invoiceLine->getQuantity(),
            "total_price" => $invoiceLine->getTotalPrice(),
        ];

        $this->databaseService->insert(self::TABLE_NAME, $data);
    }
}