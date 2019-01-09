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

    /*
    http://us.php.net/manual/en/language.oop5.magic.php#object.sleep
    služi za serijalizaciju objekta u session varijablu.
    */
    public function __sleep() {
        return array(
            'question',
        );
    }

    public function mapAttr($row) {
        $this->id = $row->id;
        $this->time = $row->time;
        $this->signature = $row->signature;
        $this->question = $row->question;

        return 1;
    }

    function store($signature, $question, $room_id) {
        if (empty($signature)) {
            $signature = 'Anon';
        }
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

    function delete($question_id, $room_id) {
        $query = "DELETE FROM question WHERE question.id = :question_id AND question.room_id = :room_id";

        try {
            $result = $this->database->connection->prepare($query);
            $result->execute(array(
                    "question_id" => $question_id,
                    "room_id" => $room_id,
                ));

            return 1;
        } catch(\PDOException $e) {
            return -1;
        }
    }

}
