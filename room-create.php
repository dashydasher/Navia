<?php
require_once 'vendor/autoload.php';
use Models\Room;
use Models\Helper;

session_start();
if(isset($_SESSION["my_id"])) {

    if (isset($_POST['room-name'])) {
        $room = new Room;
        $room_id = $room->store(Helper::xssafe($_POST['room-name']), $_SESSION["my_id"], Helper::xssafe($_POST['room-token']));

        // uspjesno
        if ($room_id > 0) {
            $_SESSION["room_id"] = $room_id;
            header("Location: room.php?id=$room_id");
            exit();
        } else {
            $_SESSION["error"] = array("Došlo je do pogreške prilikom kreiranja sobe");
            header("Location: profesor.php");
            exit();
        }
    } else {
        $_SESSION["error"] = array("Unesite opis sobe");
        header("Location: profesor.php");
        exit();
    }

} else {
    $_SESSION["error"] = array("Molimo prijavite se da bi nastavili");
    header("Location: login.php");
    exit();
}
