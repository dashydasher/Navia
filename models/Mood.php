<?php
namespace Models;

class Mood {
    public $id;
    public $time;
    public $signature;
    public $mood;
    public $reason;

    private $database;

    function __construct() {
        $this->database = new Database;
    }

    public function mapAttr($row) {
        $this->id = $row->id;
        $this->time = $row->time;
        $this->signature = $row->signature;
        $this->mood = $row->mood;
        $this->reason = $row->reason;

        return 1;
    }

    function store($signature, $mood_id, $reason_id, $room_id) {
        $query = "INSERT INTO mood (time, signature, mood_option_id, mood_reason_id, room_id) VALUES (NOW(), :signature, :mood_id, :reason_id, :room_id)";
        try {
            $result = $this->database->connection->prepare($query);
            $result->execute(array(
                    "signature" => $signature,
                    "mood_id" => $mood_id,
                    "reason_id" => $reason_id,
                    "room_id" => $room_id,
                ));
        } catch(\PDOException $e) {
            return -1;
        }
    }

}
