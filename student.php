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

    /*
    kad se student prvi puta pridružuje sobi dobiva ovo raspoloženje.
    1 - sretno
    2 - neutralno
    3 - tužno
    to je iz baze...
    */
    $default_mood_id = 1;
    
    /*
    ako postoji, dohvati trenutno raspoloženje za sobu kojoj pokušavaš pristupiti
    */
    $current_room_mood = null;
    if (isset($_SESSION["current_moods"][$room->id])) {
        $current_room_mood = $_SESSION["current_moods"][$room->id];
    }

    /*
    ako postoji, dohvati objavljene komentare za sobu kojoj pokušavaš pristupiti
    */
    $comments = null;
    if (isset($_SESSION["comments"][$room->id])) {
        $comments = $_SESSION["comments"][$room->id];
    }

    /*
    ako postoji, dohvati postavljena pitanja za sobu kojoj pokušavaš pristupiti
    */
    $questions = null;
    if (isset($_SESSION["questions"][$room->id])) {
        $questions = $_SESSION["questions"][$room->id];
    }

    echo $twig->render('student.html.twig', array(
        "reasons" => $reasons,
        "default_mood_id" => $default_mood_id,
        "current_mood" => $current_room_mood,
        "comments" => $comments,
        "questions" => $questions,
    ));
}
