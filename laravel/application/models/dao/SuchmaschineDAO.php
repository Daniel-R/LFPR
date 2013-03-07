<?php
/**
 * Erstellung:              21.06.12
 * Autor:                   Daniel Reichelt, mydata GmbH
 * Beschreibung:            Datenbank Aufrufe für Suchmaschine,
 *                          Enthält auch Parameter.
 */

require_once("application/models/dao/DAO.php");
require_once("application/models/Suchmaschine.php");
require_once("application/models/Parameter.php");
require_once("application/models/Verbindung.php");

class SuchmaschineDAO extends DAO {
    public function __construct() {
        parent::__construct();
    }


    /**Holt die Angaben einer einzelnen Suchmaschine aus der DB.
     * @param int $id
     * @return Suchmaschine
     */
    public function fetchSuchmaschineByID($id) {
        $mysqli            = parent::getMySQLi();
        $searchengineQuery = $mysqli->prepare("SELECT sm.id, sm.name, sm.url, sm.positionsEachSite
                                               FROM searchengine sm
                                               WHERE sm.id = ?
                                               LIMIT 1");
        $searchengineQuery->bind_param("i", $id);
        $searchengineQuery->execute();
        $searchengineID    = 0;
        $name              = "";
        $url               = "";
        $positionenJeSeite = 0;
        $searchengineQuery->bind_result($searchengineID, $name, $url, $positionsEachSite);
        $searchengineQuery->fetch();
        $searchengineQuery->close();
        $parameter = $this->fetchParameterBySuchmaschinenID($id);
        return new Suchmaschine($searchengineID, $name, $url, $positionsEachSite, $parameter);
    }

    /**
     * Holt die Namen einer oder mehrerer Suchmaschinen mit ID als key.
     * @param array $ids array(int)
     * @return array array(string)
     */
    public function fetchSuchmaschinenNamenByIDs(array $ids) {
        $mysqli  = parent::getMySQLi();
        $idArray = array();

        foreach ($ids as $id) {
            $idArray[] = $mysqli->real_escape_string($id);
        }

        $searchengineIDs   = "'" . implode("', '", $idArray) . "'";
        $mysqli            = parent::getMySQLi();
        $searchengineQuery = $mysqli->query("SELECT sm.id , sm.name
                                             FROM searchengine sm
                                             WHERE sm.id IN ($searchengineIDs)
                                             ORDER BY id asc");
        $searchengines     = array();

        while ($searchengineResultat = $searchengineQuery->fetch_assoc()) {
            $searchengines[$searchengineResultat["id"]] = $searchengineResultat["name"];
        }

        return $searchengines;
    }

    /**
     * Entscheidet ob das übergebene Array zum speichern oder updaten ist.
     * @param array $searchengines array(Suchmaschine)
     * @return array array(bool)
     * @throws Exception
     */
    public function saveSuchmaschine(array $searchengines) {
        $insertArray = array();
        $updateArray = array();

        /**@var $suchmaschine Suchmaschine*/
        foreach ($searchengines as $searchengine) {
            if (is_null($searchengine->getID())) {
                $insertArray[] = $searchengine;
            }
            else {
                $updateArray[] = $searchengine;
            }
        }

        $isGespeichert = array();

        try {
            if (!empty($insertArray) && !empty($updateArray)) {
                throw new Exception("Es wurden Suchmaschine für Insert und Update mit einem Aufruf übergeben\n");
            }
            elseif (!empty($insertArray)) {
                $isGespeichert = $this->insertSuchmaschine($insertArray);
            }
            else {
                $isGespeichert = $this->updateSuchmaschine($updateArray);
            }
            return $isGespeichert;
        } catch (Exception $exception) {
            echo "Fehler: " . $exception->getMessage();
        }
    }

    /**
     * Zum speichern neuer Suchbegriffe.
     * @param array $searchengines array(Suchmaschine)
     * @return array array(bool)
     */
    public function insertSuchmaschine(array $searchengines) {
        $mysqli            = parent::getMySQLi();
        $searchengineQuery = $mysqli->prepare("INSERT INTO searchengine(name, url, positionsEachSite) VALUES (?, ?, ?)");
        $isGespeichert     = array();

        /**@var $suchmaschine Suchmaschine*/
        foreach ($searchengines as $searchengine) {
            $name              = $searchengine->getName();
            $url               = $searchengine->getUrl();
            $positionsEachSite = $searchengine->getPositionenJeSeite();
            $searchengineQuery->bind_param("ssi", $name, $url, $positionsEachSite);
            $isGespeichert[] = $searchengineQuery->execute();
        }

        $searchengineQuery->close();
        return $isGespeichert;
    }

    /**
     * Aktualisiert Einträge in der Datenbank.
     * @param array $searchengines array(Suchbegriff)
     * @return array array(bool)
     */
    public function updateSuchmaschine(array $searchengines) {
        $mysqli            = parent::getMySQLi();
        $searchengineQuery = $mysqli->prepare("UPDATE searchengine SET name = ?, url = ?, positionsEachSite = ? WHERE id = ?");
        $isGespeichert     = array();

        /**@var $suchmaschine Suchmaschine*/
        foreach ($searchengines as $searchengine) {
            $name              = $searchengine->getName();
            $url               = $searchengine->getUrl();
            $positionsEachSite = $searchengine->getPositionenJeSeite();
            $id                = $searchengine->getID();
            $searchengineQuery->bind_param("ssii", $name, $url, $positionsEachSite, $id);
            $isGespeichert[] = $searchengineQuery->execute();
        }

        $searchengineQuery->close();
        return $isGespeichert;
    }

    /**
     * Gibt die Parameter einer Suchmaschine zurueck.
     * @param int $id
     * @return array array(Parameter)
     */
    public function fetchParameterBySuchmaschinenID($id) {
        $mysqli         = parent::getMySQLi();
        $parameterQuery = $mysqli->prepare("SELECT pa.id, pa.label, pw.value
                                            FROM searchengine sm
                                            INNER JOIN parameterValue pw ON pw.searchengineID=sm.id
                                            INNER JOIN parameter pa ON pw.parameterID=pa.id
                                            WHERE sm.id = ?");
        $parameterQuery->bind_param("i", $id);
        $parameterQuery->execute();
        $parameterID = 0;
        $bez         = "";
        $wert        = "";
        $parameterQuery->bind_result($parameterID, $bez, $wert);
        $parameter = array();

        while ($parameterQuery->fetch()) {
            $parameter[] = new Parameter($parameterID, $bez, $wert);
        }

        $parameterQuery->close();
        return $parameter;
    }

    /**
     * Entscheidet ob das übergebene Array zum speichern oder updaten ist.
     * Parameter speichern mit parameterID = NULL && suchmaschineID = NULL
     * Parameter updaten mit parameterID = int && suchmaschineID = NULL
     * ParameterWerte speichern mit isInsert = true && suchmaschineID = array(int)
     * ParameterWerte updaten mit isInsert = false && suchmaschineID = array(int)
     * @param array $parameter array(Parameter)
     * @param null|int $searchengineID
     * @param null|bool $isWerteInsert
     * @return array array(bool)
     * @throws Exception
     */
    public function saveParameter(array $parameter, $searchengineID = NULL, $isWerteInsert = NULL) {
        $insertArray   = array();
        $updateArray   = array();
        $isGespeichert = array();

        if (is_null($searchengineID)) {
            /**@var $einParameter Parameter*/
            foreach ($parameter as $einParameter) {
                if (is_null($einParameter->getID())) {
                    $insertArray[] = $einParameter;
                }
                else {
                    $updateArray[] = $einParameter;
                }
            }
            try {
                if (!empty($insertArray) && !empty($updateArray)) {
                    throw new Exception("Es wurden Parameter für Insert und Update mit einem Aufruf übergeben\n");
                }
                elseif (!empty($insertArray)) {

                    $isGespeichert = $this->insertParameter($insertArray);
                }
                else {
                    $isGespeichert = $this->updateParameter($updateArray);
                }
            } catch (Exception $exception) {
                echo "Fehler: " . $exception->getMessage();
            }
        }
        else {
            foreach ($parameter as $einParameter) {
                if ($isWerteInsert) {
                    $insertArray[] = $einParameter;
                }
                else {
                    $updateArray[] = $einParameter;
                }
            }
            try {
                if (!empty($insertArray) && !empty($updateArray)) {
                    throw new Exception("Es wurden ParameterWerte für Insert und Update mit einem Aufruf übergeben\n");
                }
                elseif (!empty($insertArray)) {

                    $isGespeichert = $this->insertParameterWerte($insertArray, $searchengineID);
                }
                else {
                    $isGespeichert = $this->updateParameterWerte($updateArray, $searchengineID);
                }
            } catch (Exception $exception) {
                echo "Fehler: " . $exception->getMessage();
            }
        }

        return $isGespeichert;
    }

    /**
     * Zum speichern neuer Parameter.
     * @param array $parameter array(Parameter)
     * @return array array(bool)
     */
    public function insertParameter(array $parameter) {
        $mysqli         = parent::getMySQLi();
        $parameterQuery = $mysqli->prepare("INSERT INTO parameter(label) VALUES (?)");
        $isGespeichert  = array();

        /**@var $einParameter Parameter*/
        foreach ($parameter as $einParameter) {
            $label = $einParameter->getBezeichnung();
            $parameterQuery->bind_param("s", $label);
            $isGespeichert[] = $parameterQuery->execute();
        }

        $parameterQuery->close();
        return $isGespeichert;
    }

    /**
     * Aktualisiert Einträge in der Datenbank.
     * @param array $parameter array(Suchbegriff)
     * @return array array(bool)
     */
    public function updateParameter(array $parameter) {
        $mysqli         = parent::getMySQLi();
        $parameterQuery = $mysqli->prepare("UPDATE parameter SET label = ? WHERE id = ?");
        $isGespeichert  = array();

        /**@var $einParameter Parameter*/
        foreach ($parameter as $einParameter) {
            $label = $einParameter->getBezeichnung();
            $id    = $einParameter->getID();
            $parameterQuery->bind_param("si", $label, $id);
            $isGespeichert[] = $parameterQuery->execute();
        }

        $parameterQuery->close();
        return $isGespeichert;
    }

    /**
     * Zum speichern neuer ParameterWerte.
     * @param array $parameter array(Parameter)
     * @param int $searchengineID
     * @return array array(bool)
     */
    public function insertParameterWerte(array $parameter, $searchengineID) {
        $mysqli         = parent::getMySQLi();
        $parameterQuery = $mysqli->prepare("INSERT INTO parameterValue(searchengineID, parameterID, wert)VALUES (?, ?, ?)");
        $isGespeichert  = array();

        /**@var $einParameter Parameter*/
        foreach ($parameter as $einParameter) {
            $parameterID = $einParameter->getID();
            $wert        = $einParameter->getWert();
            $parameterQuery->bind_param("iis", $searchengineID, $parameterID, $wert);
            $isGespeichert[] = $parameterQuery->execute();
        }

        $parameterQuery->close();
        return $isGespeichert;
    }

    /**
     * Aktualisiert Einträge in der Datenbank.
     * @param array $parameter array(Suchbegriff)
     * @param int $searchengineID
     * @return array array(bool)
     */
    public function updateParameterWerte(array $parameter, $suchmaschineID) {
        $mysqli         = parent::getMySQLi();
        $parameterQuery = $mysqli->prepare("UPDATE parameterValue SET wert = ? WHERE SearchengineID = ? AND parameterID = ?");
        $isGespeichert  = array();

        /**@var $einParameter Parameter*/
        foreach ($parameter as $einParameter) {
            $parameterID = $einParameter->getID();
            $wert        = $einParameter->getWert();
            $parameterQuery->bind_param("sii", $wert, $searchengineID, $parameterID);
            $isGespeichert[] = $parameterQuery->execute();
        }

        $parameterQuery->close();
        return $isGespeichert;
    }

    public function deleteParameterWerte(array $parameter, $searchengineID) {
        $mysqli         = parent::getMySQLi();
        $parameterQuery = $mysqli->prepare("DELETE FROM parameterValue WHERE searchengineID = ? AND parameterID = ?");
        $isGeloescht    = array();

        /**@var $einParameter Parameter*/
        foreach ($parameter as $einParameter) {
            $parameterID = $einParameter->getID();
            $parameterQuery->bind_param("ii", $searchengineID, $parameterID);
            $isGeloescht[] = $parameterQuery->execute();
        }

        $parameterQuery->close();
        return $isGeloescht;
    }
}

?>