<?php
/**
 * Erstellung:              17.10.12
 * Autor:                   Daniel Reichelt, mydata GmbH
 * Beschreibung:
 */
require_once("application/models/Verbindung.php");
require_once("application/models/Domaene.php");
require_once("application/models/Suchbegriff.php");
require_once("application/models/PageRank.php");
require_once("application/models/dao/SuchmaschineDAO.php");
require_once("application/models/dao/SuchbegriffDAO.php");
require_once("application/models/dao/DomaeneDAO.php");
require_once("application/models/dao/PageRankDAO.php");

class SuchmaschinenSteuerung {
    private $pageRanks = array();

    public function __construct() {
        Verbindung::initMySQLi("localhost", "root", "pktpktpkt", "LFPR");
    }

    public function getPageRanks() {
        return $this->pageRanks;
    }

    public function setPageRanks($pageRanks) {
        $this->pageRanks = $pageRanks;
    }

    /**
     * @param array $domaenen array(Domaenen)
     * @param array $suchbegriffe array(Suchbegriff)
     * @param array $suchmaschinenNamen array(int)
     * @param int $pruefTiefe
     * @param int $benutzerID
     */
    public function neueSuche(array $domaenen, array $suchbegriffe, array $suchmaschinenNamen, $pruefTiefe, $benutzerID) {
        $suchmaschinenPageRanks = array();
        $suchmaschineDAO        = new SuchmaschineDAO();
        $suchmaschinenPageRanks = array();

        foreach ($suchmaschinenNamen as $suchmaschinenName) {

            /*@var $parser Suchmaschine*/
            $suchmaschine = $suchmaschineDAO->fetchSuchmaschineByID($suchmaschinenName);
            $suchmaschine->holePageRanks($suchbegriffe, $domaenen, $pruefTiefe, $benutzerID);
            $suchmaschinenPageRanks[] = $suchmaschine->getPageRanks(); //immer nur neuster pagerank, das noch bissl stoerend, zerstoert alle bis auf letzte suchmaschine
        }

        $this->speichereNeueSuchbegriffe($suchbegriffe);
        $this->speichereNeueDomaenen($domaenen);

        foreach ($suchmaschinenPageRanks as &$pageRanks) {
            $this->speicherePageRanks($pageRanks);
        }

        $this->setPageRanks($suchmaschinenPageRanks);
    }

    /**
     * @param array $suchbegriffe array(Suchbegriff)
     */
    public function speichereNeueSuchbegriffe($suchbegriffe) {
        $suchbegriffDAO  = new SuchbegriffDAO();
        $suchbegriffeNeu = array();

        /**@var $suchbegriffAlt Suchbegriff*/
        foreach ($suchbegriffe as $suchbegriffAlt) {
            if (!$suchbegriffDAO->fetchIsSuchbegriffRegisteriert($suchbegriffAlt)) {
                $suchbegriffeNeu[] = $suchbegriffAlt;
            }
        }

        if (!empty($suchbegriffeNeu)) {
            $suchbegriffDAO->speichereSuchbegriffe($suchbegriffeNeu);
        }
    }

    /**
     * @param array $domaenen array(Domaene)
     */
    private function speichereNeueDomaenen($domaenen) {
        $domaeneDAO  = new DomaeneDAO();
        $domaenenNeu = array();

        /**@var $domaeneAlt Domaene*/
        foreach ($domaenen as $domaeneAlt) {
            if (!$domaeneDAO->fetchIsDomaeneRegisteriert($domaeneAlt)) {
                $domaenenNeu[] = $domaeneAlt;
            }
        }

        if (!empty($domaenenNeu)) {
            $domaeneDAO->speichereDomaenen($domaenenNeu);
        }
    }

    /**
     * @param array $pageRanks array(PageRank)
     */
    private function speicherePageRanks(&$pageRanks) {
        $pageRankDAO = new PageRankDAO();

        /**@var $pageRank PageRank*/
        foreach ($pageRanks as &$pageRank) {
            if (!$pageRankDAO->fetchIsPageRankRegistriert($pageRank)) {
                $domaeneDAO = new DomaeneDAO();
                $domaeneDAO->setzeDomaeneIDByURL($pageRank->getDomaene());
                $suchbegriffDAO = new SuchbegriffDAO();
                $suchbegriffDAO->setzeSuchbegriffIDByName($pageRank->getSuchbegriff());
                $pageRankDAO->speicherePageRanks($pageRanks);
                $pageRankDAO->setzePageRankIDByValues($pageRank);
            }
        }
    }
}

?>