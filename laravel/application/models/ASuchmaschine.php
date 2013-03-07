<?php
/**
 * Erstellung:              05.06.12
 * Autor:                   Daniel Reichelt, mydata GmbH
 * Beschreibung:            abstraktes Model der Suchmaschine
 */

abstract class ASuchmaschine {

    /**
     * Auslöser für alles weitere, hier beginnt das durchgehen der Suchmaschine
     * geordnet nach Suchbegriffen und Domaenen solange bis die zu pruefende Tiefe erreicht wurde
     * -> hier sind alle prüfungen drin und weitergaben an die einzelnen methoden
     * @abstract
     * @param array $suchbegriffe array(Suchbegriff)
     * @param array $domaenen array(Domaene)
     * @param int $pruefTiefe
     * @param int $benutzerID
     * @return array array(pageRanksA)
     */
    abstract public function holePageRanks(array $suchbegriffe, array $domaenen, $pruefTiefe, $benutzerID);
}

?>