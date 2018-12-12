<?php
namespace Models;

class Comment {
    public $id;
    public $time;
    public $signature;
    public $comment;

    private $database;

    function __construct() {
        $this->database = new Database;
    }

    public function mapAttr($row) {
        $this->id = $row->id;
        $this->time = $row->time;
        $this->signature = $row->signature;
        $this->comment = $row->comment;

        return 1;
    }

    function store($signature, $comment, $room_id) {
        $query = "INSERT INTO comment (time, signature, comment, room_id) VALUES (NOW(), :signature, :comment, :room_id)";
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
