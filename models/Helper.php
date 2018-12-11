<?php
namespace Models;

class Helper {

    /*
    služi za dohvaćanje korisnikovog inputa koji vjerojatno nije siguran
    */
    static function xssafe($data, $encoding='UTF-8') {
       return htmlspecialchars($data, ENT_QUOTES | ENT_HTML401, $encoding);
    }

}
