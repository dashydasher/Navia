<?php
require_once '../vendor/autoload.php';
use Models\Question;
use Models\Helper;

if (!isset($_POST['mood-reason-id'])) {
    header('HTTP/1.1 400 Bad Request');
} else {
    $room_id = Helper::provjeri_prava_profesora_vrati_id_sobe();

    header('Content-type:application/json;charset=utf-8');

    $question_id = $_POST['question_id'];

    $question = new Question;

    $result = $question->delete($question_id, $room_id);
    if ($result > 0) {
        echo json_encode(array(
            "success" => true,
            "error" => null,
        ));
        exit();
    } else {
        $errors = include(__DIR__ . '/../config/errors.php');

        echo json_encode(array(
            "success" => false,
            "error" => $errors->neuspjesno_brisanje_pitanja,
        ));
        exit();
    }
}
