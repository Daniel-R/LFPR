<?php
/**
 * Erstellung:              05.06.12
 * Autor:                   Daniel Reichelt, mydata GmbH
 * Beschreibung:            Objekt eines Suchbegriffes
 */

class Suchbegriff {
    private $id = 0;
    private $suchbegriff = "";

    /**
     * @param int $id
     * @param string $suchbegriff
     */
    public function __construct($id = null, $suchbegriff) {
        if (is_null($id)) {
            $this->id = $id;
        }
        else {
            $this->id = (int)$id;
        }
        $this->suchbegriff = $suchbegriff;
    }

    /**
     * @return int
     */
    public function getID() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSuchbegriff() {
        return $this->suchbegriff;
    }

    public function setID($id) {
        $this->id = $id;
    }

    public function setSuchbegriff($suchbegriff) {
        $this->suchbegriff = $suchbegriff;
    }
}

?>