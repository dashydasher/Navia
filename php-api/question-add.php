<?php
require_once '../vendor/autoload.php';
use Models\Question;
use Models\Helper;

if (!isset($_POST['signature']) || !isset($_POST['question'])) {
    header('HTTP/1.1 400 Bad Request');
} else {
    // on vec postavlja Content-type:application/json u header
    $room_id = Helper::provjeri_aktivnost_sobe_vrati_id();

    $signature = Helper::xssafe($_POST['signature']);
    $question_text = Helper::xssafe($_POST['question']);

    $question = new Question;

    $result = $question->store($signature, $question_text, $room_id);
    if ($result > 0) {
        echo json_encode(array(
            "success" => true,
            "error" => null,
            "question" => $question
        ));
        exit();
    } else {
        $errors = include(__DIR__ . '/../config/errors.php');

        echo json_encode(array(
            "success" => false,
            "error" => $errors->neuspjesno_dodavanje_pitanja,
            "question" => null
        ));
        exit();
    }
}
