<?php
require_once '../vendor/autoload.php';
use Models\Room;

//ignore_user_abort(false);
set_time_limit(40);

while (true) {
    $last_ajax_call = isset($_GET['timestamp']) ? $_GET['timestamp'] : "1970-01-01 00:00:00";
    /*
    TODO kaj ako ne postoji??
    */
    session_start();
    $room_id = $_SESSION["room_id"];
    session_write_close();

    $room = new Room;
    $room->fetch($room_id);

    try {
        /*
        TODO probati kak se ponaša kad se ova varijabla računa i nakon izvršavanja queryja
        */
        $now2 = new DateTime(null, new DateTimeZone('Europe/Zagreb'));

        $comments = $room->fetch_comments($last_ajax_call);
        $questions = $room->fetch_questions($last_ajax_call);
        $moods = $room->fetch_moods($last_ajax_call);
        /*
        TODO probati kak se ponaša kad se ova varijabla računa i prije izvršavanja queryja
        */
		$now = new DateTime(null, new DateTimeZone('Europe/Zagreb'));

		if(sizeof($comments) > 0 || sizeof($questions) > 0 || sizeof($moods) > 0) {
            header('Content-type:application/json;charset=utf-8');
			echo json_encode(array(
				"success" => true,
                "error" => null,
                'comments' => $comments,
                'moods' => $moods,
	            'questions' => $questions,
	            'timestamp' => $now2->add(new DateInterval('PT1S'))->format('Y-m-d H:i:s'),
        	));
			exit();
		} else {
			sleep(3);
        }
	} catch (\PDOException $e) {
        header('Content-type:application/json;charset=utf-8');
		echo json_encode(array(
			"success" => false,
			"error" => $e->getMessage(),
            'comments' => array(),
            'moods' => array(),
            'questions' => array(),
            'timestamp' => $last_ajax_call,
    	));
		exit();
    }
}
