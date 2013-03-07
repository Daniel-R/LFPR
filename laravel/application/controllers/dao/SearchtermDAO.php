<?php
/**
 * Erstellung:              20.06.12
 * Autor:                   Daniel Reichelt, mydata GmbH
 * Beschreibung:
 */

class SearchtermDAO {

    /**
     * Holt die Angaben eines oder mehrerer Suchbegriffe aus der DB.
     * @param array $ids array(int)
     * @return array array(Searchterm)
     */
    public function fetchSearchtermsByIDs(array $ids) {
        $searchterms = array();

        foreach ($ids as $id) {
            $searchterms[] = Searchterm::table('searchterm')
                    ->where_in('id', '=', $id)
                    ->order_by('searchterm', 'asc')
                    ->get();
        }

        return $searchterms;
    }

    /**
     * Holt die Namen eines oder mehrerer Suchbegriffe mit ID als key.
     * @param array $searchterms array(Searchterms)
     * @return array array(string)
     */
    public function fetchSearchtermNamenByIDs(array $searchterms) {
        //TODO: Ist derzeit was gaaaanz anderes ^^'
        $domain      = Domain::where('url', '=', $_domain->url)
                ->order_by('name', 'asc')
                ->first();
        $_domain->id = $domain->id;
    }

    /**
     * Setzt die ID eines Suchbegriffes anhand vom Name.
     * @param Searchterm $_searchterm
     * @return array array(string)
     */
    public function setzeSearchtermIDByURL(&$_searchterm) {

        //TODO: Ich hoffe doch stark das dies NICHT die beste Lösung ist. vielleicht mit get_attribute('Feld')
        $searchterm = Searchterm::where('searchterm', '=', $_searchterm->searchterm)
                ->order_by('searchterm', 'asc')
                ->first();
        $_searchterm->id = $searchterm->id;
    }

    /**
     * Holt die Namen eines oder mehrerer Suchbegriffe mit ID als key.
     * @param Suchbegriff $suchbegriff
     * @return bool
     */
    public function fetchIsSearchtermRegisteriert($searchterm) {
        if (is_null(Searchterm::where('searchterm', '=', $searchterm->searchterm)->order_by('id', 'asc')->first())) {
            return false;
        }

        return true;
//        $mysqli           = parent::getMySQLi();
//        $suchbegriffQuery = $mysqli->prepare("SELECT sb.id, sb.suchbegriff
//                                              FROM suchbegriff sb
//                                              WHERE sb.suchbegriff = ?
//                                              ORDER BY id asc
//                                              LIMIT 1");
//        $suchbegriffQuery->bind_param("s", $suchbegriff->getSuchbegriff());
//        $suchbegriffQuery->execute();
//        $id   = 0;
//        $name = "";
//        $suchbegriffQuery->bind_result($id, $name);
//        $suchbegriffQuery->fetch();
//        $suchbegriff->setID($id);
//
//        if ($suchbegriff->getID() == 0) {
//            return false;
//        }
//        return true;
    }

    /**
     * Sichern von mehreren Suchbegriffen.
     * @param array $suchbegriffe array(Suchbegriff)
     * @throws Exception
     * @return array array(bool)
     */
    public function speichereSearchterm(array $suchbegriffe) {
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