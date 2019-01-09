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
        // Dodavanje nastavnika
        $query = "INSERT INTO teacher (username, password, name) VALUES (:username, SHA2(:password, 256), :name)";
        // Dodavanje nastavnikovih default razloga
        $query2 = "INSERT INTO mood_reason (reason, type, teacher_id) VALUES
                        ('dobro objašnjavanje', 1, :teacher_id1),
                        ('zanimljiva tema', 1, :teacher_id2),
                        ('primjena multimedija u nastavi', 1, :teacher_id3),
                        ('previše digresija', 0, :teacher_id4),
                        ('prebrzo objašnjavanje', 0, :teacher_id5),
                        ('presporo objašnjavanje', 0, :teacher_id6)";

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


            $result2 = $this->database->connection->prepare($query2);
            $result2->execute(array(
                    "teacher_id1" => $new_id,
                    "teacher_id2" => $new_id,
                    "teacher_id3" => $new_id,
                    "teacher_id4" => $new_id,
                    "teacher_id5" => $new_id,
                    "teacher_id6" => $new_id,
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

    /*
    ako je teacher_id postavljen onda koristi njega, inače koristi svoj id
    */
    function fetch_mood_reasons($teacher_id = null) {
        $query = "SELECT mood_reason.* FROM mood_reason
                  WHERE (mood_reason.teacher_id = :teacher_id)
                        AND active = 1";
        try {
            $result = $this->database->connection->prepare($query);
            $result->execute(array(
                    "teacher_id" => $teacher_id ? $teacher_id : $this->id,
                ));

            foreach ($result->fetchAll(\PDO::FETCH_OBJ) as $row) {
                $mood_reason = new MoodReason;
                $mood_reason->mapAttr($row);
                array_push($this->mood_reasons, $mood_reason);
            }
            return $this->mood_reasons;
        } catch(\PDOException $e) {
            return -1;
        }
    }
}
