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
        $suchbegriffIDs   = "'" . implode("', '", $idArray) . "'";
        $mysqli           = parent::getMySQLi();
        $suchbegriffQuery = $mysqli->query("SELECT sb.id, sb.suchbegriff
                                            FROM suchbegriff sb
                                            WHERE sb.id IN ($suchbegriffIDs)
                                            ORDER BY id asc");
        $suchbegriffe     = array();

        while ($suchbegriffResultat = $suchbegriffQuery->fetch_assoc()) {
            $suchbegriffe[] = new Suchbegriff($suchbegriffResultat["id"], $suchbegriffResultat["suchbegriff"]);
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
        $suchbegriffIDs   = "'" . implode("', '", $idArray) . "'";
        $suchbegriffQuery = $mysqli->query("SELECT sb.id, sb.suchbegriff
                                            FROM suchbegriff sb
                                            WHERE sb.id IN ($suchbegriffIDs)
                                            ORDER BY id asc");
        $suchbegriffe     = array();

        while ($suchbegriffResultat = $suchbegriffQuery->fetch_assoc()) {
            $suchbegriffe[$suchbegriffResultat["id"]] = $suchbegriffResultat["suchbegriff"];
        }

        return $suchbegriffe;
    }

    /**
     * Setzt die ID eines Suchbegriffes anhand vom Name.
     * @param Suchbegriff $suchbegriff
     * @return array array(string)
     */
    public function setzeSuchbegriffIDByName(&$suchbegriff) {
        $mysqli       = parent::getMySQLi();
        $suchbegriffQuery = $mysqli->prepare("SELECT sb.id
                                              FROM suchbegriff sb
                                              WHERE sb.suchbegriff = ?
                                              ORDER BY id asc
                                              LIMIT 1");
        $suchbegriffQuery->bind_param("s", $suchbegriff->getSuchbegriff());
        $suchbegriffQuery->execute();
        $id = 0;
        $suchbegriffQuery->bind_result($id);
        $suchbegriffQuery->fetch();
        $suchbegriff->setID($id);
    }

    /**
     * Holt die Namen eines oder mehrerer Suchbegriffe mit ID als key.
     * @param Suchbegriff $suchbegriff
     * @return bool
     */
    public function fetchIsSuchbegriffRegisteriert($suchbegriff) {
        $mysqli           = parent::getMySQLi();
        $suchbegriffQuery = $mysqli->prepare("SELECT sb.id, sb.suchbegriff
                                              FROM suchbegriff sb
                                              WHERE sb.suchbegriff = ?
                                              ORDER BY id asc
                                              LIMIT 1");
        $suchbegriffQuery->bind_param("s", $suchbegriff->getSuchbegriff());
        $suchbegriffQuery->execute();
        $id   = 0;
        $name = "";
        $suchbegriffQuery->bind_result($id, $name);
        $suchbegriffQuery->fetch();
        $suchbegriff->setID($id);

        if ($suchbegriff->getID() == 0) {
            return false;
        }
        return true;
    }

    /**
     * Sichern von mehreren Suchbegriffen.
     * @param array $suchbegriffe array(Suchbegriff)
     * @throws Exception
     * @return array array(bool)
     */
    public function speichereSuchbegriffe(array $suchbegriffe) {
        $insertArray   = array();
        $updateArray   = array();
        $isGespeichert = array();

        /**@var $suchbegriff Suchbegriff*/
        foreach ($suchbegriffe as $suchbegriff) {
            if ($suchbegriff->getID() < 1) {
                $insertArray[] = $suchbegriff;
            }
            else {
                $updateArray[] = $suchbegriff;
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
    public function insertSuchbergiffe(array $suchbegriffe) {
        $mysqli           = parent::getMySQLi();
        $suchbegriffQuery = $mysqli->prepare("INSERT INTO suchbegriff(suchbegriff) VALUES (?)");
        $isGespeichert    = array();

        /**@var $suchbegriff Suchbegriff*/
        foreach ($suchbegriffe as $suchbegriff) {
            $suchwort = $suchbegriff->getSuchbegriff();
            $suchbegriffQuery->bind_param("s", $suchwort);
            $isGespeichert[] = $suchbegriffQuery->execute();
        }

        $suchbegriffQuery->close();
        return $isGespeichert;
    }

    /**
     * Aktualisiert Einträge in der Datenbank..
     * @param array $suchbegriffe array(Suchbegriff)
     * @return array array(bool)
     */
    public function updateSuchbergiffe(array $suchbegriffe) {
        $mysqli           = parent::getMySQLi();
        $suchbegriffQuery = $mysqli->prepare("UPDATE suchbegriff SET suchbegriff = ? WHERE id = ?");
        $isGespeichert    = array();

        /**@var $suchbegriff Suchbegriff*/
        foreach ($suchbegriffe as $suchbegriff) {
            $suchwort = $suchbegriff->getSuchbegriff();
            $id       = $suchbegriff->getID();
            $suchbegriffQuery->bind_param("si", $suchwort, $id);
            $isGespeichert[] = $suchbegriffQuery->execute();
        }

        $suchbegriffQuery->close();
        return $isGespeichert;
    }
}

?>