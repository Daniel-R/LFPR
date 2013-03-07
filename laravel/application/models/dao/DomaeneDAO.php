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

        $domainIDs   = "'" . implode("', '", $idArray) . "'";
        $domainQuery = parent::getMySQLi()->query("SELECT do.id, do.name, do.url
                                                    FROM domain do
                                                    WHERE do.id IN ($domainIDs)
                                                    ORDER BY name asc;");
        $domains     = array();

        while ($domainResult = $domainQuery->fetch_assoc()) {
            $domains[] = new Domaene($domaiinResult["id"], $domaiinResult["name"], $domaiinResult["url"]);
        }

        return $domains;
    }

    /**
     * Setzt die ID einer Domaene anhand von Name und URL.
     * @param Domaene $domain
     */
    public function setzeDomaeneIDByURL(&$domain) {
        $mysqli      = parent::getMySQLi();
        $domainQuery = $mysqli->prepare("SELECT dm.id
                                          FROM domain dm
                                          WHERE dm.url = ? AND dm.name = ?
                                          ORDER BY id asc
                                          LIMIT 1");
        $url = $domain->getUrl();
        $name = $domain->getName();
        $domainQuery->bind_param("ss", $url, $name);
        $domainQuery->execute();
        $id = 0;
        $domainQuery->bind_result($id);
        $domainQuery->fetch();
        $domain->setID($id);
    }

    /**
     * Holt die Namen einer Domaene anhand der URL.
     * @param Domaene $domaene
     * @return bool
     */
    public function fetchIsDomaeneRegisteriert($domain) {
        $mysqli = parent::getMySQLi();

        $domainQuery = $mysqli->prepare("SELECT dm.id
                                          FROM domain dm
                                          WHERE dm.url = ?
                                          ORDER BY id asc
                                          LIMIT 1");
        $url = $domain->getURL(); //laravel akzeptiert hier kein direktes anreichen der Methode die eine variable überreicht
        $domainQuery->bind_param("s", $url);
        $domainQuery->execute();
        $id   = 0;
        $name = "";
        $url  = "";
        $domainQuery->bind_result($id);
        $domainQuery->fetch();

        $domain->setID($id);

        if ($domain->getID() == 0) {
            return false;
        }

        return true;
    }

    /**
     * Sichern von mehreren Domaenen.
     * @param array $domains array(Domaene)
     * @throws Exception
     * @return array array(bool)
     */
    public function speichereDomaenen(array $domains) {
        $insertArray   = array();
        $updateArray   = array();
        $isGespeichert = array();

        /**@var $domaene Domaene*/
        foreach ($domains as $domain) {
            if ($domain->getID() < 1) {
                $insertArray[] = $domain;
            }
            else {
                $updateArray[] = $domain;
            }
        }

        try {
            if (!empty($insertArray) && !empty($updateArray)) {
                throw new Exception("Es wurden Domaenen für Insert und Update mit einem Aufruf übergeben\n");
            }
            else if (!empty($insertArray)) {
                $isGespeichert = $this->insertDomaenen($insertArray);
            }
            else if (!empty($updateArray)) {
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
    public function insertDomaenen(array $domains) {
        $mysqli        = parent::getMySQLi();
        $domainQuery   = $mysqli->prepare("INSERT INTO domain(name, url) VALUES (?, ?)");
        $isGespeichert = array();

        /**@var $domaene Domaene*/
        foreach ($domains as $domain) {
            $name = $domain->getName();
            $url  = $domain->getUrl();
            $domainQuery->bind_param("ss", $name, $url);
            $isGespeichert[] = $domainQuery->execute();
        }

        $domainQuery->close();
        return $isGespeichert;
    }

    /**
     * Aktualisiert Einträge in der Datenbank..
     * @param array $domains array(Domaene)
     * @return array array(bool)
     */
    public function updateDomaenen(array $domains) {
        $mysqli        = parent::getMySQLi();
        $domainQuery  = $mysqli->prepare("UPDATE domain SET name = ?, url = ? WHERE id = ?");
        $isGespeichert = array();

        /**@var $domaene Domaene*/
        foreach ($domains as $domain) {
            $name = $domain->getName();
            $url  = $domain->getUrl();
            $id   = $domain->getID();
            $domainQuery->bind_param("ssi", $name, $url, $id);
            $isGespeichert[] = $domainQuery->execute();
        }

        $domainQuery->close();
        return $isGespeichert;
    }
}

?>