<?php
namespace Models;
use \DateTime;
use \DateTimeZone;

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
        $time = new DateTime(null, new DateTimeZone('Europe/Zagreb'));
        $query = "INSERT INTO comment (time, signature, comment, room_id) VALUES (:time, :signature, :comment, :room_id)";
        try {
            $result = $this->database->connection->prepare($query);
            $result->execute(array(
                    "time" => $time->format('Y-m-d\TH:i:s.u'),
                    "signature" => $signature,
                    "comment" => $comment,
                    "room_id" => $room_id,
                ));

            $new_id = $this->database->connection->lastInsertId();

            $this->mapAttr((object)array(
                "id" => $new_id,
                "time" => $time,
                "signature" => $signature,
                "comment" => $comment,
            ));
        } catch(\PDOException $e) {
            return -1;
        }
    }

}
