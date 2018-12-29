<?php
require_once '../vendor/autoload.php';
use Models\MoodReason;
use Models\Helper;

if (!isset($_POST['mood-reason']) || !isset($_POST['type'])) {
    header('HTTP/1.1 400 Bad Request');
} else {
    $teacher_id = Helper::provjeri_prava_profesora_vrati_id();

    header('Content-type:application/json;charset=utf-8');

    $mood_reason_text = Helper::xssafe($_POST['mood-reason']);
    $type = $_POST['type'];

    $mood_reason = new MoodReason;

    $result = $mood_reason->store($mood_reason_text, $type, $teacher_id);
    if ($result > 0) {
        echo json_encode(array(
            "success" => true,
            "error" => null,
            "mood_reason" => $mood_reason,
        ));
        exit();
    } else {
        $errors = include(__DIR__ . '/../config/errors.php');

        echo json_encode(array(
            "success" => false,
            "error" => $errors->neuspjesno_dodavanje_razloga_raspolozenja,
        ));
        exit();
    }
}
