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
$comment2->store("003648431", "2. komentar 1. sobe 1. ucitelja.", $room1->id);
$comment3 = new Comment;
$comment3->store("003643533", "3. komentar 1. sobe 1. ucitelja.", $room1->id);

// Question
$question1 = new Question;
$question1->store("", "1. pitanje 1. sobe 1. ucitelja.", $room1->id);
$question2 = new Question;
$question2->store("Toni", "2. pitanje 1. sobe 1. ucitelja.", $room1->id);


// Mood
$mood1 = new Mood();
$mood1->store("", 3, 1, $room1->id);
$mood2 = new Mood();
$mood2->store("Petar", 2, 3, $room1->id);
$mood3 = new Mood();
$mood3->store("Toni", 3, 1, $room1->id);
$mood4 = new Mood();
$mood4->store("Lucija", 1, 2, $room1->id);
$mood5 = new Mood();
$mood5->store("Matea", 3, 4, $room1->id);
*/



// TEST DATABASE

// Teacher
$teacher1 = new Teacher;
$uspjeh_logina1 = $teacher1->login_check("user1", "pass1");
$teacher1->fetch_rooms();
$teacher1->rooms[0]->fetch_comments();
$teacher1->rooms[0]->fetch_questions();
$teacher1->rooms[0]->fetch_moods();
var_dump($teacher1);

// Room
$room = new Room;
$room->fetch($teacher1->rooms[0]->id);
$room->fetch_comments();
$room->fetch_questions();
$room->fetch_moods();
var_dump($room);
