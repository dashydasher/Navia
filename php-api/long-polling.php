<?php
require_once '../vendor/autoload.php';
use Models\Database;

session_write_close();
ignore_user_abort(false);
set_time_limit(30);

while (true) {
    $last_ajax_call = isset($_GET['timestamp']) ? (int)$_GET['timestamp'] : null;

    /*
    TODO kreiraj klasu comment
    */
    $db = new Database;

    $query = "SELECT * FROM comment WHERE room_id = :room_id AND time >= :last_call";
    try {
        $result = $db->connection->prepare($query);
        $result->execute(array(
            "room_id" => 1,
            "last_call" => date("Y-m-d H:i:s", $last_ajax_call),
        ));
		$now = time();

		if($result->rowCount() > 0) {
			echo json_encode(array(
				"success" => true,
	            'comments' => $result->fetchAll(\PDO::FETCH_OBJ),
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
            'timestamp' => $last_ajax_call,
    	));
		break;
    }
    break;
}
