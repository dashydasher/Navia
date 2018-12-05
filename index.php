<?php
require_once 'vendor/autoload.php';
use Models\Database;

$loader = new Twig_Loader_Filesystem('public/views');
$twig = new Twig_Environment($loader);

echo $twig->render('index.html.twig', array(
    "tekst" => "radi!",
));
