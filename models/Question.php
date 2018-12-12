<?php
namespace Models;

class Comment {
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
        $query = "INSERT INTO mood (time, signature, question, room_id) VALUES (NOW(), :signature, :question, :room_id)";
        try {
            $result = $this->database->connection->prepare($query);
            $result->execute(array(
                    "signature" => $signature,
                    "question" => $question,
                    "room_id" => $room_id,
                ));
        } catch(\PDOException $e) {
            return -1;
        }
    }

}
