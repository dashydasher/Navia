<?php
namespace Models;
use \DateTime;
use \DateTimeZone;

class Room {
    public $id;
    public $time;
    public $key;
    public $description;
    public $active;
    public $teacher_id;

    public $moods = array();
    public $comments = array();
    public $questions = array();

    private $database;

    function __construct() {
        $this->database = new Database;
    }

    public function mapAttr($row) {
        $this->id = $row->id;
        $this->time = $row->time;
        $this->key = $row->key;
        $this->description = $row->description;
        $this->active = $row->active;
        $this->teacher_id = $row->teacher_id;

        return 1;
    }

    # za generiranje random kljuca
    function crypto_rand_secure($min, $max) {
        $range = $max - $min;
        if ($range < 1) return $min; // not so random...
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd > $range);
        return $min + $rnd;
    }
    function getToken($length) {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet); // edited

        for ($i=0; $i < $length; $i++) {
            $token .= $codeAlphabet[$this->crypto_rand_secure(0, $max-1)];
        }

        return $token;
    }

    function store($description, $teacher_id, $token) {
        if ($token) {
            $key = $token;
        }
        else {
            $key = $this->getToken(6);
        }
        $time = new DateTime(null, new DateTimeZone('Europe/Zagreb'));
        $query = "INSERT INTO room (`time`, `key`, description, active, teacher_id) VALUES (:time, :key, :description, 1, :teacher_id)";
        try {
            $result = $this->database->connection->prepare($query);
            $result->execute(array(
                    "time" => $time->format('Y-m-d\TH:i:s.u'),
                    "key" => $key,
                    "description" => $description,
                    "teacher_id" => $teacher_id,
                ));

            $new_id = $this->database->connection->lastInsertId();

            $this->mapAttr((object)array(
                "id" => $new_id,
                "time" => $time,
                "key" => $key,
                "description" => $description,
                "active" => 1,
                "teacher_id" => $teacher_id,
            ));

            return $new_id;
        } catch(\PDOException $e) {
            return -1;
        }
    }

    function fetch($id) {
        $query = "SELECT room.* FROM room WHERE room.id = :id";
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

    /*
    TODO key nije UNIQUE index pa bi nam moglo bilo koju sobu vratiti
    */
    function fetch_by_key($key) {
        $query = "SELECT room.* FROM room WHERE room.key = :key";
        try {
            $result = $this->database->connection->prepare($query);
            $result->execute(array(
                    "key" => $key,
                ));
            $row = $result->fetch(\PDO::FETCH_OBJ);
            if ($row) {
                return $this->mapAttr($row);
            }
        } catch(\PDOException $e) {
            return -1;
        }
    }

    function fetch_comments($time_sel) {
        $query = "SELECT comment.* FROM comment WHERE comment.room_id = :room_id AND time >= :time_sel";
        try {
            $result = $this->database->connection->prepare($query);
            $result->execute(array(
                    "room_id" => $this->id,
                    "time_sel" => $time_sel,
                ));

            foreach ($result->fetchAll(\PDO::FETCH_OBJ) as $row) {
                $comment = new Comment;
                $comment->mapAttr($row);
                array_push($this->comments, $comment);
            }
            return $this->comments;
        } catch(\PDOException $e) {
            return $e;
        }
    }

    function fetch_questions($time_sel) {
        $query = "SELECT question.* FROM question WHERE question.room_id = :room_id AND time >= :time_sel";
        try {
            $result = $this->database->connection->prepare($query);
            $result->execute(array(
                    "room_id" => $this->id,
                    "time_sel" => $time_sel,
                ));

            foreach ($result->fetchAll(\PDO::FETCH_OBJ) as $row) {
                $question = new Question;
                $question->mapAttr($row);
                array_push($this->questions, $question);
            }
            return $this->questions;
        } catch(\PDOException $e) {
            return $e;
        }
    }

    function fetch_moods($time_sel) {
        $query = "SELECT `mood`.`id`, `mood`.`time`, `mood`.`signature`, `mood_option`.`mood`, `mood_reason`.`reason`
                    FROM mood
                    JOIN mood_option ON `mood`.`mood_option_id`=`mood_option`.`id`
                    JOIN mood_reason ON `mood`.`mood_reason_id`=`mood_reason`.`id`
                    WHERE mood.room_id = :room_id AND time >= :time_sel";
        try {
            $result = $this->database->connection->prepare($query);
            $result->execute(array(
                    "room_id" => $this->id,
                    "time_sel" => $time_sel,
                ));

            foreach ($result->fetchAll(\PDO::FETCH_OBJ) as $row) {
                $mood = new Mood;
                $mood->mapAttr($row);
                array_push($this->moods, $mood);
            }
            return $this->moods;
        } catch(\PDOException $e) {
            return $e;
        }
    }

    function activate($id) {
        $query = "UPDATE `room` SET `active` = '1' WHERE `room`.`id` = :id";
        try {
            $result = $this->database->connection->prepare($query);
            $result->execute(array(
                    "id" => $id,
                ));
            $this->active = 1;
        } catch(\PDOException $e) {
            return -1;
        }
    }

    function deactivate($id) {
        $query = "UPDATE `room` SET `active` = '0' WHERE `room`.`id` = :id";
        try {
            $result = $this->database->connection->prepare($query);
            $result->execute(array(
                    "id" => $id,
                ));
            $this->active = 0;
        } catch(\PDOException $e) {
            return -1;
        }
    }
}
