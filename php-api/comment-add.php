<?php
require_once '../vendor/autoload.php';
use Models\Comment;
use Models\Helper;

if (!isset($_POST['signature']) || !isset($_POST['comment'])) {
    header('HTTP/1.1 400 Bad Request');
} else {
    header('Content-type:application/json;charset=utf-8');

    /*
    ako je string prazan nemoj ga staviti u bazu, stavi null.
    */
    $signature = strlen(trim($_POST['signature'])) > 0 ? Helper::xssafe($_POST['signature']) : null;
    $content = Helper::xssafe($_POST['comment']);

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
