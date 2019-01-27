<?php
require_once '../vendor/autoload.php';
use Models\Room;
use Models\Helper;

if (!isset($_POST['room-id']) || !isset($_POST['activate'])) {
    header('HTTP/1.1 400 Bad Request');
} else {
    $teacher_id = Helper::provjeri_prava_profesora_vrati_id();

    header('Content-type:application/json;charset=utf-8');

    // dohvati parametre
    $room_id = $_POST['room-id'];
    // activate je 0 (deaktiviraj) ili 1 (aktiviraj)
    $activate = $_POST['activate'];

    $room = new Room;

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
        $errors = include(__DIR__ . '/../config/errors.php');

        echo json_encode(array(
            "success" => false,
            "active" => null,
            "error" => $errors->neuspjesna_aktivacija_sobe,
        ));
        exit();
    }
}
