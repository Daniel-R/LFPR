<?php
/**
 * Erstellung:              21.06.12
 * Autor:                   Daniel Reichelt, mydata GmbH
 * Beschreibung:            Datenbank Aufrufe für Suchmaschine,
 *                          Enthält auch Parameter.
 */

class SuchmaschineDAO {
    public function __construct() {
    }


    /**Holt die Angaben einer einzelnen Suchmaschine aus der DB.
     * @param int $id
     * @return Suchmaschine
     */
    public function fetchSearchengineByID($id) {
        $searchengines = array();

        $searchengines = Searchengine::where_in('id', $id)
                ->order_by('name', 'asc')
                ->get();

        return $searchengines;
    }

    /**
     * Holt die Namen einer oder mehrerer Suchmaschinen mit ID als key.
     * @param array $ids array(int)
     * @return array array(string)
     */
    public function fetchSearchengineNameByIDs(array $ids) {
        $searchengines     = array();
        $searchengineNames = array();


        foreach ($ids as $id) {
            $searchengines[] = Searchengine::table('searchengine')
                    ->where('id', '=', $id)
                    ->order_by('searchterm', 'asc')
                    ->get();
        }

        foreach ($searchengines as $searchengine) {

        }

        return $searchengineNames;

//        $mysqli  = parent::getMySQLi();
//        $idArray = array();
//
//        foreach ($ids as $id) {
//            $idArray[] = $mysqli->real_escape_string($id);
//        }
//
//        $suchmaschineIDs  = "'" . implode("', '", $idArray) . "'";
//        $mysqli           = parent::getMySQLi();
//        $suchbegriffQuery = $mysqli->query("SELECT sm.id , sm.name
//                                             FROM suchmaschine sm
//                                             WHERE sm.id IN ($suchmaschineIDs)
//                                             ORDER BY id asc");
//        $suchmaschinen    = array();
//
//        while ($suchbegriffResultat = $suchbegriffQuery->fetch_assoc()) {
//            $suchmaschinen[$suchbegriffResultat["id"]] = $suchbegriffResultat["name"];
//        }
//
//        return $suchmaschinen;
    }

