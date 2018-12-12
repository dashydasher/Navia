<?php
require_once 'vendor/autoload.php';
use Models\Database;

$db = new Database;

$twig = new Twig_Environment(new Twig_Loader_Filesystem('public/views'));

echo $twig->render('student.html.twig', array(
));
