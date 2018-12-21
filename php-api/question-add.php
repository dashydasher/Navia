<?php
require_once '../vendor/autoload.php';
use Models\Question;

if (!isset($_POST['signature']) || !isset($_POST['question'])) {
    header('HTTP/1.1 400 Bad Request');
} else {
    header('Content-type:application/json;charset=utf-8');

    $signature = $_POST['signature'];
    $question_text = $_POST['question'];

    $question = new Question;

    session_start();
    $room_id = $_SESSION["entered_room_id"];
    $result = $question->store($signature, $question_text, $room_id);
    if ($result > 0) {
        echo json_encode(array(
            "success" => true,
            "error" => null,
            "question" => $question
        ));
        exit();
    } else {
        echo json_encode(array(
            "success" => false,
            "error" => "Došlo je do pogreške prilikom dodavanja pitanja",
            "question" => null
        ));
        exit();
    }
}
