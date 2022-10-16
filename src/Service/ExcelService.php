<?php

namespace App\Service;

use App\Core\Container;
use App\Factory\CustomerAddressFactory;
use App\Factory\CustomerFactory;
use App\Factory\InvoiceFactory;
use App\Factory\InvoiceLineFactory;
use App\Factory\ProductFactory;
use App\Repository\CustomerAddressRepository;
use App\Repository\CustomerRepository;
use App\Repository\InvoiceLineRepository;
use App\Repository\InvoiceRepository;
use App\Repository\ProductRepository;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class ExcelService
{
    private string $filesPath;
    private Container $container;
    private InvoiceRepository $invoiceRepository;
    private InvoiceLineRepository $invoiceLineRepository;
    private CustomerRepository $customerRepository;
    private CustomerAddressRepository $customerAddressRepository;
    private ProductRepository $productRepository;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->filesPath = dirname(__DIR__, 2) . "/files";
        $this->container = new Container();
        $this->invoiceRepository = $this->container->get(InvoiceRepository::class);
        $this->invoiceLineRepository = $this->container->get(InvoiceLineRepository::class);
        $this->customerRepository = $this->container->get(CustomerRepository::class);
        $this->customerAddressRepository = $this->container->get(CustomerAddressRepository::class);
        $this->productRepository = $this->container->get(ProductRepository::class);
    }

    /**
     * This method takes the data from the Excel file and adds it to the database
     * @throws \Exception
     */
    public function addFileDataToDatabase(string $fileName): void
    {
        if (!file_exists($this->filesPath . "/{$fileName}")) {
            throw new \Exception("File does not exist");
        }

        $reader = ReaderEntityFactory::createXLSXReader();
        $reader->open($this->filesPath . "/$fileName");

        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $rowNumber => $row) {
                if ($rowNumber === 1) { // column names
                    continue;
                }
                $invoiceData = $customerData = $customerAddressData = $productData = $invoiceLineData = [];
                $cells = $row->getCells();
                foreach ($cells as $columnNumber => $cell) {
                    if ($columnNumber === 0) { // invoice id column
                        if (!$this->invoiceRepository->isInvoiceExists((string)$cell)) {
                            $invoiceData["id"] = ["value" => $cell->getValue(), "exists" => false];
                        } else {
                            $invoiceData["id"] = ["value" => $cell->getValue(), "exists" => true];
                        }
                    }

                    if ($columnNumber === 1 && count($invoiceData) > 0) { // date column
                        $invoiceData["date"] = new \DateTime(); // I added here the date for 'now' because the date from the file is not formatted correctly
                    }

                    if ($columnNumber === 2) { // customer name column
                        if (!$this->customerRepository->isCustomerExistsByName((string)$cell)) {
                            $customerData["name"] = ["value" => $cell->getValue(), "exists" => false];
                        } else {
                            $customerData["name"] = ["value" => $cell->getValue(), "exists" => true];
                        }
                    }

                    if ($columnNumber === 3) { // customer address column
                        if (!$this->customerAddressRepository->isCustomerAddressExistsByAddress((string)$cell)) {
                            $customerAddressData["address"] = ["value" => $cell->getValue(), "exists" => false];
                        } else {
                            $customerAddressData["address"] = ["value" => $cell->getValue(), "exists" => true];
                        }
                    }

                    if ($columnNumber === 4) { // product name column
                        if (!$this->productRepository->isProductExistsByName((string)$cell)) {
                            $productData["name"] = ["value" => $cell->getValue(), "exists" => false];
                        } else {
                            $productData["name"] = ["value" => $cell->getValue(), "exists" => true];
                        }
                    }

                    if ($columnNumber === 5) { // quantity column
                        $invoiceLineData["quantity"] = $cell->getValue();
                    }

                    if ($columnNumber === 6 && count($productData) > 0) { // product price column
                        $productData["price"] = $cell->getValue();
                    }

                    if ($columnNumber === 7) { // invoice line total
                        $invoiceLineData["totalPrice"] = $cell->getValue();
                    }

                    if ($columnNumber === 8 && count($invoiceData) > 0) { // invoice Grand Total
                        $invoiceData["grandTotal"] = $cell->getValue();
                    }
                }
                $this->addDataToDatabase($invoiceData, $customerData, $customerAddressData, $productData, $invoiceLineData);
            }
        }
    }

    /**
     * This method persists one row in the database
     * @throws \Exception
     */
    private function addDataToDatabase(
        array $invoiceData,
        array $customerData,
        array $customerAddressData,
        array $productData,
        array $invoiceLineData
    ): void
    {
        $customer = CustomerFactory::create(["name" => $customerData["name"]["value"]]);
        if (!$customerData["name"]["exists"]) {
            $this->customerRepository->createCustomer($customer);
        }

        $customerId = null;
        $existingCustomer = $this->customerRepository->findBy(["name" => $customer->getName()]);
        if ($existingCustomer) {
            $customerId = $existingCustomer["id"];
        }

        $customerAddress = CustomerAddressFactory::create(["customerId" => $customerId, "address" => $customerAddressData["address"]["value"]]);
        if (!$customerAddressData["address"]["exists"]) {
            $this->customerAddressRepository->createCustomerAddress($customerAddress);
        }

        $invoice = InvoiceFactory::create([
            "id" => $invoiceData["id"]["value"],
            "customerId" => $customerId,
            "grandTotal" => $invoiceData["grandTotal"],
            "date" => $invoiceData["date"]
        ]);
        if (!$invoiceData["id"]["exists"]) {
            $this->invoiceRepository->createInvoice($invoice);
        }

        $product = ProductFactory::create(["name" => $productData["name"]["value"], "price" => $productData["price"]]);
        if (!$productData["name"]["exists"]) {
            $this->productRepository->createProduct($product);
        }

        $invoiceId = null;
        $existingInvoice = $this->invoiceRepository->findBy(["id" => $invoice->getId()]);
        if ($existingInvoice) {
            $invoiceId = $existingInvoice["id"];
        }

        $productId = null;
        $existingProduct = $this->productRepository->findBy(["name" => $product->getName()]);
        if ($existingProduct) {
            $productId = $existingProduct["id"];
        }

        if (count($invoiceLineData) > 0) {
            $this->invoiceLineRepository->createInvoiceLine(InvoiceLineFactory::create(array_merge($invoiceLineData, ["productId" => $productId, "invoiceId" => $invoiceId])));
        }
    }
}