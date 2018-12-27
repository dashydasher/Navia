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
    public $room_id;
    public $parent_mood_id;

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
            'id',
            'mood_option_id',
        );
    }

    public function mapAttr($row) {
        $this->id = $row->id;
        $this->time = $row->time;
        $this->signature = $row->signature;
        $this->personal_reason = $row->personal_reason;
        $this->mood_option_id = $row->mood_option_id;
        $this->mood_reason_id = $row->mood_reason_id;
        $this->room_id = $row->room_id;
        $this->parent_mood_id = $row->parent_mood_id;

        return 1;
    }

    /*
    ako se funkcija pozove s parametrom personal_reason onda se on upisuje u bazu, a mood_reason_id postaje null.
    inače, mood_reason_id ima neku vrijednost, a personal_reason je null (jer je funkcija pozvana bez tog parametra)
    */
    function store($signature, $mood_option_id, $mood_reason_id, $room_id, $parent_mood_id, $personal_reason) {
        $time = new DateTime(null, new DateTimeZone('Europe/Zagreb'));
        $query = "INSERT INTO mood (time, signature, personal_reason, mood_option_id, mood_reason_id, room_id, parent_mood_id)
                  VALUES (:time, :signature, :personal_reason, :mood_option_id, :mood_reason_id, :room_id, :parent_mood_id)";
        try {
            $result = $this->database->connection->prepare($query);
            $result->execute(array(
                "time" => $time->format('Y-m-d\TH:i:s.u'),
                "signature" => $signature,
                "personal_reason" => $personal_reason,
                "mood_option_id" => $mood_option_id,
                "mood_reason_id" => $personal_reason === null ? $mood_reason_id : null,
                "room_id" => $room_id,
                "parent_mood_id" => $parent_mood_id,
            ));
            $new_id = $this->database->connection->lastInsertId();

            $this->mapAttr((object)array(
                "id" => $new_id,
                "time" => $time,
                "signature" => $signature,
                "personal_reason" => $personal_reason,
                "mood_option_id" => $mood_option_id,
                "mood_reason_id" => $mood_reason_id,
                "room_id" => $room_id,
                "parent_mood_id" => $parent_mood_id,
            ));

            return $new_id;
        } catch(\PDOException $e) {
            return -1;
        }
    }

}
