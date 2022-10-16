<?php

namespace App\Factory;

use App\Entity\InvoiceLine;

class InvoiceLineFactory
{
    public static function create(array $data): InvoiceLine
    {
        $invoiceLine = new InvoiceLine();
        $invoiceLine->setInvoiceId($data["invoiceId"]);
        $invoiceLine->setProductId($data["productId"]);
        $invoiceLine->setQuantity($data["quantity"]);
        $invoiceLine->setTotalPrice($data["totalPrice"]);

        return $invoiceLine;
    }
}