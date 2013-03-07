<?php
/**
 * Erstellung:              21.06.12
 * Autor:                   Daniel Reichelt, mydata GmbH
 * Beschreibung:
 */

require_once("DAO.php");
require_once("../class/Verbindung.php");
require_once("../class/PageRank.php");


class PageRankDAO extends DAO {
    public function __construct() {
        parent::__construct();
    }

    /**
     * Holt die Angaben einer mehrerer PageRanks aus der DB
     * @param array $ids array(int)
     * @return array array(PageRank)
     */
    public function fetchPageRankByIDs(array $ids) {
        $mysqli  = parent::getMySQLi();
        $idArray = array();

        foreach ($ids as $id) {
            $idArray[] = $mysqli->real_escape_string($id);
        }

        $pageRankIDs   = "'" . implode("', '", $idArray) . "'";
        $pageRankQuery = parent::getMySQLi()->query("SELECT pr.id, pr.domaeneID, dm.name, dm.url, pr.suchmaschineID, pr.suchmaschineGefundenID, pr.suchbegriffID, su.suchbegriff, pr.benutzerID, pr.position, pr.gefundeneURL, pr.anzahlErgebnisse, pr.datum
                                                     FROM pageRank pr
                                                     INNER JOIN domaene dm ON pr.domaeneID = dm.id
                                                     INNER JOIN suchbegriff su ON pr.suchbegriffID = su.id
                                                     WHERE pr.id IN ($pageRankIDs)
                                                     ORDER BY pr.datum asc;");
        $pageRanks     = array();
        while ($pageRankResultat = $pageRankQuery->fetch_assoc()) {
            $pageRanks[] = new PageRank($pageRankResultat["id"], new Domaene($pageRankResultat["domaeneID"], $pageRankResultat["name"], $pageRankResultat["url"]), $pageRankResultat["suchmaschineID"], $pageRankResultat["suchmaschineGefundenID"], new Suchbegriff($pageRankResultat["suchbegriffID"], $pageRankResultat["suchbegriff"]), $pageRankResultat["position"], $pageRankResultat["gefundeneURL"], $pageRankResultat["anzahlErgebnisse"], $pageRankResultat["datum"]);
        }

        return $pageRanks;
    }

    /**
     * Holt die Angaben mehrerer PageRanks aus der DB
     * @param int $id
     * @return array array(PageRank)
     */
    public function fetchPageRankByBenutzerID($id) {
        $mysqli        = parent::getMySQLi();
        $pageRankQuery = $mysqli->prepare("SELECT pr.id, pr.domaeneID, dm.name, dm.url, pr.suchmaschineID, pr.suchmaschineGefundenID, pr.suchbegriffID, su.suchbegriff, pr.benutzerID, pr.position, pr.gefundeneURL, pr.anzahlErgebnisse, pr.datum
                                           FROM pageRank pr
                                           INNER JOIN domaene dm ON pr.domaeneID = dm.id
                                           INNER JOIN suchbegriff su ON pr.suchbegriffID = su.id
                                           WHERE pr.benutzerID IN (?)
                                           ORDER BY pr.datum asc");
        $pageRankQuery->bind_param("i", $id);
        $pageRankQuery->execute();
        $id                     = 0;
        $domaeneID              = 0;
        $domaeneName            = "";
        $domaeneURL             = "";
        $suchmaschineID         = 0;
        $suchmaschineGefundenID = 0;
        $suchbegriffID          = 0;
        $suchbegriffName        = "";
        $benutzerID             = 0;
        $position               = 0;
        $gefundeneURL           = "";
        $anzahlErgebnisse       = 0;
        $datum                  = "";
        $pageRankQuery->bind_result($id, $domaeneID, $domaeneName, $domaeneURL, $suchmaschineID,
                                    $suchmaschineGefundenID, $suchbegriffID, $suchbegriffName, $benutzerID, $position,
                                    $gefundeneURL, $anzahlErgebnisse, $datum);
        $pageRanks = array();

        while ($pageRankResultat = $pageRankQuery->fetch()) {
            $pageRanks[] = new PageRank($id, new Domaene($domaeneID, $domaeneName, $domaeneURL), $suchmaschineID, $suchmaschineGefundenID, new Suchbegriff($suchbegriffID, $suchbegriffName), $benutzerID, $position, $gefundeneURL, $anzahlErgebnisse, $datum);
        }
        return $pageRanks;
    }

    /**
     * @param int $id
     * @return array
     */
    public function fetchLetzteSucheByBenutzerID($id) { //derzeit tut es nichts, an sich sollte es halt alle neusten eintraege holen so letzten x sekunden
        $mysqli = parent::getMySQLi();
        //TODO: Selbe Abfrage wie sie in BenutzerDAO vorkommt
        $pageRankQuery = $mysqli->prepare("SELECT pr.id, dm.id, dm.name, dm.url, pr.suchmaschineID, pr.suchmaschineGefundenID, su.id, su.suchbegriff, pr.benutzerID, pr.position, pr.gefundeneURL, pr.anzahlErgebnisse, pr.datum
                                                       FROM pageRank pr
                                                       INNER JOIN domaene dm ON pr.domaeneID = dm.id
                                                       INNER JOIN suchbegriff su ON pr.suchbegriffID = su.id
                                                       WHERE pr.benutzerID = ?
                                                       ORDER BY pr.id DESC
                                                       LIMIT 1");
        $pageRankQuery->bind_param("i", $id);
        $pageRankQuery->execute();
        $id                     = 0;
        $domaeneID              = 0;
        $domaeneName            = "";
        $domaeneURL             = "";
        $suchmaschineID         = 0;
        $suchmaschineGefundenID = 0;
        $suchbegriffID          = 0;
        $suchbegriffName        = "";
        $benutzerID             = 0;
        $position               = 0;
        $gefundeneURL           = "";
        $anzahlErgebnisse       = 0;
        $datum                  = "";
        $pageRankQuery->bind_result($id, $domaeneID, $domaeneName, $domaeneURL, $suchmaschineID,
                                    $suchmaschineGefundenID, $suchbegriffID, $suchbegriffName, $benutzerID, $position,
                                    $gefundeneURL, $anzahlErgebnisse, $datum);
        $pageRanks = array();

        while ($pageRankResultat = $pageRankQuery->fetch()) {
            $pageRanks[] = new PageRank($id, new Domaene($domaeneID, $domaeneName, $domaeneURL), $suchmaschineID, $suchmaschineGefundenID, new Suchbegriff($suchbegriffID, $suchbegriffName), $benutzerID, $position, $gefundeneURL, $anzahlErgebnisse, $datum);
        }

        return $pageRanks;
    }

    /**
     * Setzt die ID eines PageRanks anhand von Domaene, Suchbegriff, Position und Datum.
     * @param PageRank $pageRank
     * @return array array(string)
     */
    public function setzePageRankIDByValues(&$pageRank) {
        $mysqli      = parent::getMySQLi();
        $domaene     = $pageRank->getDomaene();
        $suchbegriff = $pageRank->getSuchbegriff();
        $pageRankQuery = $mysqli->prepare("SELECT pr.id
                                           FROM pageRank pr
                                           WHERE pr.domaeneID = ? && pr.suchbegriffID = ? && pr.position = ? && pr.datum = ?
                                           ORDER BY id asc
                                           LIMIT 1");
        $pageRankQuery->bind_param("iiis", $domaene->getID(), $suchbegriff->getID(), $pageRank->getPosition(),
                                   $pageRank->getDatum());
        $pageRankQuery->execute();
        $id = 0;
        $pageRankQuery->bind_result($id);
        $pageRankQuery->fetch();
        $pageRank->setID($id);
    }

    /**
     * @param PageRank $pageRank
     * @return bool
     */
    public function fetchIsPageRankRegistriert($pageRank) {
        $mysqli        = parent::getMySQLi();
        $pageRankQuery = $mysqli->prepare("SELECT pr.id
                                           FROM pageRank pr
                                           WHERE pr.domaeneID = ? && pr.suchbegriffID = ? && pr.position = ? && pr.datum = ?
                                           ORDER BY id asc
                                           LIMIT 1");
        $domaene       = $pageRank->getDomaene();
        $suchbegriff   = $pageRank->getSuchbegriff();
        $pageRankQuery->bind_param("ssss", $domaene->getID(), $suchbegriff->getID(), $pageRank->getPosition(),
                                   $pageRank->getDatum());
        $pageRankQuery->execute();
        $id = 0;
        $pageRankQuery->bind_result($id);
        $pageRankQuery->fetch();
        $pageRank->setID($id);

        if ($pageRank->getID() == 0) {
            return false;
        }

        return true;
    }

    /**
     * Sichern von mehreren PageRanks.
     * @param array $pageRanks array(PageRank)
     * @return array array(bool)
     * @throws Exception
     */
    public function speicherePageRanks(array $pageRanks) {
        $insertArray   = array();
        $updateArray   = array();
        $isGespeichert = array();

        /**@var $pageRank PageRank*/
        foreach ($pageRanks as $pageRank) {
            if ($pageRank->getID() < 1) {
                $insertArray[] = $pageRank;
            }
            else {
                $updateArray[] = $pageRank;
            }
        }
//        echo "insert array:" . var_dump($insertArray) . "<br>";
//        echo "update array:" . var_dump($updateArray) . "<br>";

        try {
            if (!empty($insertArray) && !empty($updateArray)) {
                throw new Exception("Es wurden Datensätze für Insert und Update mit einem Aufruf übergeben\n");
            }
            elseif (!empty($insertArray)) {
                $isGespeichert = $this->insertPageRanks($insertArray);
            }
            else {
                $isGespeichert = $this->updatePageRanks($updateArray);
            }
            return $isGespeichert;
        } catch (Exception $exception) {
            echo "Fehler: " . $exception->getMessage();
        }

        return $isGespeichert;
    }

    /**
     * Zum speichern neuer PageRanks
     * @param array $pageRanks array(PageRank)
     * @return array array(bool)
     */
    public function insertPageRanks(array $pageRanks) {
        $mysqli        = parent::getMySQLi();
        $pageRankQuery = $mysqli->prepare("INSERT INTO pageRank(domaeneID, suchmaschineID, suchmaschineGefundenID,
                                                                suchbegriffID, benutzerID, position, gefundeneURL,
                                                                anzahlErgebnisse, datum)
                                          VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $isGespeichert = array();

        /**@var $pageRank PageRank*/
        foreach ($pageRanks as $pageRank) {
            $domaene                = $pageRank->getDomaene();
            $domaeneID              = $domaene->getID();
            $suchmaschineID         = $pageRank->getSuchmaschineID();
            $suchmaschineGefundenID = $pageRank->getSuchmaschineGefundenID();
            $suchbegriff            = $pageRank->getSuchbegriff();
            $suchbegriffID          = $suchbegriff->getID();
            $besitzerID             = $pageRank->getBenutzerID();
            $position               = $pageRank->getPosition();
            $gefundeneURL           = $pageRank->getGefundeneURL();
            $anzahlErgebnisse       = $pageRank->getAnzahlErgebnisse();
            $datum                  = $pageRank->getDatum();
            $pageRankQuery->bind_param("iiiiiisis", $domaeneID, $suchmaschineID, $suchmaschineGefundenID,
                                       $suchbegriffID, $besitzerID, $position, $gefundeneURL, $anzahlErgebnisse,
                                       $datum);
            $isGespeichert[] = $pageRankQuery->execute();
        }
        $pageRankQuery->close();
        return $isGespeichert;
    }

    /**
     * Zum updaten der PageRanks
     * @param array $pageRanks array(PageRank)
     * @return array array(bool)
     */
    public function updatePageRanks(array $pageRanks) {
        $mysqli        = parent::getMySQLi();
        $pageRankQuery = $mysqli->prepare("UPDATE pageRank
                                           SET domaeneID = ?, suchmaschineID = ?, suchmaschineGefundenID = ?, suchbegriffID = ?, benutzerID = ?, position = ?, gefundeneURL = ?, anzahlErgebnisse = ?, datum = ?
                                           WHERE id = ?");
        $isGespeichert = array();

        /**@var $pageRank PageRank*/
        foreach ($pageRanks as $pageRank) {
            $domaene                = $pageRank->getDomaene();
            $domaeneID              = $domaene->getID();
            $suchmaschineID         = $pageRank->getSuchmaschineID();
            $suchmaschineGefundenID = $pageRank->getSuchmaschineGefundenID();
            $suchbegriff            = $pageRank->getSuchbegriff();
            $suchbegriffID          = $suchbegriff->getID();
            $besitzerID             = $pageRank->getBenutzerID();
            $position               = $pageRank->getPosition();
            $gefundeneURL           = $pageRank->getGefundeneURL();
            $anzahlErgebnisse       = $pageRank->getAnzahlErgebnisse();
            $datum                  = $pageRank->getDatum();
            $id                     = $pageRank->getID();
            $pageRankQuery->bind_param("iiiiiisisi", $domaeneID, $suchmaschineID, $suchmaschineGefundenID,
                                       $suchbegriffID, $besitzerID, $position, $gefundeneURL, $anzahlErgebnisse, $datum,
                                       $id);
            $isGespeichert[] = $pageRankQuery->execute();
        }
        $pageRankQuery->close();
        return $isGespeichert;
    }
}

?>