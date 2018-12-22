<?php
require_once '../vendor/autoload.php';
use Models\MoodReason;

if (!isset($_POST['mood-reason']) || !isset($_POST['type'])) {
    header('HTTP/1.1 400 Bad Request');
} else {
    header('Content-type:application/json;charset=utf-8');

    $mood_reason_text = $_POST['mood-reason'];
    $type = $_POST['type'];

    $mood_reason = new MoodReason;

    session_start();
    $teacher_id = $_SESSION["my_id"];
    session_write_close();

    $result = $mood_reason->store($mood_reason_text, $type, $teacher_id);
    if ($result > 0) {
        echo json_encode(array(
            "success" => true,
            "error" => null,
            "mood_reason" => $mood_reason,
        ));
        exit();
    } else {
        echo json_encode(array(
            "success" => false,
            "error" => "Došlo je do pogreške prilikom dodavanja razloga promjene raspoloženja",
            "mood_reason" => null,
        ));
        exit();
    }
}
