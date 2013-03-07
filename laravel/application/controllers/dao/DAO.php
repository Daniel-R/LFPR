<?php
/**
 * Erstellung:              14.06.12
 * Autor:                   Daniel Reichelt, mydata GmbH
 * Beschreibung:
 */

class DAO {
    private $mysqli = null;

    /**
     * Die Klasse Verbindung ist eine singleton class in der die Datenbank verbunden wird und aus der sie entnommen werden kann auf globaler Ebene
     */
    public function __construct() {
        $this->mysqli = Verbindung::getMySQLi();
    }

    /**
     * @return mysqli
     */
    protected function getMySQLi() {
        return $this->mysqli;
    }
}

?>