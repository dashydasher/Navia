<?php
require_once 'vendor/autoload.php';
use Models\Teacher;

$twig = new Twig_Environment(new Twig_Loader_Filesystem('public/views'));

session_start();
if(isset($_SESSION["my_id"])) {

    $teacher = new Teacher;
    $teacher->fetch($_SESSION["my_id"]);
    $teacher->fetch_rooms();
    $rooms = $teacher->rooms;

    echo $twig->render('profesor.html.twig', array(
        "name" => $_SESSION["my_name"],
        "success" => isset($_SESSION["success"]) ? $_SESSION["success"] : null,
        "error_list" => isset($_SESSION["error"]) ? $_SESSION["error"] : null,
        "rooms" => $rooms,
    ));
    unset($_SESSION["success"]);
    unset($_SESSION["error"]);
    exit();
} else {
    $_SESSION["error"] = array("Molimo prijavite se da bi nastavili");
    header("Location: login.php");
    exit();
}
