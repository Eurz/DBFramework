<?php

namespace Core;

use App\Controller\AppController;
use PDO;
use PDOException;

class Database
{
    private string $dbName;
    private string $dbHost;
    private string $dbUser;
    private string $dbPassword;
    private $errorCode;
    private $pdo;
    protected $messageManager;

    public function __construct($dbName, $dbHost, $dbUser, $dbPassword)
    {
        $this->dbName = $dbName;
        $this->dbHost = $dbHost;
        $this->dbUser = $dbUser;
        $this->dbPassword = $dbPassword;
        $this->messageManager = new Messages();
    }

    /**
     * Return an instance of PDO
     */
    public function getPdo()
    {
        if (is_null($this->pdo)) {

            $dsn = "mysql:";
            $dsn .= "host=" . $this->dbHost . ';';
            $dsn .= "dbname=" . $this->dbName;

            $dbOptions = [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            ];

            try {
                $this->pdo = new PDO($dsn, $this->dbUser, $this->dbPassword, $dbOptions);
            } catch (\PDOException $e) {
                var_dump($e->getMessage());
            }
        }

        return $this->pdo;
    }

    public function dbExist()
    {

        $dsn = "mysql:";
        $dsn .= "host=" . $this->dbHost . ';';

        $dbOptions = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
        ];

        $pdo = new PDO($dsn, $this->dbUser, $this->dbPassword, $dbOptions);
        $query = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME=?";
        $stmt = $pdo->prepare($query);
        $db = 'test_db';

        $stmt->execute([$db]);
        $res = $stmt->fetch();
        if ($res === false) {

            return false;
        } else {
            return true;
        }
    }

    public function getErrorCode()
    {
        return $this->errorCode;
    }




    /**
     * @param string $query - A sql query
     * @param array $attributes - Parameters for prepared query
     * @param object $entity - Entity object to fetch data
     * @param boolean $isSingleData - True if fetching one data, or false for multiple data
     */
    public function query(string $query, ?array $attributes, $entity, $isSingleData): mixed
    {

        $statement = $this->getPdo()->prepare($query);
        if (!is_null($entity)) {
            $statement->setFetchMode(PDO::FETCH_CLASS, $entity);
        } else {
            $statement->setFetchMode(PDO::FETCH_ASSOC);
        }

        try {

            $response = $statement->execute($attributes);

            if ($response !== false) {
                if (
                    strpos($query, 'UPDATE') === 0 ||
                    strpos($query, 'DELETE') === 0 ||
                    strpos($query, 'INSERT') === 0
                ) {
                    return $response;
                }

                if ($isSingleData) {
                    $result = $statement->fetch();
                } else {
                    $result = $statement->fetchAll();
                }
                return $result;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            var_dump($e->getMessage());
            return false;
        }
    }

    /**
     * @param string $query - A sql query
     * @param  $attributes - Parameters for prepared query
     * @return 
     */
    public function queryIndexed(string $query, $attributes = [], $entity = null): array
    {
        $pdo = $this->getPdo();
        $statement = $pdo->prepare($query);

        $statement->setFetchMode(PDO::FETCH_NUM);
        try {
            $response = $statement->execute($attributes);
            $result = $statement->fetchAll();
            return $result;
        } catch (PDOException $e) {
            var_dump($e->getMessage());
            return false;
        }
    }


    public function lastInsertId()
    {
        return $this->getPdo()->lastInsertId();
    }
}
