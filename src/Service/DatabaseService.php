<?php

namespace App\Service;

class DatabaseService
{
    private ?string $databaseServer;
    private ?string $host;
    private ?string $dbName;
    private ?string $user;
    private ?string $password;

    public function __construct()
    {
        $this->databaseServer = $_ENV["DB_SERVER"];
        $this->host = $_ENV["DB_HOST"];
        $this->dbName = $_ENV["DB_NAME"];
        $this->user = $_ENV["DB_USER"];
        $this->password = $_ENV["DB_PASSWORD"];
    }

    /**
     * @throws \Exception
     */
    public function createTable(
        string $tableName,
        array $configs
    ) {
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

    //==================================================== PRIVATE METHODS =============================================

    /**
     * This method establishes a connection to the database
     * @throws \PDOException
     * @return \PDO
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
     * @throws \Exception
     */
    private function executeQuery(string $sql)
    {
        try {
            $connection = $this->connect();
            $connection->query($sql);
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
            throw new \Exception("An Error occurred while executing the database query");
        }
    }
}