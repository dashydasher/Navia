<?php
namespace Models;

class Comment {
    private $id;
    public $time;
    public $signature;
    public $comment;

    private $database;

    function __construct() {
        $this->database = new Database;
    }

    private function mapAttr($row) {
        $this->id = $row->id;
        $this->time = $row->time;
        $this->signature = $row->signature;
        $this->comment = $row->comment;

        return 1;
    }

    function store($signature, $comment, $room_id) {
        $query = "INSERT INTO mood (time, signature, comment, room_id) VALUES (NOW(), :signature, :comment, :room_id)";
        try {
            $result = $this->database->connection->prepare($query);
            $result->execute(array(
                    "signature" => $signature,
                    "comment" => $comment,
                    "room_id" => $room_id,
                ));
        } catch(\PDOException $e) {
            return -1;
        }
    }

}
