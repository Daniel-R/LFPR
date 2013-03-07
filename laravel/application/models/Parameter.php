<?php
/**
 * Erstellung:              05.06.12
 * Autor:                   Daniel Reichelt, mydata GmbH
 * Beschreibung:
 */

class Parameter {
    private $id = 0;
    private $bezeichnung = "";
    private $wert = "";

    /**
     * @param int $id
     * @param string $bezeichnung
     * @param string $wert
     */
    public function __construct($id = null, $bezeichnung, $wert = null) {
        if (is_null($id)) {
            $this->id = $id;
        }
        else {
            $this->id = (int)$id;
        }
        $this->bezeichnung = (string)$bezeichnung;

        if (is_null($id)) {
            $this->wert = $wert;
        }
        else {
            $this->wert = (string)$wert;
        }
    }

    /**
     * @return int
     */
    public function  getID() {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getBezeichnung() {
        return $this->bezeichnung;
    }

    /**
     * @return string
     */
    public function getWert() {
        return $this->wert;
    }
}

?>