    /**
     * Entscheidet ob das übergebene Array zum speichern oder updaten ist.
     * @param array $suchmaschinen array(Suchmaschine)
     * @return array array(bool)
     * @throws Exception
     */
    public function saveSuchmaschine(array $suchmaschinen) {
        $insertArray = array();
        $updateArray = array();

        /**@var $suchmaschine Suchmaschine*/
        foreach ($suchmaschinen as $suchmaschine) {
            if (is_null($suchmaschine->getID())) {
                $insertArray[] = $suchmaschine;
            }
            else {
                $updateArray[] = $suchmaschine;
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
     * @param array $suchmaschinen array(Suchmaschine)
     * @return array array(bool)
     */
    public function insertSuchmaschine(array $suchmaschinen) {
        $mysqli           = parent::getMySQLi();
        $suchbegriffQuery = $mysqli->prepare("INSERT INTO suchmaschine(name, url, positionenJeSeite) VALUES (?, ?, ?)");
        $isGespeichert    = array();

        /**@var $suchmaschine Suchmaschine*/
        foreach ($suchmaschinen as $suchmaschine) {
            $name              = $suchmaschine->getName();
            $url               = $suchmaschine->getUrl();
            $positionenJeSeite = $suchmaschine->getPositionenJeSeite();
            $suchbegriffQuery->bind_param("ssi", $name, $url, $positionenJeSeite);
            $isGespeichert[] = $suchbegriffQuery->execute();
        }

        $suchbegriffQuery->close();
        return $isGespeichert;
    }

    /**
     * Aktualisiert Einträge in der Datenbank.
     * @param array $suchmaschinen array(Suchbegriff)
     * @return array array(bool)
     */
    public function updateSuchmaschine(array $suchmaschinen) {
        $mysqli           = parent::getMySQLi();
        $suchbegriffQuery = $mysqli->prepare("UPDATE suchmaschine SET name = ?, url = ?, positionenJeSeite = ? WHERE id = ?");
        $isGespeichert    = array();

        /**@var $suchmaschine Suchmaschine*/
        foreach ($suchmaschinen as $suchmaschine) {
            $name              = $suchmaschine->getName();
            $url               = $suchmaschine->getUrl();
            $positionenJeSeite = $suchmaschine->getPositionenJeSeite();
            $id                = $suchmaschine->getID();
            $suchbegriffQuery->bind_param("ssii", $name, $url, $positionenJeSeite, $id);
            $isGespeichert[] = $suchbegriffQuery->execute();
        }

        $suchbegriffQuery->close();
        return $isGespeichert;
    }

    /**
     * Gibt die Parameter einer Suchmaschine zurueck.
     * @param int $id
     * @return array array(Parameter)
     */
    public function fetchParameterBySuchmaschinenID($id) {
        $mysqli         = parent::getMySQLi();
        $parameterQuery = $mysqli->prepare("SELECT pa.id, pa.bezeichnung, pw.wert
                                            FROM suchmaschine sm
                                            INNER JOIN parameterWerte pw ON pw.suchmaschineID=sm.id
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
     * @param null|int $suchmaschineID
     * @param null|bool $isWerteInsert
     * @return array array(bool)
     * @throws Exception
     */
    public function saveParameter(array $parameter, $suchmaschineID = NULL, $isWerteInsert = NULL) {
        $insertArray   = array();
        $updateArray   = array();
        $isGespeichert = array();

        if (is_null($suchmaschineID)) {
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

                    $isGespeichert = $this->insertParameterWerte($insertArray, $suchmaschineID);
                }
                else {
                    $isGespeichert = $this->updateParameterWerte($updateArray, $suchmaschineID);
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
        $parameterQuery = $mysqli->prepare("INSERT INTO parameter(bezeichnung) VALUES (?)");
        $isGespeichert  = array();

        /**@var $einParameter Parameter*/
        foreach ($parameter as $einParameter) {
            $bezeichnung = $einParameter->getBezeichnung();
            $parameterQuery->bind_param("s", $bezeichnung);
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
        $parameterQuery = $mysqli->prepare("UPDATE parameter SET bezeichnung = ? WHERE id = ?");
        $isGespeichert  = array();

        /**@var $einParameter Parameter*/
        foreach ($parameter as $einParameter) {
            $bezeichnung = $einParameter->getBezeichnung();
            $id          = $einParameter->getID();
            $parameterQuery->bind_param("si", $bezeichnung, $id);
            $isGespeichert[] = $parameterQuery->execute();
        }

        $parameterQuery->close();
        return $isGespeichert;
    }

    /**
     * Zum speichern neuer ParameterWerte.
     * @param array $parameter array(Parameter)
     * @param int $suchmaschineID
     * @return array array(bool)
     */
    public function insertParameterWerte(array $parameter, $suchmaschineID) {
        $mysqli         = parent::getMySQLi();
        $parameterQuery = $mysqli->prepare("INSERT INTO parameterWerte(suchmaschineID, parameterID, wert)VALUES (?, ?, ?)");
        $isGespeichert  = array();

        /**@var $einParameter Parameter*/
        foreach ($parameter as $einParameter) {
            $parameterID = $einParameter->getID();
            $wert        = $einParameter->getWert();
            $parameterQuery->bind_param("iis", $suchmaschineID, $parameterID, $wert);
            $isGespeichert[] = $parameterQuery->execute();
        }

        $parameterQuery->close();
        return $isGespeichert;
    }

    /**
     * Aktualisiert Einträge in der Datenbank.
     * @param array $parameter array(Suchbegriff)
     * @param int $suchmaschineID
     * @return array array(bool)
     */
    public function updateParameterWerte(array $parameter, $suchmaschineID) {
        $mysqli         = parent::getMySQLi();
        $parameterQuery = $mysqli->prepare("UPDATE parameterWerte SET wert = ? WHERE suchmaschineID = ? AND parameterID = ?");
        $isGespeichert  = array();

        /**@var $einParameter Parameter*/
        foreach ($parameter as $einParameter) {
            $parameterID = $einParameter->getID();
            $wert        = $einParameter->getWert();
            $parameterQuery->bind_param("sii", $wert, $suchmaschineID, $parameterID);
            $isGespeichert[] = $parameterQuery->execute();
        }

        $parameterQuery->close();
        return $isGespeichert;
    }

    public function deleteParameterWerte(array $parameter, $suchmaschineID) {
        $mysqli         = parent::getMySQLi();
        $parameterQuery = $mysqli->prepare("DELETE FROM parameterWerte WHERE suchmaschineID = ? AND parameterID = ?");
        $isGeloescht    = array();

        /**@var $einParameter Parameter*/
        foreach ($parameter as $einParameter) {
            $parameterID = $einParameter->getID();
            $parameterQuery->bind_param("ii", $suchmaschineID, $parameterID);
            $isGeloescht[] = $parameterQuery->execute();
        }

        $parameterQuery->close();
        return $isGeloescht;
    }
}

?>