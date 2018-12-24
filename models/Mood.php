<?php
namespace Models;
use \DateTime;
use \DateTimeZone;

class Mood {
    public $id;
    public $time;
    public $signature;
    public $personal_reason;
    public $mood_option_id;
    public $mood_reason_id;

    private $database;

    function __construct() {
        $this->database = new Database;
    }

    /*
    http://us.php.net/manual/en/language.oop5.magic.php#object.sleep
    služi za serijalizaciju objekta u session varijablu. PDO objekt nebre serijalizirat pa je zato izostavljen
    */
    public function __sleep() {
        return array(
            'id',
            'time',
            'signature',
            'personal_reason',
            'mood_option_id',
            'mood_reason_id',
        );
    }

    public function mapAttr($row) {
        $this->id = $row->id;
        $this->time = $row->time;
        $this->signature = $row->signature;
        $this->personal_reason = $row->personal_reason;
        $this->mood_option_id = $row->mood_option_id;
        $this->mood_reason_id = $row->mood_reason_id;

        return 1;
    }

    function store($signature, $mood_option_id, $mood_reason_id, $room_id, $personal_reason = null) {
        $time = new DateTime(null, new DateTimeZone('Europe/Zagreb'));
        $query = "INSERT INTO mood (time, signature, personal_reason, mood_option_id, mood_reason_id, room_id)
                  VALUES (:time, :signature, :personal_reason, :mood_option_id, :mood_reason_id, :room_id)";
        try {
            $result = $this->database->connection->prepare($query);
            $result->execute(array(
                "time" => $time->format('Y-m-d\TH:i:s.u'),
                "signature" => $signature,
                "personal_reason" => $personal_reason,
                "mood_option_id" => $mood_option_id,
                "mood_reason_id" => $mood_reason_id,
                "room_id" => $room_id,
            ));
            $new_id = $this->database->connection->lastInsertId();

            $this->mapAttr((object)array(
                "id" => $new_id,
                "time" => $time,
                "signature" => $signature,
                "personal_reason" => $personal_reason,
                "mood_option_id" => $mood_option_id,
                "mood_reason_id" => $mood_reason_id,
            ));

            return $new_id;
        } catch(\PDOException $e) {
            return -1;
        }
    }

}
