<?php
require_once '../vendor/autoload.php';
use Models\Room;

if (!isset($_POST['room-id']) || !isset($_POST['activate'])) {
    header('HTTP/1.1 400 Bad Request');
} else {
    header('Content-type:application/json;charset=utf-8');

    $room_id = $_POST['room-id'];
    $activate = $_POST['activate'];

    $room = new Room;

    session_start();
    $teacher_id = $_SESSION["my_id"];
    session_write_close();

    if ($activate == 1) {
        $result = $room->activate($room_id, $teacher_id);
    } else {
        $result = $room->deactivate($room_id, $teacher_id);
    }
    if ($result > 0) {
        echo json_encode(array(
            "success" => true,
            "active" => intval($activate),
            "error" => null,
        ));
        exit();
    } else {
        echo json_encode(array(
            "success" => false,
            "active" => null,
            "error" => "Došlo je do pogreške prilikom aktivacije sobe",
        ));
        exit();
    }
}
