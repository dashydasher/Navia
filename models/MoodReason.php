<?php
namespace Models;
use \DateTime;
use \DateTimeZone;

class MoodReason {
    public $id;
    public $reason;
    public $type;
    public $active;
    public $teacher_id;

    private $database;

    function __construct() {
        $this->database = new Database;
    }

    public function mapAttr($row) {
        $this->id = $row->id;
        $this->reason = $row->reason;
        $this->type = $row->type;
        $this->teacher_id = $row->teacher_id;

        return 1;
    }

    function store($reason, $type, $teacher_id) {
        $query = "INSERT INTO mood_reason (reason, type, teacher_id) VALUES (:reason, :type, :teacher_id)";
        try {
            $result = $this->database->connection->prepare($query);
            $result->execute(array(
                "reason" => $reason,
                "type" => $type,
                "teacher_id" => $teacher_id,
            ));
            $new_id = $this->database->connection->lastInsertId();

            $this->mapAttr((object)array(
                "id" => $new_id,
                "reason" => $reason,
                "type" => $type,
                "active" => 1,
                "teacher_id" => $teacher_id,
            ));

            return $new_id;
        } catch(\PDOException $e) {
            return -1;
        }
    }

    function deactivate($id, $teacher_id) {
        $query = "UPDATE `mood_reason` SET `active` = '0' WHERE `mood_reason`.`id` = :id AND `mood_reason`.`teacher_id` = :teacher_id";
        try {
            $result = $this->database->connection->prepare($query);
            $result->execute(array(
                    "id" => $id,
                    "teacher_id" => $teacher_id,
                ));
            $this->active = 0;

            return 1;
        } catch(\PDOException $e) {
            return -1;
        }
    }
}
