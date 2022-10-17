<?php

namespace App\Service;

use App\Core\Container;
use App\Repository\CustomerAddressRepository;
use App\Repository\CustomerRepository;
use App\Repository\InvoiceLineRepository;
use App\Repository\InvoiceRepository;
use App\Repository\ProductRepository;

class ObjectService
{
    private Container $container;
    private CustomerRepository $customerRepository;
    private CustomerAddressRepository $customerAddressRepository;
    private ProductRepository $productRepository;
    private InvoiceRepository $invoiceRepository;
    private InvoiceLineRepository $invoiceLineRepository;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->container = new Container();
        $this->customerRepository = $this->container->get(CustomerRepository::class);
        $this->customerAddressRepository = $this->container->get(CustomerAddressRepository::class);
        $this->productRepository = $this->container->get(ProductRepository::class);
        $this->invoiceRepository = $this->container->get(InvoiceRepository::class);
        $this->invoiceLineRepository = $this->container->get(InvoiceLineRepository::class);
    }

    /**
     * @throws \Exception
     */
    public function getDataInArray(): array
    {
        $data = [];
        $invoicesRows = $this->invoiceRepository->findAll();
        foreach ($invoicesRows as $invoice) {
            $rowData = [];
            $invoiceLines = [];
            foreach ($invoice as $column => $columnData) {
                if ($column == "id") {
                    $rowData["id"] = $columnData;
                    $invoiceLines = $this->adjustInvoiceLinesArray($columnData);
                }
                if ($column == "date") {
                    $rowData["date"] = $columnData;
                }
                if ($column == "grand_total") {
                    $rowData["grand_total"] = $columnData;
                }
                if ($column == "customer_id") {
                    $rowData["customer"] = $this->getCustomer($this->customerRepository->findById($columnData)[0]);
                    $rowData["customer"]["customer_address"] = $this->getCustomerAddress($this->customerAddressRepository->findBy(["customer_id" => $columnData])[0]);
                }
            }
            $rowData["invoiceLines"] = $invoiceLines;
            $data["invoices"][] = $rowData;
        }

        return $data;
    }

    /**
     * @throws \Exception
     */
    private function adjustInvoiceLinesArray(int|string $invoiceId): array
    {
        $invoiceLines = [];
        $invoiceLinesRows = $this->invoiceLineRepository->findBy(["invoice_id" => $invoiceId]);
        foreach ($invoiceLinesRows as $invoiceLinesRow) {
            $rowData = [];
            foreach ($invoiceLinesRow as $column => $columnData) {
                if ($column == "id") {
                    $rowData["id"] = $columnData;
                }
                if ($column == "quantity") {
                    $rowData["quantity"] = $columnData;
                }
                if ($column == "total_price") {
                    $rowData["total_price"] = $columnData;
                }
                if ($column == "product_id") {
                    $rowData["product"] = $this->getProduct($this->productRepository->findById($columnData)[0]);
                }
            }
            $invoiceLines[] = $rowData;
        }

        return $invoiceLines;
    }

    private function getCustomer(array $customerArray): array
    {
        $customerData = [];

        foreach ($customerArray as $column => $columnData) {
            if ($column == "id") {
                $customerData["id"] = $columnData;
            }
            if ($column == "name") {
                $customerData["name"] = $columnData;
            }
        }

        return $customerData;
    }

    private function getCustomerAddress(array $customerAddressArray): array
    {
        $customerAddressData = [];

        foreach ($customerAddressArray as $column => $columnData) {
            if ($column == "id") {
                $customerAddressData["id"] = $columnData;
            }
            if ($column == "address") {
                $customerAddressData["address"] = $columnData;
            }
        }

        return $customerAddressData;
    }

    private function getProduct(array $productArray): array
    {
        $productData = [];

        foreach ($productArray as $column => $columnData) {
            if ($column == "id") {
                $productData["id"] = $columnData;
            }
            if ($column == "name") {
                $productData["name"] = $columnData;
            }
            if ($column == "price") {
                $productData["price"] = $columnData;
            }
        }

        return $productData;
    }
}