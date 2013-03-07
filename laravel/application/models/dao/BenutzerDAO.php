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
        $suchmaschineQuery = $mysqli->prepare("SELECT ben.id, ben.categoryID, ben.name, ben.username, ben.email, ben.password, ben.administrator
                                               FROM user ben
                                               WHERE ben.username = ? AND ben.password = ?");
        $suchmaschineQuery->bind_param("ss", $loginName, md5($password));
        $suchmaschineQuery->execute();
        $benutzerID    = 0;
        $name          = "";
        $email         = "";
        $loginName     = 0;
        $password      = "";
        $administrator = false;
        $suchmaschineQuery->bind_result($userID, $categoryID, $name, $username, $email, $password, $administrator);
        $suchmaschineQuery->fetch();
        $benutzer = new Benutzer($userID, $categoryID, $name, $username, $email, $password, $administrator, null);
        $suchmaschineQuery->close();
        return $benutzer;
    }

    /**
     * @param Benutzer $benutzer
     * @return PageRank
     */
    public function letzteSucheVonBenutzer($benutzer) { //sollte unnötig geworden sein
        $mysqli = parent::getMySQLi();
//        new PageRank($pageRankID, new Domaene($domaeneID, $domaeneName, $domaeneURL), $suchmaschineID, $suchmaschineGefundenID, new Suchbegriff($suchbegriffID, $suchwort), $benutzerID, $position, $gefundenURL, $anzahlErgebnisse, $datum)
        $suchmaschineQuery = $mysqli->prepare("SELECT pr.id, dm.id, dm.name, dm.url, pr.searchengineID, pr.searchengineFoundID, su.id, su.searchterm, pr.userID, pr.position, pr.foundURL, pr.ammountResults, pr.date
                                               FROM pageRank pr
                                               INNER JOIN domain dm ON pr.domaeneID = dm.id
                                               INNER JOIN searchterm su ON pr.searchtermID = su.id
                                               WHERE pr.userID = ?
                                               ORDER BY pr.id DESC
                                               LIMIT 1");
        $suchmaschineQuery->bind_param("i", $benutzer->getID());
        $suchmaschineQuery->execute();
        $pageRankID          = 0;
        $domainID            = 0;
        $domainName          = "";
        $domainURL           = "";
        $searchengineID      = 0;
        $searchengineFoundID = 0;
        $searchtermID        = 0;
        $searchterm          = "";
        $userID              = 0;
        $position            = 0;
        $foundURL            = "";
        $ammountResults      = 0;
        $date                = 0;
        $suchmaschineQuery->bind_result($pageRankID, $domainID, $domainName, $domainURL, $searchengineID,
                                        $searchengineFoundID,
                                        $searchtermID, $searchterm, $userID, $position, $foundURL,
                                        $ammountResults, $date);
        $suchmaschineQuery->fetch();
        $suchmaschineQuery->close();
        return new PageRank($pageRankID, new Domaene($domainID, $domainName, $domainURL), $searchengineID, $searchengineFoundID, new Suchbegriff($searchtermID, $searchterm), $userID, $position, $foundURL, $ammountResults, $date);
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
        $domaeneQuery  = $mysqli->prepare("INSERT INTO user(categoryID, name, username, email, password, administrator) VALUES (?, ?, ?, ?, ?, ?)");
        $isGespeichert = false;
        $categoryID    = $benutzer->getGruppeID();
        $name          = $benutzer->getName();
        $email         = $benutzer->getEmail();
        $username      = $benutzer->getLoginname();
        $password      = $benutzer->getPassword();
        $administrator = $benutzer->getAdministrator();
        $domaeneQuery->bind_param("isssss", $categoryID, $name, $loginname, $email, $password, $administrator);
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
        $domaeneQuery  = $mysqli->prepare("UPDATE user SET categoryID = ?, name = ?, username = ?, email = ?, administrator = ? WHERE id = ? AND password = ?");
        $isGespeichert = false;
        $categoryID    = $benutzer->getGruppeID();
        $name          = $benutzer->getName();
        $email         = $benutzer->getEmail();
        $username      = $benutzer->getLoginname();
        $administrator = $benutzer->getAdministrator();
        $id            = $benutzer->getID();
        $password      = $benutzer->getPassword();
        $domaeneQuery->bind_param("isssiis", $categoryID, $name, $username, $email, $administrator, $id, $password);
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
        $domaeneQuery  = $mysqli->prepare("UPDATE user SET password = ? WHERE id = ? AND password = ?");
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