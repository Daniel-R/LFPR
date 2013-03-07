<?php
/**
 * Erstellung:              02.08.12
 * Autor:                   Daniel Reichelt, mydata GmbH
 * Beschreibung:
 */

require_once('application/models/Suchbegriff.php');

class Resultat {

    private $seite = "";

    private $suchbegriff = "";

    private $seitenzahl = 0;

    /**
     * @param string $seite
     * @param string $suchwort
     * @param int $seitenzahl
     */
    public function __construct($seite, $suchbegriff, $seitenzahl) {
        $this->seite       = (string)$seite;
        $this->suchbegriff = (string)$suchbegriff;
        $this->seitenzahl  = (int)$seitenzahl;
    }

    /**
     * @return string
     */
    public function getSeite() {
        return $this->seite;
    }

    /**
     * @return string
     */
    public function getSuchbegriff() {
        return $this->suchbegriff;
    }

    /**
     * @return int
     */
    public function getSeitenzahl() {
        return $this->seitenzahl;
    }
}

?>