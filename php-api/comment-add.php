<?php
require_once '../vendor/autoload.php';
use Models\Comment;

if (!isset($_POST['signature']) || !isset($_POST['comment'])) {
    header('HTTP/1.1 400 Bad Request');
} else {
    $signature = $_POST['signature'];
    $content = $_POST['comment'];

    $comment = new Comment;
    /*
    TODO promjeni ovo?
    */
    $room_id = isset($_SESSION["room_id"]) ? $_SESSION["room_id"] : 5;
    $result = $comment->store($signature, $content, $room_id);
    if ($result > 0) {
        echo json_encode(array(
            "success" => true,
            "error" => null,
            "comment" => $content
        ));
    } else {
        echo json_encode(array(
            "success" => false,
            "error" => "Došlo je do pogreške prilikom dodavanja komentara",
            "comment" => null
        ));
    }
}
