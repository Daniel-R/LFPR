<?php
/**
 * Erstellung:              29.11.12
 * Autor:                   Daniel Reichelt, mydata GmbH
 * Beschreibung:
 */

require_once('GoogleDEParser.php');

class Searchengine extends Eloquent {


    public static $table = 'searchengine';
    public static $accessible = array('name', 'url', 'positionsEachSite');
    public static $timestamps = false;

//    public static $timestamps = false;

//    public function pageRank() { //TODO: Problem mit dem doppelten Fremdschlüssel der Suchmaschinen ID in PageRank - wird aber hier definiert nehme ich an
//        return $this->has_many('PageRank', 'searchengineID');
//    }

    public function pageRankSearchengine() {
        return $this->has_many('PageRank', 'searchengineID');
    }

    public function pageRankFound() {
        return $this->has_many('PageRank', 'searchengineFoundID');
    }

    public function parameterValue() {
        return $this->has_many_and_belongs_to('Parameter', 'parameterValue', 'searchengineID', 'parameterID');
    }

//    /**
//     * Auslöser für alles weitere.
//     * Die Suchmaschine wird geordnet nach Suchbegriffen und Domaenen solange bis die zu pruefende Tiefe erreicht wurde
//     * Alle Prüfungen und weitergaben an die einzelnen Methoden sollen von hier aus erfolgen ohne weitere Auslöser
//     * @param array $suchbegriffe array(Suchbegriff)
//     * @param array $domaenen array(Domaene)
//     * @param int $pruefTiefe
//     * @param int $benutzerID
//     * @return array array(PageRank)
//     */
//    public function getPageRanks(array $_searchterms, array $domaenen, $pruefTiefe, $benutzerID) {
//        $pageRankArray = array();
//        $parser        = "";
//
//        require_once("ParserID.php");
//        if ($this->id == ParserID::GOOGLEDE) {
//            $parser = new GoogleDEParser($this->id, $this->name, $this->url, $this->positionenJeSeite, $this->parameter);
//        }
//        else if ($this->id == ParserID::GOOGLECOM) {
//            $parser = new GoogleCOMParser($this->id, $this->name, $this->url, $this->positionenJeSeite, $this->parameter);
//        }
//        else if ($this->id == ParserID::YAHOODE) {
//            $parser = new YahooDEParser($this->id, $this->name, $this->url, $this->positionenJeSeite, $this->parameter);
//        }
//        else if ($this->id == ParserID::YAHOOCOM) {
//            $parser = new YahooCOMParser($this->id, $this->name, $this->url, $this->positionenJeSeite, $this->parameter);
//        }
//        else if ($this->id == ParserID::BINGDE) {
//            $parser = new BingDEParser($this->id, $this->name, $this->url, $this->positionenJeSeite, $this->parameter);
//        }
//        else if ($this->id == ParserID::DUCKDUCKGO) {
//            $parser = new DuckDuckGoCOMParser($this->id, $this->name, $this->url, $this->positionenJeSeite, $this->parameter);
//        }
//
//        $resultate     = $parser->holeAlleResultate($_searchterms, $pruefTiefe);
//        $pageRankArray = $parser->holePageRanks($_searchterms, $domaenen, $resultate, $benutzerID);
//        //        var_dump($pageRankArray);
//        //        $pageRankDAO   = new PageRankDAO();
//        //        $pageRankDAO->savePageRanks($pageRankArray);
//        $this->pageRanks = $pageRankArray;
//    }
}

?>