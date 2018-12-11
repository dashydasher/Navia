<?php
namespace Models;

class Database {
    public $connection;

    function __construct() {
        $db_data = include(__DIR__ . '/../config/database.php');
        $servername = $db_data->servername;
        $username = $db_data->username;
        $password = $db_data->password;
        $db = $db_data->db;
        $charset = $db_data->charset;

        try {
            $this->connection = new \PDO("mysql:host=$servername;dbname=$db;charset=$charset", $username, $password);
            $this->connection->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
            $this->connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            /*
            TODO ovdje bi trebalo nešt pametnije napraviti
            */
            throw $e;
        }
    }
}
