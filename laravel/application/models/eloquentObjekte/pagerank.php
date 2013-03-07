<?php
/**
 * Erstellung:              29.11.12
 * Autor:                   Daniel Reichelt, mydata GmbH
 * Beschreibung:
 */

class PageRank extends Eloquent {

    public static $table = 'pageRank';
    public static $accessible = array('domainID', 'searchengineID', 'searchengineFoundID', 'searchtermID', 'userID', 'position', 'foundURL', 'ammountResults', 'resultDepth', 'date');

    public function domain() {
        return $this->belongs_to('Domain');
    }

    public function searchengine() {
        return $this->belongs_to('Searchengine');
    }

    public function searchengineFound() {
        return $this->belongs_to('Searchengine');
    }

    public function searchterm() {
        return $this->belongs_to('Searchterm');
    }

    public function user() {
        return $this->belongs_to('User');
    }
}
?>f