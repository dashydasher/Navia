<?php
require_once '../vendor/autoload.php';
use Models\Comment;

if (!isset($_POST['signature']) || !isset($_POST['comment'])) {
    header('HTTP/1.1 400 Bad Request');
} else {
    header('Content-type:application/json;charset=utf-8');

    $signature = $_POST['signature'];
    $content = $_POST['comment'];

    $comment = new Comment;

    session_start();
    $room_id = $_SESSION["entered_room_id"];
    $result = $comment->store($signature, $content, $room_id);
    if ($result > 0) {
        echo json_encode(array(
            "success" => true,
            "error" => null,
            "comment" => $comment
        ));
        exit();
    } else {
        echo json_encode(array(
            "success" => false,
            "error" => "Došlo je do pogreške prilikom dodavanja komentara",
            "comment" => null,
        ));
        exit();
    }
}
