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

    /*
    http://us.php.net/manual/en/language.oop5.magic.php#object.sleep
    služi za serijalizaciju objekta u session varijablu.
    */
    public function __sleep() {
        return array(
            'comment',
        );
    }

    public function mapAttr($row) {
        $this->id = $row->id;
        $this->time = $row->time;
        $this->signature = $row->signature;
        $this->comment = $row->comment;

        return 1;
    }

    function store($signature, $comment, $room_id) {
        if (empty($signature)) {
            $signature = 'Anon';
        }
        $time = new DateTime(null, new DateTimeZone('Europe/Zagreb'));
        $query = "INSERT INTO comment (`time`, signature, comment, room_id) VALUES (:time, :signature, :comment, :room_id)";
        try {
            $result = $this->database->connection->prepare($query);
            $result->execute(array(
                "time" => $time->format('Y-m-d H:i:s'),
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

            return $new_id;
        } catch(\PDOException $e) {
            return -1;
        }
    }

}
