<?php
namespace Models;
use \DateTime;
use \DateTimeZone;

class Question {
    public $id;
    public $time;
    public $signature;
    public $question;

    private $database;

    function __construct() {
        $this->database = new Database;
    }

    public function mapAttr($row) {
        $this->id = $row->id;
        $this->time = $row->time;
        $this->signature = $row->signature;
        $this->question = $row->question;

        return 1;
    }

    function store($signature, $question, $room_id) {
        $time = new DateTime(null, new DateTimeZone('Europe/Zagreb'));
        $query = "INSERT INTO question (time, signature, question, room_id) VALUES (:time, :signature, :question, :room_id)";
        try {
            $result = $this->database->connection->prepare($query);
            $result->execute(array(
                "time" => $time->format('Y-m-d\TH:i:s.u'),
                "signature" => $signature,
                "question" => $question,
                "room_id" => $room_id,
            ));

            $new_id = $this->database->connection->lastInsertId();

            $this->mapAttr((object)array(
                "id" => $new_id,
                "time" => $time,
                "signature" => $signature,
                "question" => $question,
            ));
            
            return $new_id;
        } catch(\PDOException $e) {
            return -1;
        }
    }

}
