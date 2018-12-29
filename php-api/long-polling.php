<?php
require_once '../vendor/autoload.php';
use Models\Room;
use Models\Helper;

// How often to poll, in seconds
$MESSAGE_POLL_SECONDS = 3;

// How long to keep the Long Poll open, in seconds
$MESSAGE_TIMEOUT_SECONDS = 30;

// Timeout padding in seconds, to avoid a premature timeout in case the last call in the loop is taking a while
$MESSAGE_TIMEOUT_SECONDS_BUFFER = 5;

// Automatically die after timeout (plus buffer)
set_time_limit($MESSAGE_TIMEOUT_SECONDS + $MESSAGE_TIMEOUT_SECONDS_BUFFER);

// id trenutne sobe
$room_id = Helper::provjeri_prava_profesora_vrati_id_sobe();
// instanciraj novu sobu, ali nemoj dohvaćati njezine varijable iz baze
$room = new Room;
// malo glupo rješenje, ali samo nam je id sobe potreban, a njega imamo u sesiji
$room->id = $room_id;

/*
vrti ovu petlju beskonačno (ali maksimalno set_time_limit sekundi).
probaj dohvatiti nove komentare, pitanja i raspoloženja.
ako ima čega novog, vrati JSON i završi izvođenje. inače, čekaj $MESSAGE_POLL_SECONDS i pokušaj ponovo.
*/
while (true) {
    // ako nije postavljen parametar, vrati sve komentare, pitanja i raspoloženja jer su svi sigurno noviji od 1970.
    $last_ajax_call = isset($_GET['timestamp']) ? $_GET['timestamp'] : "1970-01-01 00:00:00";

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

		if (sizeof($comments) > 0 || sizeof($questions) > 0 || sizeof($moods) > 0) {
            header('Content-type:application/json;charset=utf-8');
			echo json_encode(array(
				"success" => true,
                'timestamp' => $now2->add(new DateInterval('PT1S'))->format('Y-m-d H:i:s'),
                'comments' => $comments,
                'moods' => $moods,
	            'questions' => $questions,
        	));
			exit();
		} else {
			sleep($MESSAGE_POLL_SECONDS);
        }
	} catch (\PDOException $e) {
        header('Content-type:application/json;charset=utf-8');
        $errors = include(__DIR__ . '/../config/errors.php');

		echo json_encode(array(
			"success" => false,
            'timestamp' => $last_ajax_call,
            "error" => $errors->long_polling_error,
    	));
		exit();
    }
}
