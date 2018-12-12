<?php
namespace Models;

class Teacher {
    private $id;
    public $username;
    private $password;
    public $name;

    public $rooms;

    private $database;

    function __construct() {
        $this->database = new Database;
    }

    private function mapAttr($row) {
        $this->id = $row->id;
        $this->username = $row->username;
        $this->password = $row->password;
        $this->name = $row->name;

        return 1;
    }

    function store($username, $password, $name) {
        $query = "INSERT INTO teacher (username, password, name) VALUES (:username, SHA2(:password, 256), :name)";
        try {
            $result = $this->database->connection->prepare($query);
            $result->execute(array(
                    "username" => $username,
                    "password" => $password,
                    "name" => $name,
                ));
        } catch(\PDOException $e) {
            return -1;
        }
    }

    function fetch($id) {
        $query = "SELECT teacher.* FROM teacher WHERE teacher.id = :id";
        try {
            $result = $this->database->connection->prepare($query);
            $result->execute(array(
                    "id" => $id,
                ));
            $row = $result->fetch(\PDO::FETCH_OBJ);
            if ($row) {
                return $this->mapAttr($row);
            }
        } catch(\PDOException $e) {
            return -1;
        }
    }

    function delete($id) {
        $query = "DELETE FROM teacher WHERE teacher.id = :id";
        try {
            $result = $this->database->connection->prepare($query);
            $result->execute(array(
                    "id" => $id,
                ));
        } catch(\PDOException $e) {
            return -1;
        }
    }

}
