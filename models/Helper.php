<?php
namespace Models;

class Helper {

    /*
    služi za dohvaćanje korisnikovog inputa koji vjerojatno nije siguran (HTML injection)
    */
    static function xssafe($data, $encoding='UTF-8') {
       return htmlspecialchars($data, ENT_QUOTES | ENT_HTML401, $encoding);
    }

    /*
    koristi se kod php apija sa strane studenta.
    prilikom unosa komentara, pitanja ili promjene raspoloženja se prvo provjerava
        je li osba uopce aktivna. ako nije, onda nije moguće imati interakcije s njom.
    služi za slučajeve kad profesor tijekom predavanja deaktivira sobu (npr tijekom odmora).
    */
    static function provjeri_aktivnost_sobe_vrati_id() {
        session_start();
        if (!isset($_SESSION["entered_room_id"])) {
            header('HTTP/1.1 401 Unauthorized');
            exit();
        }

        header('Content-type:application/json;charset=utf-8');

        $room_id = $_SESSION["entered_room_id"];

        $room = new Room;
        $room->fetch($room_id);

        if ($room->active) {
            return $room_id;
        } else {
            $errors = include(__DIR__ . '/../config/errors.php');

            echo json_encode(array(
                "success" => false,
                "error" => $errors->soba_nije_aktivna,
            ));
            exit();
        }
    }

    /*
    koristi se kod php apija sa strane profesora.
    */
    static function provjeri_prava_profesora_vrati_id() {
        session_start();
        if (!isset($_SESSION["my_id"])) {
            header('HTTP/1.1 401 Unauthorized');
            exit();
        }

        $teacher_id = $_SESSION["my_id"];
        return $teacher_id;
    }

    /*
    koristi se kod long pollinga sa strane profesora.
    */
    static function provjeri_prava_profesora_vrati_id_sobe() {
        session_start();
        if (!isset($_SESSION["room_id"])) {
            header('HTTP/1.1 401 Unauthorized');
            exit();
        }

        $room_id = $_SESSION["room_id"];
        // Close the session prematurely to avoid usleep() from locking other requests
        session_write_close();
        return $room_id;
    }

}
