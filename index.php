<?php
require_once 'vendor/autoload.php';
use Models\Database;

$db = new Database;

$twig = new Twig_Environment(new Twig_Loader_Filesystem('public/views'));

echo $twig->render('index.html.twig', array(
    "tekst" => "radi!",
));












?>

<!DOCTYPE html>
<html>
<head>

  <title></title>
  <link rel="stylesheet" type="text/css" href="public/css/main.css">
</head>
<body>

<header>
    <nav>
        <div class="main-wrapper">
<ul>
    <li><a href="rooms.php">Izlaz iz sobe</a></li>
    <li><a href="login.php">Odjava</a></li>
</ul>

        </div>
    </nav>
</header>

<section>
</section>

</header>




</html>
