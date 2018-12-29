<?php
require_once '../vendor/autoload.php';
use Models\Mood;
use Models\Helper;
use Models\Room;

if (!isset($_POST['signature']) || !isset($_POST['mood_option_id']) || !isset($_POST['reason_id']) || !isset($_POST['personal_reason'])) {
    header('HTTP/1.1 400 Bad Request');
} else {
    // on vec postavlja Content-type:application/json u header
    $room_id = Helper::provjeri_aktivnost_sobe_vrati_id();

    // ako je string prazan nemoj ga staviti u bazu, stavi null.
    $signature = strlen(trim($_POST['signature'])) > 0 ? Helper::xssafe($_POST['signature']) : null;
    $mood_option_id = $_POST['mood_option_id'];
    $reason_id = $_POST['reason_id'];
    $personal_reason = Helper::xssafe($_POST['personal_reason']);

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
        // ako već postoji zapis s tim ključem onda ga prepiši. inače unesi novo raspoloženje za tu sobu.
        $_SESSION["current_moods"][$room_id] = $mood;

        echo json_encode(array(
            "success" => true,
            "mood" => $mood,
            "error" => null,
        ));
        exit();
    } else {
        $errors = include(__DIR__ . '/../config/errors.php');

        echo json_encode(array(
            "success" => false,
            "mood" => null,
            "error" => $errors->neuspjesna_promjena_raspolozenja,
        ));
        exit();
    }
}
