<?php
require_once '../vendor/autoload.php';
use Models\Comment;

if (!isset($_POST['comment'])) {
    header('HTTP/1.1 400 Bad Request');
} else {
    $signature = $_POST['signature'];
    $content = $_POST['comment'];
    $room_id = $_POST['room_id'];

    $comment = new Comment;
    $result = $comment->store($signature, $content, $room_id);
    if ($result > 0) {
        echo json_encode(
            "success" => true,
            "comment" => $content
        );
    } else {
        echo json_encode(
            "success" => false
        );
    }
}
