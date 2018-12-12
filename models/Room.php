<?php
namespace Models;
use \DateTime;

class Room {
    public $id;
    public $time;
    public $key;
    public $description;
    public $active;

    public $moods;
    public $comments;
    public $questions;

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

    function store($description, $teacher_id) {
        $key = $this->getToken(6);
        $time = new DateTime();
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
                ));

                return $new_id;
        } catch(\PDOException $e) {
            return $e;
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

    function fetch_moods() {

    }

    function fetch_comments() {

    }

    function fetch_questions() {

    }

}
