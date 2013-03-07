<?php
/**
 * Erstellung:              06.06.12
 * Autor:                   Daniel Reichelt, mydata GmbH
 * Beschreibung:
 */

/**
 * @param $class
 */
include_once("application/models//dao/SuchbegriffDAO.php");
include_once("application/models//dao/DomaeneDAO.php");
include_once("application/models//dao/SuchmaschineDAO.php");
include_once("application/models//dao/PageRankDAO.php");

function __autoload($class) {
    if (preg_match("/^[A-Z]([a-zA-Z0-9_]+)$/", $class)) {
        require_once "" . $class . ".php";
    }
}

class Suche {
    public function __construct() {}

    /**
     * setzt die Suche um anhand der übergebennen Werte
     * @param array $suchbegriffIDs array(int)
     * @param array $domaenenIDs array(int)
     * @param int $suchmaschineID
     * @param int $pruefTiefe
     */
    public function starteSuche(array $suchbegriffIDs, array $domaenenIDs, $suchmaschineID, $pruefTiefe) {
        Verbindung::initMySQLi("localhost", "root", "pktpktpkt", "LFPR");

        $suchbegriffDAO = new SuchbegriffDAO();
        $suchbegriffe = $suchbegriffDAO->fetchSuchbegriffeByIDs($suchbegriffIDs);

        $domaeneDAO = new DomaeneDAO();
        $domaenen = $domaeneDAO->fetchDomaenenByIDs($domaenenIDs);

        $suchmaschineDAO = new SuchmaschineDAO(); //Suchmaschine als DAO sollte so auslesen aber es fehlt das Objekt und wie die Daten genutzt werden
        $gewaehlteSuchmaschine = $suchmaschineDAO->fetchSuchmaschineByID($suchmaschineID);

        $pageRankDAO = new PageRankDAO;
        $pageRanks = $gewaehlteSuchmaschine->holePageRanks($suchbegriffe, $domaenen, $pruefTiefe);

        $pageRankDAO->speicherePageRanks($pageRanks);
        Verbindung::schliesseMySQLi();
    }
}

?>