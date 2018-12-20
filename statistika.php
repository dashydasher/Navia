<?php
require_once 'vendor/autoload.php';
use Models\Room;

#$twig = new Twig_Environment(new Twig_Loader_Filesystem('public/views'));

$room_id = htmlspecialchars($_GET["id"]);
$room = new Room;
$result = $room->fetch($room_id);

session_start();
if ($result > 0 && $_SESSION["my_id"] == $room->teacher_id) {
    $_SESSION["room_id"] = $room_id;

    $room->fetch_comments(0);
    $room->fetch_questions(0);
    $room->fetch_moods(0);
    var_dump($room);
    /*
    echo $twig->render('room-stats.html.twig', array(
        "name" => $_SESSION["my_name"],
        "success" => isset($_SESSION["success"]) ? $_SESSION["success"] : null,
        "error_list" => isset($_SESSION["error"]) ? $_SESSION["error"] : null,
        "room" => $room,
    ));
    */
    unset($_SESSION["success"]);
    unset($_SESSION["error"]);
} else {
    $_SESSION["error"] = array("Nemate prava pristupa toj sobi");
    header("Location: profesor.php");
    exit();
}
