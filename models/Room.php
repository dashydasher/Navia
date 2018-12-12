<?php
namespace Models;

class Room {
    private $id;
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

    private function mapAttr($row) {
        $this->id = $row->id;
        $this->time = $row->time;
        $this->signature = $row->signature;
        $this->mood = $row->mood;
        $this->reason = $row->reason;

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
        $query = "INSERT INTO room (`time`, `key`, description, active, teacher_id) VALUES (NOW(), :key, :description, 1, :teacher_id)";
        try {
            $result = $this->database->connection->prepare($query);
            $result->execute(array(
                    "key" => $key,
                    "description" => $description,
                    "teacher_id" => $teacher_id,
                ));
        } catch(\PDOException $e) {
            return $e;
        }
    }

}
