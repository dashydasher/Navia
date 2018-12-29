<?php
require_once '../vendor/autoload.php';
use Models\MoodReason;
use Models\Helper;

if (!isset($_POST['mood-reason-id'])) {
    header('HTTP/1.1 400 Bad Request');
} else {
    $teacher_id = Helper::provjeri_prava_profesora_vrati_id();

    header('Content-type:application/json;charset=utf-8');

    $mood_reason_id = $_POST['mood-reason-id'];

    $mood_reason = new MoodReason;

    $result = $mood_reason->deactivate($mood_reason_id, $teacher_id);
    if ($result > 0) {
        echo json_encode(array(
            "success" => true,
            "error" => null,
        ));
        exit();
    } else {
        echo json_encode(array(
            "success" => false,
            "error" => $errors->neuspjesno_brisanje_razloga_raspolozenja,
        ));
        exit();
    }
}
