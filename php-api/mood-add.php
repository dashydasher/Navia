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
        $result = $mood->store($signature, $mood_option_id, $reason_id, $room_id, null);
    }
    if ($result > 0) {
        /*
        u session sprema trenutno raspoloženje.
        array služi da bi se moglo spremiti više raspoloženja (svaki za jednu sobu di je student pristupio)
        TODO makni prethodno raspoloženje za tu sobu
        */
        if (isset($_SESSION["current_mood"])) {
            //unset($_SESSION["current_mood"][$mood->parent_id]);
        }
        $_SESSION["current_mood"][$mood->id] = $mood;

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
