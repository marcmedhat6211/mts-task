<?php

namespace App\Service;

use App\Utils\Validate;

class DatabaseService
{
    private ?string $databaseServer;
    private ?string $host;
    private ?string $dbName;
    private ?string $user;
    private ?string $password;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        $this->databaseServer = $_ENV["DB_SERVER"];
        $this->host = $_ENV["DB_HOST"];
        $this->dbName = $_ENV["DB_NAME"];
        $this->user = $_ENV["DB_USER"];
        $this->password = $_ENV["DB_PASSWORD"];
        $this->validateEnvVars();
    }

    /**
     * This method creates a new table in the database
     * @throws \Exception
     */
    public function createTable(
        string $tableName,
        array  $configs
    )
    {
        $sql = "CREATE TABLE IF NOT EXISTS {$tableName} (";
        $i = 0;
        foreach ($configs as $columnName => $columnConfig) {
            $sql .= $columnName . " " . $columnConfig;
            if (++$i === count($configs)) {
                $sql .= ")";
            } else {
                $sql .= ", ";
            }
        }

        $this->executeQuery($sql);
    }

    /**
     * This method drops one or many tables from the database
     * @throws \Exception
     */
    public function dropTables(array $tableNames)
    {
        $sql = "DROP TABLE IF EXISTS ";
        foreach ($tableNames as $index => $tableName) {
            $sql .= $tableName;
            if (++$index === count($tableNames)) {
                $sql .= ";";
            } else {
                $sql .= ",";
            }
        }

        $this->executeQuery($sql);
    }

    /**
     * This method inserts a new row in the database
     * @throws \Exception
     */
    public function insert(
        string $tableName,
        array  $data,
    )
    {
        $sql = "INSERT INTO {$tableName} (";
        $i = 0;
        foreach ($data as $columnName => $columnData) {
            $sql .= "$columnName";
            if (++$i === count($data)) {
                $sql .= ")";
            } else {
                $sql .= ",";
            }
        }

        $sql .= " VALUES (";

        $i = 0;
        foreach ($data as $columnData) {
            $dataType = gettype($columnData);
            if ($dataType === "string") {
                $sql .= "'$columnData'";
            } elseif ($dataType === "NULL") {
                $sql .= "NULL";
            } else {
                $sql .= "$columnData";
            }

            if (++$i === count($data)) {
                $sql .= ");";
            } else {
                $sql .= ",";
            }
        }

        $this->executeQuery($sql);
    }

    /**
     * This method fetches data from the database
     * @throws \Exception
     */
    public function select(
        string $tableName,
        array  $fields = [],
        string $whereClause = ""
    ): array|bool
    {
        $sql = "SELECT ";

        if (count($fields) > 0) {
            foreach ($fields as $index => $field) {
                $sql .= "$field";
                if (++$index === count($fields)) {
                    $sql .= " ";
                } else {
                    $sql .= ",";
                }
            }
        } else {
            $sql .= "* ";
        }

        $sql .= "FROM {$tableName} ";
        if (Validate::notNull($whereClause)) {
            $sql .= "{$whereClause}";
        }

        $queryResult = $this->executeQuery($sql);

        return $queryResult->fetch();
    }

    //==================================================== PRIVATE METHODS =============================================

    /**
     * This method establishes a connection to the database
     * @return \PDO
     * @throws \PDOException
     */
    public function connect(): \PDO
    {
        try {
            $connection = new \PDO(
                $this->getDsn(),
                $this->user,
                $this->password
            );
            $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            return $connection;
        } catch (\PDOException $exception) {
            throw new \PDOException($exception->getMessage());
        }
    }

    /**
     * This method generates the DSN to use in the PDO connection
     * @return string
     */
    private function getDsn(): string
    {
        return "{$this->databaseServer}={$this->host};dbname={$this->dbName};charset=UTF8";
    }

    /**
     * This method executes the database query
     * @throws \Exception
     */
    private function executeQuery(string $sql): \PDOStatement|false
    {
        try {
            $connection = $this->connect();
            return $connection->query($sql);
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
            throw new \Exception("An Error occurred while executing the database query");
        }
    }

    /**
     * This method validates the variables generated in the .env file
     * @throws \Exception
     */
    private function validateEnvVars()
    {
        if (!Validate::notNull($this->databaseServer)) {
            throw new \Exception("Invalid ENV Variable 'DB_SERVER'");
        }

        if (!Validate::notNull($this->host)) {
            throw new \Exception("Invalid ENV Variable 'DB_HOST'");
        }

        if (!Validate::notNull($this->dbName)) {
            throw new \Exception("Invalid ENV Variable 'DB_NAME'");
        }

        if (!Validate::notNull($this->user)) {
            throw new \Exception("Invalid ENV Variable 'DB_USER'");
        }

        if (!Validate::notNull($this->password)) {
            throw new \Exception("Invalid ENV Variable 'DB_PASSWORD'");
        }
    }
}