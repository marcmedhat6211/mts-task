<?php

namespace App\Factory;

use App\Entity\Invoice;

class InvoiceFactory
{
    public static function create(array $data): Invoice
    {
        $invoice = new Invoice();
        $invoice->setId($data["id"]);
        $invoice->setCustomerId($data["customerId"]);
        $invoice->setGrandTotal($data["grandTotal"]);
        $invoice->setDate($data["date"]);

        return $invoice;
    }
}