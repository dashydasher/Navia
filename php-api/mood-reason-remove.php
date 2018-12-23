<?php
require_once '../vendor/autoload.php';
use Models\MoodReason;

if (!isset($_POST['mood-reason-id'])) {
    header('HTTP/1.1 400 Bad Request');
} else {
    header('Content-type:application/json;charset=utf-8');

    $mood_reason_id = $_POST['mood-reason-id'];

    $mood_reason = new MoodReason;

    session_start();
    $teacher_id = $_SESSION["my_id"];
    session_write_close();

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
            "error" => "Došlo je do pogreške prilikom brisanja razloga promjene raspoloženja",
        ));
        exit();
    }
}
