<?php
/**
 * Erstellung:              29.11.12
 * Autor:                   Daniel Reichelt, mydata GmbH
 * Beschreibung:
 */

class Searchterm extends Eloquent {

    public static $table = 'searchterm';
    public static $accessible = array('searchterm');
    public static $timestamps = false;

    public function pageRank() {
        return $this->has_many('PageRank', 'searchtermID');
    }
}

?>