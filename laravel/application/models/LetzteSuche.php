<?php
/**
 * Erstellung:              23.08.12
 * Autor:                   Daniel Reichelt, mydata GmbH
 * Beschreibung:
 */

class LetzteSuche {

    private $domaeneID = 0;
    private $domaeneName = "";
    private $domaeneURL = "";
    private $suchbegriffID = 0;
    private $suchbegriff = "";

    public function __construct($domaeneID, $domaeneName = "", $domaeneURL = "", $suchbegriffID = 0, $suchbegriff = "") {
        $this->domaeneID     = (int)$domaeneID;
        $this->domaeneName   = (string)$domaeneName;
        $this->domaeneURL    = (string)$domaeneURL;
        $this->suchbegriffID = (int)$suchbegriffID;
        $this->suchbegriff   = (string)$suchbegriff;
    }

    public function getDomaeneID() {
        return $this->domaeneID;
    }

    public function getDomaeneName() {
        return $this->domaeneName;
    }

    public function getDomaeneURL() {
        return $this->domaeneURL;
    }

    public function getSuchbegriffID() {
        return $this->suchbegriffID;
    }

    public function getSuchbegriff() {
        return $this->suchbegriff;
    }
}

?>