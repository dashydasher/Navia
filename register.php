<?php
require_once 'vendor/autoload.php';
use Models\Helper;
use Models\Teacher;

$twig = new Twig_Environment(new Twig_Loader_Filesystem('public/views'));

if (empty($_POST)) {
    header("Location: login.php");
    exit();
} else {
    $nema_svih_podataka = false;
    if (!isset($_POST['first']) || !isset($_POST['last']) || !isset($_POST['uid']) || !isset($_POST['pwd'])) {
        $nema_svih_podataka = true;
    }
    $name = Helper::xssafe($_POST['first']);
    $last_name = Helper::xssafe($_POST['last']);
    $username = Helper::xssafe($_POST['uid']);
    $password = Helper::xssafe($_POST['pwd']);

    if ($nema_svih_podataka == false) {
        $teacher = new Teacher;
        $result = $teacher->store($username, $password, "$name $last_name");
        // uspjesno kreiran
        if ($result > 0) {
            // stavi ime u cookie i jođ možda druge stvari
            setcookie("success", "Uspješno ste se registrirali");
            setcookie("my_name", $teacher->name);
            header("Location: profesor.php");
            exit();
        } else {
            echo $twig->render('login.html.twig', array(
                "first" => $name,
                "last" => $last_name,
                "uid" => $username,
                "error_list" => array("Username zauzet"),
            ));
            exit();
        }
    } else {
        echo $twig->render('login.html.twig', array(
            "first" => $name,
            "last" => $last_name,
            "uid" => $username,
            "error_list" => array("Niste unijeli sve podatke"),
        ));
        exit();
    }
}
