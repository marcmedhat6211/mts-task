<?php

namespace App\Model;

use App\Core\Container;
use App\Service\DatabaseService;

abstract class AbstractRepository
{
    private Container $container;
    private DatabaseService $databaseService;
    private string $childClass;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->container = new Container();
        $this->databaseService = $this->container->get(DatabaseService::class);
        $this->childClass = get_class($this);
    }

    /**
     * @throws \Exception
     */
    public function findAll(): array|bool
    {
        $tableName = constant($this->childClass . '::TABLE_NAME');

        return $this->databaseService->select($tableName);
    }

    /**
     * @throws \Exception
     */
    public function findById(int|string $id): array|bool
    {
        $tableName = constant($this->childClass . '::TABLE_NAME');
        $fields = [];
        $whereClause = "WHERE id = {$id}";

        return $this->databaseService->select($tableName, $fields, $whereClause);
    }

    /**
     * @throws \Exception
     */
    public function findBy(array $criteria): array|bool
    {
        $tableName = constant($this->childClass . '::TABLE_NAME');

        $whereClause = "WHERE ";
        $i = 0;
        foreach ($criteria as $field => $value) {
            $dataType = gettype($value);
            if ($dataType === "string") {
                $whereClause .= "{$field} = '{$value}'";
            } else {
                $whereClause .= "{$field} = {$value}";
            }

            if (++$i === count($criteria)) {
                $whereClause .= "";
            } else {
                $whereClause .= " AND ";
            }
        }


        return $this->databaseService->select($tableName, [], $whereClause);
    }
}