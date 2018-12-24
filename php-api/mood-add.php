<?php
require_once '../vendor/autoload.php';
use Models\Mood;
use Models\Helper;

if (!isset($_POST['signature']) || !isset($_POST['mood_option_id']) || !isset($_POST['reason_id']) || !isset($_POST['personal_reason'])) {
    header('HTTP/1.1 400 Bad Request');
} else {
    header('Content-type:application/json;charset=utf-8');

    $signature = Helper::xssafe($_POST['signature']);
    $mood_option_id = Helper::xssafe($_POST['mood_option_id']);
    $reason_id = Helper::xssafe($_POST['reason_id']);
    $personal_reason = Helper::xssafe($_POST['personal_reason']);

    session_start();
    $room_id = $_SESSION["entered_room_id"];

    $mood = new Mood;
    if ($reason_id == "personal") {
        $result = $mood->store($signature, $mood_option_id, null, $room_id, $personal_reason);
    } else {
        $result = $mood->store($signature, $mood_option_id, $reason_id, $room_id);
    }
    if ($result > 0) {
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
