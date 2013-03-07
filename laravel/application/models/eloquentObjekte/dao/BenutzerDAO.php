<?php
/**
 * Erstellung:              21.08.12
 * Autor:                   Daniel Reichelt, mydata GmbH
 * Beschreibung:
 */

require_once("application/models/dao/DAO.php");
require_once("application/models/Benutzer.php");
require_once("application/models/PageRank.php");
require_once("application/models/Domaene.php");
require_once("application/models/Suchbegriff.php");

class BenutzerDAO extends DAO {
    public function __construct() {
        parent::__construct();
    }

    /**
     * Holt die Angaben eines einzelnen Benutzers aus der DB.
     * @param string $loginName
     * @param string $password
     * @return Benutzer
     */
    public function fetchBenutzerByLoginPW($loginName, $password) {
//        $loginName         = $benutzer->getLoginname();
        $mysqli            = parent::getMySQLi();
        $suchmaschineQuery = $mysqli->prepare("SELECT ben.id, ben.gruppeID, ben.name, ben.email, ben.loginname, ben.password, ben.administrator
                                               FROM benutzer ben
                                               WHERE ben.loginname = ? AND ben.password = ?");
        $suchmaschineQuery->bind_param("ss", $loginName, md5($password));
        $suchmaschineQuery->execute();
        $benutzerID    = 0;
        $name          = "";
        $email         = "";
        $loginName     = 0;
        $password      = "";
        $administrator = false;
        $suchmaschineQuery->bind_result($benutzerID, $gruppeID, $name, $email, $loginName, $password, $administrator);
        $suchmaschineQuery->fetch();
        $benutzer = new Benutzer($benutzerID, $gruppeID, $name, $email, $loginName, $password, $administrator, null);
        $suchmaschineQuery->close();
        return $benutzer;
    }

    /**
     * @param Benutzer $benutzer
     * @return PageRank
     */
    public function letzteSucheVonBenutzer($benutzer) { //sollte unnötig geworden sein
        $mysqli            = parent::getMySQLi();
//        new PageRank($pageRankID, new Domaene($domaeneID, $domaeneName, $domaeneURL), $suchmaschineID, $suchmaschineGefundenID, new Suchbegriff($suchbegriffID, $suchwort), $benutzerID, $position, $gefundenURL, $anzahlErgebnisse, $datum)
        $suchmaschineQuery = $mysqli->prepare("SELECT pr.id, dm.id, dm.name, dm.url, pr.suchmaschineID, pr.suchmaschineGefundenID, su.id, su.suchbegriff, pr.benutzerID, pr.position, pr.gefundeneURL, pr.anzahlErgebnisse, pr.datum
                                               FROM pageRank pr
                                               INNER JOIN domaene dm ON pr.domaeneID = dm.id
                                               INNER JOIN suchbegriff su ON pr.suchbegriffID = su.id
                                               WHERE pr.benutzerID = ?
                                               ORDER BY pr.id DESC
                                               LIMIT 1");
        $suchmaschineQuery->bind_param("i", $benutzer->getID());
        $suchmaschineQuery->execute();
        $pageRankID             = 0;
        $domaeneID              = 0;
        $domaeneName            = "";
        $domaeneURL             = "";
        $suchmaschineID         = 0;
        $suchmaschineGefundenID = 0;
        $suchbegriffID          = 0;
        $suchwort               = "";
        $benutzerID             = 0;
        $position               = 0;
        $gefundenURL            = "";
        $anzahlErgebnisse       = 0;
        $datum                  = 0;
        $suchmaschineQuery->bind_result($pageRankID, $domaeneID, $domaeneName, $domaeneURL, $suchmaschineID, $suchmaschineGefundenID,
                                        $suchbegriffID, $suchwort, $benutzerID, $position, $gefundenURL, $anzahlErgebnisse,
                                        $datum);
        $suchmaschineQuery->fetch();
        $suchmaschineQuery->close();
        return new PageRank($pageRankID, new Domaene($domaeneID, $domaeneName, $domaeneURL), $suchmaschineID, $suchmaschineGefundenID, new Suchbegriff($suchbegriffID, $suchwort), $benutzerID, $position, $gefundenURL, $anzahlErgebnisse, $datum);
    }

    /**
     * Sichern der Daten eines Benutzers
     * @param Benutzer $benutzer
     * @param null|string $neuesPassword
     * @return bool
     * @throws Exception
     */
    public function saveBenutzer($benutzer, $neuesPassword = null) {
        $insert        = "";
        $update        = "";
        $isGespeichert = false;

        if (is_null($benutzer->getID())) {
            $insert = $benutzer;
        }
        else {
            $update = $benutzer;
        }

        try {
            if (!empty($insert) && !empty($update)) { //kann an sich nicht eintreffen, es wird immer nur 1 benutzer übergeben...
                throw new Exception("Es wurden Benutzer für Insert und Update mit einem Aufruf übergeben\n");
            }
            elseif (!empty($insert)) {
                $isGespeichert = $this->insertBenutzer($insert);
            }
            else {
                $isGespeichert = $this->updateBenutzer($update);
                if ($neuesPassword != null && $isGespeichert) {
                    $isGespeichert = $this->updateBenutzerPassword($benutzer, $neuesPassword);
                }
            }
            return $isGespeichert;
        } catch (Exception $exception) {
            echo "Fehler: " . $exception->getMessage();
            return $isGespeichert;
        }
    }

    /**
     * Zum speichern eines neuen Benutzers.
     * @param Benutzer $benutzer
     * @return bool
     */
    public function insertBenutzer($benutzer) {
        $mysqli        = parent::getMySQLi();
        $domaeneQuery  = $mysqli->prepare("INSERT INTO benutzer(gruppeID, name, email, loginname, password, administrator) VALUES (?, ?, ?, ?, ?, ?)");
        $isGespeichert = false;
        $gruppeID      = $benutzer->getGruppeID();
        $name          = $benutzer->getName();
        $email         = $benutzer->getEmail();
        $loginname     = $benutzer->getLoginname();
        $password      = $benutzer->getPassword();
        $administrator = $benutzer->getAdministrator();
        $domaeneQuery->bind_param("isssss", $gruppeID, $name, $email, $loginname, $password, $administrator);
        $isGespeichert = $domaeneQuery->execute();
        $domaeneQuery->close();
        return $isGespeichert;
    }

    /**
     * Aktualisiert Benutzer in der Datenbank..
     * @param Benutzer $benutzer
     * @return bool
     */
    public function updateBenutzer($benutzer) {
        $mysqli        = parent::getMySQLi();
        $domaeneQuery  = $mysqli->prepare("UPDATE benutzer SET gruppeID = ?, name = ?, email = ?, loginname = ?, administrator = ? WHERE id = ? AND password = ?");
        $isGespeichert = false;
        $gruppeID      = $benutzer->getGruppeID();
        $name          = $benutzer->getName();
        $email         = $benutzer->getEmail();
        $loginname     = $benutzer->getLoginname();
        $administrator = $benutzer->getAdministrator();
        $id            = $benutzer->getID();
        $password      = $benutzer->getPassword();
        $domaeneQuery->bind_param("isssiis", $gruppeID, $name, $email, $loginname, $administrator, $id, $password);
        $isGespeichert = $domaeneQuery->execute();
        $domaeneQuery->close();
        return $isGespeichert;
    }

    /**
     * @param Benutzer $benutzer
     * @param string $neuesPassword
     * @return bool
     */
    public function updateBenutzerPassword($benutzer, $neuesPassword) {
        $mysqli        = parent::getMySQLi();
        $domaeneQuery  = $mysqli->prepare("UPDATE benutzer SET password = ? WHERE id = ? AND password = ?");
        $isGespeichert = false;
        $id            = $benutzer->getID();
        $password      = $benutzer->getPassword();
        $domaeneQuery->bind_param("sis", md5($neuesPassword), $id, $password);
        $isGespeichert = $domaeneQuery->execute();
        $domaeneQuery->close();
        return $isGespeichert;
    }
}

?>