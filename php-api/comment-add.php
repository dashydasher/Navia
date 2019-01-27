<?php
require_once '../vendor/autoload.php';
use Models\Comment;
use Models\Helper;

if (!isset($_POST['signature']) || !isset($_POST['comment'])) {
    header('HTTP/1.1 400 Bad Request');
} else {
    // on vec postavlja Content-type:application/json u header
    $room_id = Helper::provjeri_aktivnost_sobe_vrati_id();

    // dohvati parametre
    $signature = Helper::xssafe($_POST['signature']);
    $content = Helper::xssafe($_POST['comment']);

    $comment = new Comment;

    $result = $comment->store($signature, $content, $room_id);
    if ($result > 0) {
        /*
        pamti studentove komentare za svaku sobu di je ušao, a to služi za prikaz svih postavljenih komentara kad student izađe iz sobe pa se ponovo vrati u nju.
        dictionary služi da bi se moglo spremiti više soba (poseban array za svaku sobu di je student pristupio).
        ključ je ID sobe, a vrijednosti su samo neki atributi klase Comment (comment) definirani u modelu.
        */
        if (!isset($_SESSION["comments"][$room_id])) {
            $_SESSION["comments"][$room_id] = array();
        }
        // stavi komentar na prvo mjesto
        array_unshift($_SESSION["comments"][$room_id], $comment);

        echo json_encode(array(
            "success" => true,
            "error" => null,
            "comment" => $comment
        ));
        exit();
    } else {
        $errors = include(__DIR__ . '/../config/errors.php');

        echo json_encode(array(
            "success" => false,
            "error" => $errors->neuspjesno_dodavanje_kometara,
        ));
        exit();
    }
}
