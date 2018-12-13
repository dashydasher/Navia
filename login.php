<?php
require_once 'vendor/autoload.php';
use Models\Helper;
use Models\Teacher;

$twig = new Twig_Environment(new Twig_Loader_Filesystem('public/views'));
session_start();

if (empty($_POST)) {
    echo $twig->render('login.html.twig', array(
        "error_list" => isset($_SESSION["error"]) ? $_SESSION["error"] : null
    ));
    unset($_SESSION["error"]);
} else {
    $nema_svih_podataka = false;
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        $nema_svih_podataka = true;
    }
    $username = Helper::xssafe($_POST['username']);
    $password = Helper::xssafe($_POST['password']);

    $teacher = new Teacher;
    $result = $teacher->login_check($username, $password);
    // postoji takav u bazi
    if ($result > 0) {
        // stavi ime u cookie i jođ možda druge stvari
        $_SESSION["success"] = "Uspješno ste se prijavili";
        $_SESSION["my_id"] = $teacher->id;
        $_SESSION["my_name"] = $teacher->name;
        header("Location: profesor.php");
        exit();
    } else {
        echo $twig->render('login.html.twig', array(
            "username" => $username,
            "error_list" => array("Podaci za prijavu nisu ispravni"),
        ));
    }

}
