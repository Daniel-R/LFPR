<?php
/**
 * Erstellung:              21.06.12
 * Autor:                   Daniel Reichelt, mydata GmbH
 * Beschreibung:
 */

require_once("application/models/dao/DAO.php");
require_once("application/models/Verbindung.php");
require_once("application/models/PageRank.php");


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
        $pageRankQuery = parent::getMySQLi()->query("SELECT pr.id, pr.domainID, dm.name, dm.url, pr.searchengineID, pr.searchengineFoundID, pr.searchtermID, su.seachterm, pr.userID, pr.position, pr.foundURL, pr.ammountResults, pr.date
                                                     FROM pageRank pr
                                                     INNER JOIN domain dm ON pr.domainID = dm.id
                                                     INNER JOIN searchterm su ON pr.searchtermID = su.id
                                                     WHERE pr.id IN ($pageRankIDs)
                                                     ORDER BY pr.date asc;");
        $pageRanks     = array();
        while ($pageRankResultat = $pageRankQuery->fetch_assoc()) {
            $pageRanks[] = new PageRank($pageRankResultat["id"], new Domaene($pageRankResultat["domainID"], $pageRankResultat["name"], $pageRankResultat["url"]), $pageRankResultat["searchengineID"], $pageRankResultat["searchengineFoundID"], new Suchbegriff($pageRankResultat["searchtermID"], $pageRankResultat["searchterm"]), $pageRankResultat["position"], $pageRankResultat["foundURL"], $pageRankResultat["ammountResults"], $pageRankResultat["date"]);
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
        $pageRankQuery = $mysqli->prepare("SELECT pr.id, pr.domainID, dm.name, dm.url, pr.searchengineID, pr.searchengineFoundID, pr.searchtermID, su.searchterm, pr.userID, pr.position, pr.foundURL, pr.ammountResults, pr.date
                                           FROM pageRank pr
                                           INNER JOIN domain dm ON pr.domainID = dm.id
                                           INNER JOIN searchterm su ON pr.searchtermID = su.id
                                           WHERE pr.userID IN (?)
                                           ORDER BY pr.date asc");
        $pageRankQuery->bind_param("i", $id);
        $pageRankQuery->execute();
        $id                  = 0;
        $domainID            = 0;
        $domainName          = "";
        $domainURL           = "";
        $searchengineID      = 0;
        $searchengineFoundID = 0;
        $searchtermID        = 0;
        $searchtermName      = "";
        $userID              = 0;
        $position            = 0;
        $foundURL            = "";
        $ammountResults      = 0;
        $date                = "";
        $pageRankQuery->bind_result($id, $domainID, $domainName, $domainURL, $searchengineID,
                                    $searchengineFoundID, $searchtermID, $searchtermName, $userID, $position,
                                    $foundURL, $ammountResults, $date);
        $pageRanks = array();

        while ($pageRankResultat = $pageRankQuery->fetch()) {
            $pageRanks[] = new PageRank($id, new Domaene($domainID, $domainName, $domainURL), $searchengineID, $searchengineFoundID, new Suchbegriff($searchtermID, $searchtermName), $userID, $position, $foundURL, $ammountResults, $date);
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
        $pageRankQuery = $mysqli->prepare("SELECT pr.id, dm.id, dm.name, dm.url, pr.searchengineID, pr.searchengineFoundID, su.id, su.searchterm, pr.userID, pr.position, pr.foundURL, pr.ammountResults, pr.date
                                                       FROM pageRank pr
                                                       INNER JOIN domain dm ON pr.domainID = dm.id
                                                       INNER JOIN searchterm su ON pr.searchtermID = su.id
                                                       WHERE pr.userID = ?
                                                       ORDER BY pr.id DESC
                                                       LIMIT 1");
        $pageRankQuery->bind_param("i", $id);
        $pageRankQuery->execute();
        $id                  = 0;
        $domainID            = 0;
        $domainName          = "";
        $domainURL           = "";
        $searchengineID      = 0;
        $searchengineFoundID = 0;
        $searchtermID        = 0;
        $searchtermName      = "";
        $userID              = 0;
        $position            = 0;
        $foundURL            = "";
        $ammountResults      = 0;
        $date                = "";
        $pageRankQuery->bind_result($id, $domainID, $domainName, $domainURL, $searchengineID,
                                    $searchengineFoundID, $searchtermID, $searchtermName, $userID, $position,
                                    $foundURL, $ammountResults, $date);
        $pageRanks = array();

        while ($pageRankResultat = $pageRankQuery->fetch()) {
            $pageRanks[] = new PageRank($id, new Domaene($domainID, $domainName, $domainURL), $searchengineID, $searchengineFoundID, new Suchbegriff($searchtermID, $searchtermName), $uzserID, $position, $foundURL, $ammountResults, $date);
        }

        return $pageRanks;
    }

    /**
     * Setzt die ID eines PageRanks anhand von Domaene, Suchbegriff, Position und Datum.
     * @param PageRank $pageRank
     * @return array array(string)
     */
    public function setzePageRankIDByValues(&$pageRank) {
        $mysqli        = parent::getMySQLi();
        $domain        = $pageRank->getDomaene();
        $searchterm    = $pageRank->getSuchbegriff();
        $pageRankQuery = $mysqli->prepare("SELECT pr.id
                                           FROM pageRank pr
                                           WHERE pr.domainID = ? && pr.searchtermID = ? && pr.position = ? && pr.date = ?
                                           ORDER BY id asc
                                           LIMIT 1");
        $pageRankQuery->bind_param("iiis", $domain->getID(), $searchterm->getID(), $pageRank->getPosition(),
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
                                           WHERE pr.domainID = ? && pr.searchtermID = ? && pr.position = ? && pr.date = ?
                                           ORDER BY id asc
                                           LIMIT 1");
        $domain        = $pageRank->getDomaene();
        $searchterm    = $pageRank->getSuchbegriff();
        $pageRankQuery->bind_param("ssss", $domain->getID(), $searchterm->getID(), $pageRank->getPosition(),
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
        $pageRankQuery = $mysqli->prepare("INSERT INTO pageRank(domainID, searchengineID, searchengineFoundID,
                                                                searchtermID, userID, position, foundURL,
                                                                ammountResults, date)
                                          VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $isGespeichert = array();

        /**@var $pageRank PageRank*/
        foreach ($pageRanks as $pageRank) {
            $domain              = $pageRank->getDomaene();
            $domainID            = $domain->getID();
            $searchengineID      = $pageRank->getSuchmaschineID();
            $searchengineFoundID = $pageRank->getSuchmaschineGefundenID();
            $searchterm          = $pageRank->getSuchbegriff();
            $searchtermID        = $searchterm->getID();
            $userID              = $pageRank->getBenutzerID();
            $position            = $pageRank->getPosition();
            $foundURL            = $pageRank->getGefundeneURL();
            $ammountResults      = $pageRank->getAnzahlErgebnisse();
            $date                = $pageRank->getDatum();
            $pageRankQuery->bind_param("iiiiiisis", $domainID, $searchengineID, $searchengineID,
                                       $searchtermID, $userID, $position, $foundURL, $ammountResults,
                                       $date);
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
                                           SET domainID = ?, searchengineID = ?, searchengineFoundID = ?, searchntermID = ?, userID = ?, position = ?, foundURL = ?, ammountResults = ?, date = ?
                                           WHERE id = ?");
        $isGespeichert = array();

        /**@var $pageRank PageRank*/
        foreach ($pageRanks as $pageRank) {
            $domain              = $pageRank->getDomaene();
            $domainID            = $domaene->getID();
            $searchengineID      = $pageRank->getSuchmaschineID();
            $searchengineFoundID = $pageRank->getSuchmaschineGefundenID();
            $searchterm          = $pageRank->getSuchbegriff();
            $searchtermID        = $suchbegriff->getID();
            $userID              = $pageRank->getBenutzerID();
            $position            = $pageRank->getPosition();
            $foundURL            = $pageRank->getGefundeneURL();
            $ammountResults      = $pageRank->getAnzahlErgebnisse();
            $date                = $pageRank->getDatum();
            $id                  = $pageRank->getID();
            $pageRankQuery->bind_param("iiiiiisisi", $domainID, $searchengineID, $searchengineFoundID,
                                       $searchtermID, $userID, $position, $foundURL, $ammountResults, $date,
                                       $id);
            $isGespeichert[] = $pageRankQuery->execute();
        }
        $pageRankQuery->close();
        return $isGespeichert;
    }
}

?>