<?php
/**
 * Erstellung:              14.06.12
 * Autor:                   Daniel Reichelt, mydata GmbH
 * Beschreibung:
 */
include_once("application/models/dao/DAO.php");
include_once("application/models/Domaene.php");

class DomaeneDAO extends DAO {
    public function __construct() {
        parent::__construct();
    }

    /**
     * Holt die Angaben einer mehrerer Domaenen aus der DB
     * @param array $ids array(int)
     * @return array array(Domaene)
     */
    public function fetchDomaenenByIDs(array $ids) {
        $mysqli  = parent::getMySQLi();
        $idArray = array();

        foreach ($ids as $id) {
            $idArray[] = $mysqli->real_escape_string($id);
        }

        $domanenIDs   = "'" . implode("', '", $idArray) . "'";
        $domaeneQuery = parent::getMySQLi()->query("SELECT do.id, do.name, do.url
                                                    FROM domaene do
                                                    WHERE do.id IN ($domanenIDs)
                                                    ORDER BY name asc;");
        $domaenen     = array();

        while ($domaeneResultat = $domaeneQuery->fetch_assoc()) {
            $domaenen[] = new Domaene($domaeneResultat["id"], $domaeneResultat["name"], $domaeneResultat["url"]);
        }

        return $domaenen;
    }

    /**
     * Setzt die ID einer Domaene anhand von Name und URL.
     * @param Domaene $domaene
     */
    public function setzeDomaeneIDByURL(&$domaene) {
        $mysqli       = parent::getMySQLi();
        $domaeneQuery = $mysqli->prepare("SELECT dm.id
                                          FROM domaene dm
                                          WHERE dm.url = ? AND dm.name = ?
                                          ORDER BY id asc
                                          LIMIT 1");
        $domaeneQuery->bind_param("ss", $domaene->getUrl(), $domaene->getName());
        $domaeneQuery->execute();
        $id = 0;
        $domaeneQuery->bind_result($id);
        $domaeneQuery->fetch();
        $domaene->setID($id);
    }

    /**
     * Holt die Namen einer Domaene anhand der URL.
     * @param Domaene $domaene
     * @return bool
     */
    public function fetchIsDomaeneRegisteriert($domaene) {
        $mysqli = parent::getMySQLi();

        $domaeneQuery = $mysqli->prepare("SELECT dm.id
                                          FROM domaene dm
                                          WHERE dm.url = ?
                                          ORDER BY id asc
                                          LIMIT 1");
        $domaeneQuery->bind_param("s", $domaene->getUrl());
        $domaeneQuery->execute();
        $id   = 0;
        $name = "";
        $url  = "";
        $domaeneQuery->bind_result($id);
        $domaeneQuery->fetch();

        $domaene->setID($id);

        if ($domaene->getID() == 0) {
            return false;
        }

        return true;
    }

    /**
     * Sichern von mehreren Domaenen.
     * @param array $domaenen array(Domaene)
     * @throws Exception
     * @return array array(bool)
     */
    public function speichereDomaenen(array $domaenen) {
        $insertArray   = array();
        $updateArray   = array();
        $isGespeichert = array();

        /**@var $domaene Domaene*/
        foreach ($domaenen as $domaene) {
            if ($domaene->getID() < 1) {
                $insertArray[] = $domaene;
            }
            else {
                $updateArray[] = $domaene;
            }
        }

        try {
            if (!empty($insertArray) && !empty($updateArray)) {
                throw new Exception("Es wurden Domaenen für Insert und Update mit einem Aufruf übergeben\n");
            }
            elseif (!empty($insertArray)) {
                $isGespeichert = $this->insertDomaenen($insertArray);
            }
            else {
                $isGespeichert = $this->updateDomaenen($updateArray);
            }
            return $isGespeichert;
        } catch (Exception $exception) {
            echo "Fehler: " . $exception->getMessage();
        }
    }

    /**
     * Zum speichern neuer Domaenen.
     * @param array $domaenen array(Domaene)
     * @return array array(bool)
     */
    public function insertDomaenen(array $domaenen) {
        $mysqli        = parent::getMySQLi();
        $domaeneQuery  = $mysqli->prepare("INSERT INTO domaene(name, url) VALUES (?, ?)");
        $isGespeichert = array();

        /**@var $domaene Domaene*/
        foreach ($domaenen as $domaene) {
            $name = $domaene->getName();
            $url  = $domaene->getUrl();
            $domaeneQuery->bind_param("ss", $name, $url);
            $isGespeichert[] = $domaeneQuery->execute();
        }
        $domaeneQuery->close();
        return $isGespeichert;
    }

    /**
     * Aktualisiert Einträge in der Datenbank..
     * @param array $domaenen array(Domaene)
     * @return array array(bool)
     */
    public function updateDomaenen(array $domaenen) {
        $mysqli        = parent::getMySQLi();
        $domaeneQuery  = $mysqli->prepare("UPDATE domaene SET name = ?, url = ? WHERE id = ?");
        $isGespeichert = array();

        /**@var $domaene Domaene*/
        foreach ($domaenen as $domaene) {
            $name = $domaene->getName();
            $url  = $domaene->getUrl();
            $id   = $domaene->getID();
            $domaeneQuery->bind_param("ssi", $name, $url, $id);
            $isGespeichert[] = $domaeneQuery->execute();
        }
        $domaeneQuery->close();
        return $isGespeichert;
    }
}

?>