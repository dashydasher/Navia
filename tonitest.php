<?php
require_once 'vendor/autoload.php';
use Models\Database;
use Models\Teacher;
use Models\Room;

$teacher = new Teacher;
$uspjeh_logina = $teacher->login_check("user2", "pass2");
var_dump($uspjeh_logina);
var_dump($teacher);
$rooms = $teacher->fetch_rooms();
var_dump($teacher);
