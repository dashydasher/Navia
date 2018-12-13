<?php
require_once 'vendor/autoload.php';
use Models\Database;
use Models\Teacher;
use Models\Room;
use Models\Mood;
use Models\Comment;
use Models\Question;

/*
// FILL DATABASE

// Teacher
$teacher1 = new Teacher;
$teacher1->store("user1", "pass1", "ime1");

$teacher2 = new Teacher;
$teacher2->store("user2", "pass2", "ime2");

// Room
$room1 = new Room;
$room1->store("Prva soba prvog ucitelja.", $teacher1->id);
$room2 = new Room;
$room2->store("Druga soba prvog ucitelja.", $teacher1->id);
$room3 = new Room;
$room3->store("Treca soba prvog ucitelja.", $teacher1->id);
$room4 = new Room;
$room4->store("Prva soba drugog ucitelja.", $teacher2->id);
$room5 = new Room;
$room5->store("Druga soba drugog ucitelja.", $teacher2->id);

// Comment
$comment1 = new Comment;
$comment1->store("", "1. komentar 1. sobe 1. ucitelja.", $room1->id);
$comment2 = new Comment;
$comment2->store("", "2. komentar 1. sobe 1. ucitelja.", $room1->id);
$comment3 = new Comment;
$comment3->store("", "3. komentar 1. sobe 1. ucitelja.", $room1->id);

// Question
$question1 = new Question;
$question1->store("", "1. pitanje 1. sobe 1. ucitelja.", $room1->id);
$question2 = new Question;
$question2->store("Toni", "2. pitanje 1. sobe 1. ucitelja.", $room1->id);
*/



// TEST DATABASE

// Teacher
$teacher1 = new Teacher;
$uspjeh_logina1 = $teacher1->login_check("user1", "pass1");
$teacher1->fetch_rooms();
$teacher1->rooms[0]->fetch_comments();
var_dump($teacher1);

// Room
$room = new Room;
$room->fetch($teacher1->rooms[0]->id);
$room->fetch_comments();
$room->fetch_questions();
var_dump($room);
