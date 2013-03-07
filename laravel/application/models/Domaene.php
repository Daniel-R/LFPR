<?php
/**
 * Erstellung:              05.06.12
 * Autor:                   Daniel Reichelt, mydata GmbH
 * Beschreibung:            Objekt einer Webseiten Domaene
 */

class Domaene {
    private $id = 0;
    private $name = "";
    private $url = "";

    /**
     * @param int $id
     * @param string $name
     * @param string $url
     */
    public function __construct($id = null, $name, $url) {
        if (is_null($id)) {
            $this->id = $id;
        }
        else {
            $this->id = (int)$id;
        }
        $this->name = (string)$name;
        $this->url  = (string)$url;
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
    public function getName() {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * @param int $id
     */
    public function setID($id) {
        $this->id = $id;
    }

    /**
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @param string $url
     */
    public function setURL($url) {
        $this->url = $url;
    }
}

?>