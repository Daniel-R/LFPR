<?php
/**
 * Erstellung:              14.06.12
 * Autor:                   Daniel Reichelt, mydata GmbH
 * Beschreibung:
 */
//require_once('domain.php');
//require_once('application/models/domain.php');

class DomainDAO {
    public function __construct() {
    }

    /**
     * Holt die Angaben einer mehrerer Domaenen aus der DB
     * @param array $ids array(int)
     * @return array array(Domain)
     */
    public function fetchDomainsByIDs(array $ids) {
        $domains = array();

        foreach ($ids as $id) {
            $domains[] = Domain::table('domain')
                    ->where('id', '=', $id)
                    ->order_by('name', 'asc')
                    ->get();
        }

        return $domains;
    }

    /**
     * Setzt die ID einer Domaene anhand von Name und URL.
     * @param Domain $domain
     */
    public function setzeDomainIDByURL(&$_domain) {
        //TODO: Ich hoffe doch stark das dies NICHT die beste Lösung ist. vielleicht mit get_attribute('Feld')
        $domain = Domain::where('url', '=', $_domain->url)
                ->order_by('name', 'asc')
                ->first();
        $_domain->id = $domain->id;

//        $id = Domain::where('url', '=', $_domain->url)->get_id(); //nicht funktionstüchtig
//        $_domain->id = $id;
    }

    /**
     * Holt die Namen einer Domaene anhand der URL.
     * @param  Domain $domain
     * @return bool
     */
    public function fetchIsDomainRegisteriert($domain) {

        if (is_null(Domain::where('url', '=', $domain->url)->order_by('id', 'asc')->first())) {
            return false;
        }

        return true;
    }

    /**
     * Sichern von mehreren Domaenen.
     * @param array $domain array(Domaene)
     * @throws Exception
     * @return array array(bool)
     */
    public function speichereDomain(array $domain) {


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
    public function insertDomain(array $domaenen) {
        DB::table('domain')->insert(array(
                                         'name' => '',
                                         'url'  => ''
                                    ));

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
    public function updateDomain(array $domaenen) {
        DB::table('domain')->where('id', '=', $id)
                ->update(array(
                              'name' => '',
                              'url'  => ''
                         ));


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