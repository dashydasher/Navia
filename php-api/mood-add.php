<?php
require_once '../vendor/autoload.php';
use Models\Mood;
use Models\Helper;

if (!isset($_POST['signature']) || !isset($_POST['mood_option_id']) || !isset($_POST['reason_id']) || !isset($_POST['personal_reason'])) {
    header('HTTP/1.1 400 Bad Request');
} else {
    header('Content-type:application/json;charset=utf-8');

    /*
    ako je string prazan nemoj ga staviti u bazu, stavi null.
    */
    $signature = strlen(trim($_POST['signature'])) > 0 ? Helper::xssafe($_POST['signature']) : null;
    $mood_option_id = Helper::xssafe($_POST['mood_option_id']);
    $reason_id = Helper::xssafe($_POST['reason_id']);
    $personal_reason = Helper::xssafe($_POST['personal_reason']);

    session_start();
    $room_id = $_SESSION["entered_room_id"];

    /*
    u session sprema trenutno raspoloženje za neku sobu.
    dictionary služi da bi se moglo spremiti više raspoloženja (svaki za jednu sobu di je student pristupio).
    $current_mood_id postaje parent_mood_id novom raspoloženju.
    */
    $current_mood_id = null;
    if (isset($_SESSION["current_moods"][$room_id])) {
        $current_mood_id = $_SESSION["current_moods"][$room_id]->id;
    }

    $mood = new Mood;
    if ($reason_id == "personal") {
        $result = $mood->store($signature, $mood_option_id, null, $room_id, $current_mood_id, $personal_reason);
    } else {
        $result = $mood->store($signature, $mood_option_id, $reason_id, $room_id, $current_mood_id, null);
    }
    if ($result > 0) {
        $_SESSION["current_moods"][$room_id] = $mood;

        echo json_encode(array(
            "success" => true,
            "mood" => $mood,
            "error" => null,
        ));
        exit();
    } else {
        echo json_encode(array(
            "success" => false,
            "mood" => null,
            "error" => "Došlo je do pogreške prilikom promjene raspoloženja",
        ));
        exit();
    }
}
