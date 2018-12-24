<?php
require_once 'vendor/autoload.php';
use Models\Room;
use Models\Teacher;

$twig = new Twig_Environment(new Twig_Loader_Filesystem('public/views'));

session_start();
if (!isset($_SESSION["entered_room_id"])) {
    header("Location: index.php");
    exit();
} else {
    $room = new Room;
    $room->fetch($_SESSION["entered_room_id"]);

    $teacher = new Teacher;
    $reasons = $teacher->fetch_mood_reasons($room->teacher_id);

    echo $twig->render('student.html.twig', array(
        "reasons" => $reasons,
        "current_mood" => isset($_SESSION["current_mood"]) ? $_SESSION["current_mood"] : null,
    ));
}
