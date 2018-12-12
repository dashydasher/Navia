<?php
require_once '../vendor/autoload.php';
use Models\Question;

if (!isset($_POST['signature']) || !isset($_POST['question']) || !isset($_POST['room_id'])) {
    header('HTTP/1.1 400 Bad Request');
} else {
    $signature = $_POST['signature'];
    $question = $_POST['question'];
    $room_id = $_POST['room_id'];

    $comment = new Comment;
    $result = $comment->store($signature, $question, $room_id);
    if ($result > 0) {
        echo json_encode(
            "success" => true,
            "question" => $question
        );
    } else {
        echo json_encode(
            "success" => false,
            "question" => null
        );
    }
}
