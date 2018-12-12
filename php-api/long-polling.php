<?php
require_once '../vendor/autoload.php';
use Models\Database;

session_write_close();
ignore_user_abort(false);
set_time_limit(30);

while (true) {
    $last_ajax_call = isset($_GET['timestamp']) ? (int)$_GET['timestamp'] : null;
    $sql_timestamp = date("Y-m-d H:i:s", $last_ajax_call);

    /*
    TODO kreiraj klasu comment, mood, question?
    */
    $db = new Database;

    /*
    TODO dohvati iz cookieja?
    */
    $room_id = 1;

    $query = "SELECT * FROM comment WHERE room_id = :room_id AND `time` >= :last_call";
    $query2 = "SELECT * FROM mood WHERE room_id = :room_id AND `time` >= :last_call";
    $query3 = "SELECT * FROM question WHERE room_id = :room_id AND `time` >= :last_call";

    try {
        $result = $db->connection->prepare($query);
        $result2 = $db->connection->prepare($query2);
        $result3 = $db->connection->prepare($query3);

        /*
        probati kak se ponaša kad se ova varijabla računa i nakon izvršavanja queryja
        */
        $now2 = time();

        $result->execute(array(
            "room_id" => $room_id,
            "last_call" => $sql_timestamp,
        ));
        $result2->execute(array(
            "room_id" => $room_id,
            "last_call" => $sql_timestamp,
        ));
        $result3->execute(array(
            "room_id" => $room_id,
            "last_call" => $sql_timestamp,
        ));
        /*
        probati kak se ponaša kad se ova varijabla računa i prije izvršavanja queryja
        */
		$now = time();

		if($result->rowCount() > 0 || $result2->rowCount() > 0 || $result3->rowCount() > 0) {
			echo json_encode(array(
				"success" => true,
                "error" => null,
                'comments' => $result->fetchAll(\PDO::FETCH_OBJ),
                'moods' => $result2->fetchAll(\PDO::FETCH_OBJ),
	            'questions' => $result3->fetchAll(\PDO::FETCH_OBJ),
	            'timestamp' => $now,
        	));
			break;
		} else {
			sleep(2);
		}
    } catch (\PDOException $e) {
		echo json_encode(array(
			"success" => false,
			"error" => $e->getMessage(),
            'comments' => array(),
            'moods' => array(),
            'questions' => array(),
            'timestamp' => $last_ajax_call,
    	));
		break;
    }
    break;
}
