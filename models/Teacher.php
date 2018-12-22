<?php
namespace Models;

class Teacher {
    public $id;
    public $username;
    private $password;
    public $name;

    public $rooms = array();
    public $mood_reasons = array();

    private $database;

    function __construct() {
        $this->database = new Database;
    }

    public function mapAttr($row) {
        $this->id = $row->id;
        $this->username = $row->username;
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

            $new_id = $this->database->connection->lastInsertId();

            $this->mapAttr((object)array(
                "id" => $new_id,
                "username" => $username,
                "name" => $name,
            ));

            return $new_id;
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

    function login_check($username, $password) {
        $query = "SELECT teacher.* FROM teacher WHERE teacher.username = :username AND teacher.password = SHA2(:password, 256)";
        try {
            $result = $this->database->connection->prepare($query);
            $result->execute(array(
                    "username" => $username,
                    "password" => $password,
                ));
            $row = $result->fetch(\PDO::FETCH_OBJ);
            if ($row) {
                return $this->mapAttr($row);
            } else {
                return -1;
            }
        } catch(\PDOException $e) {
            return -1;
        }
    }

    function fetch_rooms() {
        $query = "SELECT room.* FROM room WHERE room.teacher_id = :teacher_id";
        try {
            $result = $this->database->connection->prepare($query);
            $result->execute(array(
                    "teacher_id" => $this->id,
                ));

            foreach ($result->fetchAll(\PDO::FETCH_OBJ) as $row) {
                $room = new Room;
                $room->mapAttr($row);
                array_push($this->rooms, $room);
            }
            return $this->rooms;
        } catch(\PDOException $e) {
            return -1;
        }
    }

    function fetch_mood_reasons() {
        $query = "SELECT mood_reason.* FROM mood_reason WHERE mood_reason.teacher_id = :teacher_id OR mood_reason.teacher_id IS NULL";
        try {
            $result = $this->database->connection->prepare($query);
            $result->execute(array(
                    "teacher_id" => $this->id,
                ));

            foreach ($result->fetchAll(\PDO::FETCH_OBJ) as $row) {
                $mood_reason = new MoodReason;
                $mood_reason->mapAttr($row);
                array_push($this->mood_reasons, $room);
            }
            return $this->mood_reasons;
        } catch(\PDOException $e) {
            return -1;
        }
    }
}
