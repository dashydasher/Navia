<?php
require_once 'vendor/autoload.php';
use Models\Helper;
use Models\Teacher;

$twig = new Twig_Environment(new Twig_Loader_Filesystem('public/views'));

if (empty($_POST)) {
    echo $twig->render('login.html.twig', array(
    ));
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
        setcookie("success", "Uspješno ste se prijavili");
        setcookie("my_name", $teacher->name);
        header("Location: profesor.php");
        exit();
    } else {
        echo $twig->render('login.html.twig', array(
            "username" => $username,
            "error_list" => array("Podaci za prijavu nisu ispravni"),
        ));
    }

}
