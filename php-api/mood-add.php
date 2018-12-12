<?php
require_once '../vendor/autoload.php';
use Models\Mood;

if (!isset($_POST['signature']) || !isset($_POST['mood_id']) || !isset($_POST['reason_id']) || !isset($_POST['room_id'])) {
    header('HTTP/1.1 400 Bad Request');
} else {
    $signature = $_POST['signature'];
    $mood_id = $_POST['mood_id'];
    $reason_id = $_POST['reason_id'];
    $room_id = $_POST['room_id'];

    $mood = new Mood;
    $result = $mood->store($signature, $mood_id, $reason_id, $room_id);
    if ($result > 0) {
        echo json_encode(
            "success" => true,
            "mood_id" => $mood_id
        );
    } else {
        echo json_encode(
            "success" => false,
            "mood_id" => null
        );
    }
}
