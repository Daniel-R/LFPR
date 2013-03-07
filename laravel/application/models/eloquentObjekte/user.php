<?php
/**
 * Erstellung:              21.08.12
 * Autor:                   Daniel Reichelt, mydata GmbH
 * Beschreibung:
 */

class User extends Eloquent {

    public static $table = 'user';
    public static $accessible = array('categoryID', 'name', 'email', 'password', 'administrator', 'created_at', 'updated_at');
    public static $timestamps = true;

    public function gruppe() {
        return $this->belongs_to('category');
    }

    public function pageRank() {
        return $this->has_many('PageRank', 'userID');
    }
}

//class Benutzer extends Eloquent {
//
//    public static $table = 'benutzer';
//
//    public $benutzer = '';
//    public $email = '';
//    private $password = '';
//
//    public function __construct() {
//    }
//
//    public function gruppe() {
//        return $this->belongs_to('gruppe');
//    }
//
//    public function pageRank() {
//        return $this->has_many('PageRank', 'benutzerID');
//    }
//}
?>