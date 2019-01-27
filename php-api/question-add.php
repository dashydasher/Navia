<?php
require_once '../vendor/autoload.php';
use Models\Question;
use Models\Helper;

if (!isset($_POST['signature']) || !isset($_POST['question'])) {
    header('HTTP/1.1 400 Bad Request');
} else {
    // on vec postavlja Content-type:application/json u header
    $room_id = Helper::provjeri_aktivnost_sobe_vrati_id();

    // dohvati parametre
    $signature = Helper::xssafe($_POST['signature']);
    $question_text = Helper::xssafe($_POST['question']);

    $question = new Question;

    $result = $question->store($signature, $question_text, $room_id);
    if ($result > 0) {
        /*
        pamti studentova pitanja za svaku sobu di je ušao, a to služi za prikaz svih postavljenih pitanja kad student izađe iz sobe pa se ponovo vrati u nju.
        dictionary služi da bi se moglo spremiti više soba (poseban array za svaku sobu di je student pristupio).
        ključ je ID sobe, a vrijednosti su samo neki atributi klase Question (question) definirani u modelu.
        */
        if (!isset($_SESSION["questions"][$room_id])) {
            $_SESSION["questions"][$room_id] = array();
        }
        // stavi pitanje na prvo mjesto
        array_unshift($_SESSION["questions"][$room_id], $question);

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
