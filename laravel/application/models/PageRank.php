<?php
/**
 * Erstellung:              05.06.12
 * Autor:                   Daniel Reichelt, mydata GmbH
 * Beschreibung:
 */

require_once("application/models/Suchbegriff.php");
require_once("application/models/Domaene.php");

class PageRank {
    private $id = 0;
    private $domaene = "";
    private $suchmaschineID = 0;
    private $suchmaschineGefundenID = 0;
    private $suchbegriff = "";
    private $benutzerID = 0;
    private $position = 0;
    private $gefundeneURL = "";
    private $anzahlErgebnisse = 0;
    private $datum = "";

    /**
     * @param null/int $_id null möglich
     * @param Domaene $_domaene
     * @param int $_suchmaschineID
     * @param int $_suchmaschineGefundenID
     * @param Suchbegriff $_suchbegriff
     * @param int $_benutzerID
     * @param int $_position
     * @param string $_gefundeneURL
     * @param int $_anzahlErgebnisse
     * @param null/int $datum2
     */
    public function __construct($_id = null, Domaene $_domaene, $_suchmaschineID, $_suchmaschineGefundenID, Suchbegriff $_suchbegriff, $_benutzerID, $_position, $_gefundeneURL, $_anzahlErgebnisse, $_datum = null) {
        $this->id                     = $_id;
        $this->domaene                = $_domaene;
        $this->suchmaschineID         = $_suchmaschineID;
        $this->suchmaschineGefundenID = $_suchmaschineGefundenID;
        $this->suchbegriff            = $_suchbegriff;
        $this->benutzerID             = $_benutzerID;
        $this->position               = $_position;
        $this->gefundeneURL           = $_gefundeneURL;
        $this->anzahlErgebnisse       = $_anzahlErgebnisse;

        if (is_null($_datum)) {
            $neuDatum    = new DateTime();
            $this->datum = $neuDatum->format('Y-m-d H');
        }
        else {
            $neuDatum    = new DateTime($_datum);
            $this->datum = $neuDatum->format('Y-m-d H');
        }
    }

    /**
     * @return int
     */
    public function getID() {
        return $this->id;
    }

    /**
     * @return Domaene
     */
    public function getDomaene() {
        return $this->domaene;
    }

    /**
     * @return int
     */
    public function getSuchmaschineID() {
        return $this->suchmaschineID;
    }

    /**
     * @return int
     */
    public function getSuchmaschineGefundenID() {
        return $this->suchmaschineGefundenID;
    }

    /**
     * @return Suchbegriff
     */
    public function getSuchbegriff() {
        return $this->suchbegriff;
    }

    /**
     * @return int
     */
    public function getBenutzerID() {
        return $this->benutzerID;
    }

    /**
     * @return int
     */
    public function  getPosition() {
        return $this->position;
    }

    /**
     * @return string
     */
    public function getGefundeneURL() {
        return $this->gefundeneURL;
    }

    /**
     * @return int
     */
    public function getAnzahlErgebnisse() {
        return $this->anzahlErgebnisse;
    }

    /**
     * @return string
     */
    public function getDatum() {
        return $this->datum;
    }

    /**
     * @param int $_id
     */
    public function setID($_id) {
        $this->id = (int)$_id;
    }

    /**
     * @param Domaene $_domaene
     */
    public function setDomaene($_domaene) {
        $this->domaene = $_domaene;
    }

    /**
     * @param int $_suchmaschineID
     */
    public function setSuchmaschineID($_suchmaschineID) {
        $this->suchmaschineID = $_suchmaschineID;
    }

    /**
     * @param int $_suchmaschineGefundenID
     */
    public function setSuchmaschineGefundenID($_suchmaschineGefundenID) {
        $this->suchmaschineGefundenID = $_suchmaschineGefundenID;
    }

    /**
     * @param Suchbegriff $_suchbegriff
     */
    public function setSuchbegriff($_suchbegriff) {
        $this->suchbegriff = $_suchbegriff;
    }

    /**
     * @param int $_benutzerID
     */
    public function setBenutzerID($_benutzerID) {
        $this->benutzerID = $_benutzerID;
    }

    /**
     * @param int $_position
     */
    public function setPosition($_position) {
        $this->position = $_position;
    }

    /**
     * @param string $_gefundeneURL
     */
    public function setGefundeneURL($_gefundeneURL) {
        $this->gefundeneURL = $_gefundeneURL;
    }

    /**
     * @param int $_anzahlErgebnisse
     */
    public function setAnzahlErgebnisse($_anzahlErgebnisse) {
        $this->anzahlErgebnisse = $_anzahlErgebnisse;
    }

    /**
     * @param string $_datum
     */
    public function setDatum($_datum) {
        $this->datum = $_datum;
    }
}

?>