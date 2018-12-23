<?php
require_once 'vendor/autoload.php';
use Models\Room;

$twig = new Twig_Environment(new Twig_Loader_Filesystem('public/views'));

session_start();
if (!isset($_SESSION["entered_room_id"])) {
    header("Location: index.php");
    exit();
} else {
    $room = new Room;
    $room->fetch($_SESSION["entered_room_id"]);
    $teacher_id = $room->teacher_id;

    // TODO dovati sve mood reasone za tog profesora čija je to soba



    echo $twig->render('student.html.twig', array(
        "reasons" => array(),
    ));
}
