<?php
require_once 'vendor/autoload.php';
use Models\Room;
use Models\Helper;

$twig = new Twig_Environment(new Twig_Loader_Filesystem('public/views'));

if (empty($_POST)) {
    echo $twig->render('index.html.twig', array());
} else {
    $room_key = Helper::xssafe($_POST['room_key']);

    $room = new Room;
    $result = $room->fetch_by_key($room_key);

    if ($result > 0 && $room->active == 1) {
        session_start();
        $_SESSION["entered_room_id"] = $room->id;
        header("Location: student.php");
        exit();
    } else {
        echo $twig->render('index.html.twig', array(
            "room_key" => $room_key,
            "error_list" => array("Soba trenutno nije aktivna ili ne postoji"),
        ));
        exit();
    }
}
