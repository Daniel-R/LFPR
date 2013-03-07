<?php
/**
 * Erstellung:              14.08.12
 * Autor:                   Daniel Reichelt, mydata GmbH
 * Beschreibung:
 */

class Eintrag {

    private $eintrag = "";
    private $position = 0;

    /**
     * @param string $eintrag
     * @param null|int $position
     */
    public function __construct($eintrag, $position = null) {
        $this->eintrag = $eintrag;
        if ($position != null) {
            $this->position = (int)$position;
        }
    }

    public function getEintrag() {
        return $this->eintrag;
    }

    public function getPosition() {
        return $this->position;
    }
}

?>