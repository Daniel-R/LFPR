<?php
/**
 * Erstellung:              29.11.12
 * Autor:                   Daniel Reichelt, mydata GmbH
 * Beschreibung:
 */

//require_once('laravel/auth.drivers/eloquent.php');
//require_once('eloquent.php');

class Domain extends Eloquent {

    public static $table = 'domain';
    public static $accessible = array('name', 'url');
    public static $timestamps = false;


    public function pageRank() {
        return $this->has_many('PageRank', 'domainID');
    }
}
?>