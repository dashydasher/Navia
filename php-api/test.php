<?php
require_once '../vendor/autoload.php';
use Models\Database;
$db = new Database;

echo json_encode(array(
    "radi" => true,
    "tekst" => "uspješno radi!"
));
