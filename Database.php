<?php

include "Table.php";

class Database
{
    private $host;
    private $dbname;
    private $username;
    private $password;
    private $connection;

    public function __construct($host, $databaseName, $username, $password)
    {
        $this->host = $host;
        $this->dbname = $databaseName;
        $this->username = $username;
        $this->password = $password;
    }

    public function createDatabase()
    {
        try {
            $pdo = new PDO("mysql:host=".$this->host, $this->username, $this->password);
            $pdo->exec("CREATE DATABASE $this->dbname");
            echo "Database created successfully";
        } catch (PDOException $e) {
            die("Error creating database: " . $e->getMessage());
        }
    }

    public function deleteDatabase()
    {
        try {
            $pdo = new PDO("mysql:host=".$this->host, $this->username, $this->password);
            $pdo->exec("DROP DATABASE $this->dbname");
            echo "Database Deleted successfully";
        } catch (PDOException $e) {
            die("Error Deleting database: " . $e->getMessage());
        }
    }

    public function getDatabaseName()
    {
        return $this->dbname;
    }

    public function getDatabaseHost()
    {
        return $this->host;
    }

    public function getDatabaseUsername()
    {
        return $this->username;
    }

    public function getDatabasePassword()
    {
        return $this->password;
    }

    public function getConnection()
    {
        try {
            $this->connection = new PDO("mysql:host=".$this->host . ";dbname=" . $this->getDatabaseName(), $this->username, $this->password);
            return $this->connection;

        } catch (PDOException $e) {
            die("Error connecting to database: " . $e->getMessage());
        }
    }
}
