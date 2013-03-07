<?php
/**
 * Erstellung:              06.06.12
 * Autor:                   Daniel Reichelt, mydata GmbH
 * Beschreibung:
 */
require_once("application/models/ASuchmaschine.php");
require_once("application/models/ISuchmaschine.php");
require_once("application/models/Parameter.php");
require_once("application/models/Suchbegriff.php");
require_once("application/models/Domaene.php");
require_once("application/models/PageRank.php");
require_once("application/models/GoogleDEParser.php");
require_once("application/models//dao/PageRankDAO.php");
require_once("application/models//dao/SuchmaschineDAO.php");

class Suchmaschine extends ASuchmaschine implements ISuchmaschine {

    private $id = 0;
    private $name = "";
    private $url = "";
    private $positionenJeSeite = 0;
    private $parameter = array();
    private $pageRanks = array();

    /**
     * @param null|int $id
     * @param string $name
     * @param string $url
     * @param int $positionenJeSeite
     * @param null|array $parameter null|array(Parameter)
     */
    public function __construct($id = null, $name, $url, $positionenJeSeite, array $parameter = null) {
        if (is_null($id)) {
            $this->id = $id;
        }
        else {
            $this->id = (int)$id;
        }

        $this->name              = (string)$name;
        $this->url               = (string)$url;
        $this->positionenJeSeite = (int)$positionenJeSeite;

        if ($parameter !== null) {
            $this->parameter = $parameter;
        }
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
     * @return int
     */
    public function getPositionenJeSeite() {
        return $this->positionenJeSeite;
    }

    /**
     * @return array array(Parameter)
     */
    public function getParameter() {
        return $this->parameter;
    }

    /**
     * @return array(PageRank)
     */
    public function getPageRanks() {
        return $this->pageRanks;
    }

    /**
     * Auslöser für alles weitere.
     * Die Suchmaschine wird geordnet nach Suchbegriffen und Domaenen solange bis die zu pruefende Tiefe erreicht wurde
     * Alle Prüfungen und weitergaben an die einzelnen Methoden sollen von hier aus erfolgen ohne weitere Auslöser
     * @param array $suchbegriffe array(Suchbegriff)
     * @param array $domaenen array(Domaene)
     * @param int $pruefTiefe
     * @param int $benutzerID
     * @return array array(PageRank)
     */
    public function holePageRanks(array $suchbegriffe, array $domaenen, $pruefTiefe, $benutzerID) {
        $pageRankArray = array();
        $parser        = "";

        require_once("ParserID.php");
        if ($this->id == ParserID::GOOGLEDE) {
            $parser = new GoogleDEParser($this->id, $this->name, $this->url, $this->positionenJeSeite, $this->parameter);
        }
        else if ($this->id == ParserID::GOOGLECOM) {
            $parser = new GoogleCOMParser($this->id, $this->name, $this->url, $this->positionenJeSeite, $this->parameter);
        }
        else if ($this->id == ParserID::YAHOODE) {
            $parser = new YahooDEParser($this->id, $this->name, $this->url, $this->positionenJeSeite, $this->parameter);
        }
        else if ($this->id == ParserID::YAHOOCOM) {
            $parser = new YahooCOMParser($this->id, $this->name, $this->url, $this->positionenJeSeite, $this->parameter);
        }
        else if ($this->id == ParserID::BINGDE) {
            $parser = new BingDEParser($this->id, $this->name, $this->url, $this->positionenJeSeite, $this->parameter);
        }
        else if ($this->id == ParserID::DUCKDUCKGO) {
            $parser = new DuckDuckGoCOMParser($this->id, $this->name, $this->url, $this->positionenJeSeite, $this->parameter);
        }

        $resultate     = $parser->holeAlleResultate($suchbegriffe, $pruefTiefe);
        $pageRankArray = $parser->holePageRanks($suchbegriffe, $domaenen, $resultate, $benutzerID);
//        $pageRankDAO   = new PageRankDAO();
//        $pageRankDAO->savePageRanks($pageRankArray);
        $this->pageRanks = $pageRankArray;
    }
}

?>