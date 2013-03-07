<?php
/**
 * Erstellung:              20.06.12
 * Autor:                   Daniel Reichelt, mydata GmbH
 * Beschreibung:
 */

include_once("application/models/dao/DAO.php");
include_once("application/models/Suchbegriff.php");

class SuchbegriffDAO extends DAO {
    public function __construct() {
        parent::__construct();
    }

    /**
     * Holt die Angaben eines oder mehrerer Suchbegriffe aus der DB.
     * @param array $ids array(int)
     * @return array array(Suchbegriff)
     */
    public function fetchSuchbegriffeByIDs(array $ids) {
        $mysqli  = parent::getMySQLi();
        $idArray = array();
        foreach ($ids as $id) {
            $idArray[] = $mysqli->real_escape_string($id);
        }
        $searchtermIDs   = "'" . implode("', '", $idArray) . "'";
        $mysqli          = parent::getMySQLi();
        $searchtermQuery = $mysqli->query("SELECT sb.id, sb.searchterm
                                            FROM searchterm sb
                                            WHERE sb.id IN ($searchtermIDs)
                                            ORDER BY id asc");
        $searchterms     = array();

        while ($searchtermResult = $searchtermQuery->fetch_assoc()) {
            $searchterms[] = new Suchbegriff($searchtermResult["id"], $searchtermResult["searchterm"]);
        }

        return $suchbegriffe;
    }

    /**
     * Holt die Namen eines oder mehrerer Suchbegriffe mit ID als key.
     * @param array $ids array(int)
     * @return array array(string)
     */
    public function fetchSuchbegriffNamenByIDs(array $ids) {
        $mysqli  = parent::getMySQLi();
        $idArray = array();
        foreach ($ids as $id) {
            $idArray[] = $mysqli->real_escape_string($id);
        }
        $searchtermIDs   = "'" . implode("', '", $idArray) . "'";
        $searchtermQuery = $mysqli->query("SELECT sb.id, sb.searchterm
                                            FROM searchterm sb
                                            WHERE sb.id IN ($searchtermIDs)
                                            ORDER BY id asc");
        $searchterms     = array();

        while ($searchtermResult = $searchtermQuery->fetch_assoc()) {
            $searchterms[$searchtermResult["id"]] = $searchtermResult["searchterm"];
        }

        return $searchterms;
    }

    /**
     * Setzt die ID eines Suchbegriffes anhand vom Name.
     * @param Suchbegriff $searchterm
     * @return array array(string)
     */
    public function setzeSuchbegriffIDByName(&$searchterm) {
        $mysqli          = parent::getMySQLi();
        $searchtermQuery = $mysqli->prepare("SELECT sb.id
                                              FROM searchterm sb
                                              WHERE sb.searchterm = ?
                                              ORDER BY id asc
                                              LIMIT 1");
        $suchbegriff = $searchterm->getSuchbegriff();
        $searchtermQuery->bind_param("s", $suchbegriff);
        $searchtermQuery->execute();
        $id = 0;
        $searchtermQuery->bind_result($id);
        $searchtermQuery->fetch();
        $searchterm->setID($id);
    }

    /**
     * Holt die Namen eines oder mehrerer Suchbegriffe mit ID als key.
     * @param Suchbegriff $searchterm
     * @return bool
     */
    public function fetchIsSuchbegriffRegisteriert($searchterm) {
        $mysqli          = parent::getMySQLi();
        $searchtermQuery = $mysqli->prepare("SELECT sb.id, sb.searchterm
                                              FROM searchterm sb
                                              WHERE sb.searchterm = ?
                                              ORDER BY id asc
                                              LIMIT 1");
        $suchbegriff = $searchterm->getSuchbegriff();
        $searchtermQuery->bind_param("s", $suchbegriff);
        $searchtermQuery->execute();
        $id   = 0;
        $name = "";
        $searchtermQuery->bind_result($id, $name);
        $searchtermQuery->fetch();
        $searchterm->setID($id);

        if ($searchterm->getID() == 0) {
            return false;
        }
        return true;
    }

    /**
     * Sichern von mehreren Suchbegriffen.
     * @param array $searchterms array(Suchbegriff)
     * @throws Exception
     * @return array array(bool)
     */
    public function speichereSuchbegriffe(array $searchterms) {
        $insertArray   = array();
        $updateArray   = array();
        $isGespeichert = array();

        /**@var $searchterm Suchbegriff*/
        foreach ($searchterms as $searchterm) {
            if ($searchterm->getID() < 1) {
                $insertArray[] = $searchterm;
            }
            else {
                $updateArray[] = $searchterm;
            }
        }

        try {
            if (!empty($insertArray) && !empty($updateArray)) {
                throw new Exception("Es wurden Suchbegriffe für Insert und Update mit einem Aufruf übergeben\n");
            }
            elseif (!empty($insertArray)) {
                $isGespeichert = $this->insertSuchbergiffe($insertArray);
            }
            else {
                $isGespeichert = $this->updateSuchbergiffe($updateArray);
            }
            return $isGespeichert;
        } catch (Exception $exception) {
            echo "Fehler: " . $exception->getMessage();
        }
    }

    /**
     * Zum speichern neuer Suchbegriffe.
     * @param array $suchbegriffe array(Suchbegriff)
     * @return array array(bool)
     */
    public function insertSuchbergiffe(array $searchterms) {
        $mysqli          = parent::getMySQLi();
        $searchtermQuery = $mysqli->prepare("INSERT INTO searchterm(searchterm) VALUES (?)");
        $isGespeichert   = array();

        /**@var $suchbegriff Suchbegriff*/
        foreach ($searchterms as $searchterm) {
            $searchword = $searchterm->getSuchbegriff();
            $searchtermQuery->bind_param("s", $searchword);
            $isGespeichert[] = $searchtermQuery->execute();
        }

        $searchtermQuery->close();
        return $isGespeichert;
    }

    /**
     * Aktualisiert Einträge in der Datenbank..
     * @param array $suchbegriffe array(Suchbegriff)
     * @return array array(bool)
     */
    public function updateSuchbergiffe(array $searchterms) {
        $mysqli          = parent::getMySQLi();
        $searchtermQuery = $mysqli->prepare("UPDATE searchterm SET searchterm = ? WHERE id = ?");
        $isGespeichert   = array();

        /**@var $suchbegriff Suchbegriff*/
        foreach ($searchterms as $searchterm) {
            $searchword = $searchterm->getSuchbegriff();
            $id         = $searchterm->getID();
            $searchtermQuery->bind_param("si", $searchword, $id);
            $isGespeichert[] = $searchtermQuery->execute();
        }

        $searchtermQuery->close();
        return $isGespeichert;
    }
}

?>