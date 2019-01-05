<?php
require_once 'vendor/autoload.php';
use Models\Room;

$twig = new Twig_Environment(new Twig_Loader_Filesystem('public/views'));

$room_id = htmlspecialchars($_GET["id"]);
$room = new Room;
$result = $room->fetch($room_id);

session_start();
if ($result > 0 && $_SESSION["my_id"] == $room->teacher_id) {
    $_SESSION["room_id"] = $room_id;

    $room->fetch_comments(0);
    $room->fetch_questions(0);
    $room->fetch_moods(0);

    // početno vrijeme bude kad se pojavi prvo raspoloženje
    // zadnje vrijeme je kad se pojavi zadnje raspoloženje
    $min_time = null;
    $max_time = null;
    foreach ($room->moods as $mood) {
        $mood_time = new DateTime($mood->time);
        if ($min_time === null || $mood_time < $min_time) {
            $min_time = $mood_time;
        }
        if ($max_time === null || $mood_time > $max_time) {
            $max_time = $mood_time;
        }
    }
    $pocetno_vrijeme = $min_time;
    $maksimalno_vrijeme = $max_time;

    // kolko česti da budu intervali
    $minute_interval = 15;

    $minutes = date('i', strtotime($min_time->format('Y-m-d H:i:s')));
    $oduzmi_minute = intval($minutes - intval($minutes / $minute_interval) * $minute_interval);
    // prvi interval je ustvari n*15 minuta od punog sata, ali tako da to obuhvaća prvo raspoloženje
    // ako je prvo raspoloženje u 18:31 onda je prvi interval u 18:45
    // ovjde samo dobivamo 18:30, a tek u petlji to povećavamo za 15 min
    $pocetno_vrijeme->sub(new DateInterval('PT' . $oduzmi_minute . 'M'));

    // sluzi za petlju
    $trenutno_vrijeme = new DateTime($pocetno_vrijeme->format('Y-m-d H:i:s'));

    $prikazi_moodove = array();

    // ova petlja je nesto skracena jer koristi varijablu $my_moods koja svaki put
    // izbaci mood iz sebe kad se taj mood obradi
    $my_moods = $room->moods;

    $prva_petlja = true;
    while($trenutno_vrijeme < $maksimalno_vrijeme) {
        if (!$prva_petlja) {
            $prethodni_interval = new DateTime($trenutno_vrijeme->format('Y-m-d H:i:s'));
        } else {
            // donji interval je "beskonačan"
            $prethodni_interval = new DateTime('1970-01-01 00:00:00');
            $pocetno_vrijeme->add(new DateInterval('PT' . $minute_interval . 'M'));
            $prva_petlja = false;
        }
        $trenutno_vrijeme->add(new DateInterval('PT' . $minute_interval . 'M'));

        $interval_key = $trenutno_vrijeme->format('H:i');

        foreach ($my_moods as $mood_key => $mood) {
            $mood_time = new DateTime($mood->time);

            if ($mood_time > $prethodni_interval && $mood_time <= $trenutno_vrijeme) {
                #$prikazi_moodove[$mood->id] = $mood;
                $prikazi_moodove[$mood->id] = $mood->parent_mood_id;

                // ako ima roditelja onda roditelja nemoj u budućim intervalima prikazivati jer
                // je on preuzeo njegovu ulogu
                if ($mood->parent_mood_id) {
                    unset($prikazi_moodove[$mood->parent_mood_id]);
                }

                // izbaci ga iz polja da se ponovo ne provjerava
                unset($my_moods[$mood_key]);
            }
        }
        $moods_intervals[$interval_key] = $prikazi_moodove;
    }

    var_dump($pocetno_vrijeme->format('H:i'));
    var_dump($maksimalno_vrijeme->format('H:i'));
    var_dump($moods_intervals);

    echo $twig->render('room-stats.html.twig', array(
        "name" => $_SESSION["my_name"],
        "success" => isset($_SESSION["success"]) ? $_SESSION["success"] : null,
        "error_list" => isset($_SESSION["error"]) ? $_SESSION["error"] : null,
        "room" => $room,
        "moods_intervals" => $moods_intervals,
        "time_start" => $pocetno_vrijeme->format('H:i'),
        "time_end" => $maksimalno_vrijeme->format('H:i'),
    ));

    unset($_SESSION["success"]);
    unset($_SESSION["error"]);
} else {
    $_SESSION["error"] = array("Nemate prava pristupa toj sobi");
    header("Location: profesor.php");
    exit();
}
