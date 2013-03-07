<?php
/**
 * Erstellung:              31.07.12
 * Autor:                   Daniel Reichelt, mydata GmbH
 * Beschreibung:
 */

require_once("application/models/IParser.php");
require_once("application/models/PageRank.php");
require_once("application/models/Resultat.php");
require_once("application/models/Eintrag.php");
require_once("application/models/Parameter.php");
require_once("application/models//dao/PageRankDAO.php");

class GoogleCOMParser implements IParser {

    /**
     * Entnimmt die einzelnen Eintraege aus der Seite
     * [1][$i]beeinhaltet alle Eintraege
     * @var string
     */
    private $regexEintrag = '(<h3 class="r">(.*?)<!--n-->)';

    /**
     * @var string
     */
    private $regexSeitenzahl = '';

    /**
     * Findet die Positionsnummer zu den angefragten Suchbegriffen, die Position steht in [1]
     * @var string
     */
    private $regexPosition = "(\(this,'.*?','.*?','.*?','(.*?)','.*?','.*?','.*?',.*?,.*?\))";

    /**
     * Findet auf der Seite die Anzahl aller Einträge zu den Begriffen, die Ergebniss Anzahl liegt unter [2]
     * @var string
     */
    private $regexAnzahlErgebnisse = '((<div id=resultStats>(.*?)About[\s]*)(([0-9]{1,3}[\,]*)+)([\s]*)([\s]*results<nobr>))';

    /**
     * findet alle gesuchten Begriffe aus einem Eintrag, der gesuchte Begriff wird mit [1] ausgelesen
     * @var string
     */
    private $regexSuchbegriff = '(<[em|b]{1,2}>([a-zA-Z0-9]*?)</[em|b]{1,2}>)';

    /**
     * Sucht die URL raus zu der verwiesen wir)(([0-9]{1,3}[\.]*)+)([\s]*results<nobr>d.
     * Kann für einen einzelnen Eintrag oder die ganze Seite genutzt werden..
     * [1] beeinhaltet die vollständige URL
     * @var string
     */
    private $regexDomaene = '(<a href="((ht|f)tp(s?))\://((www\.){0,1}([a-zA-Z0-9-\.]+[\.][a-z]+))/[\S]+.+?class=l)';

    private $suchmaschineID = 0;
    private $name = 0;
    private $suchmaschineUrl = "";
    private $positionenJeSeite = 0;
    private $parameter;

    /**
     * @param int $suchmaschineID
     * @param string $name
     * @param string $suchmaschineUrl
     * @param int $positionenJeSeite
     * @param array $parameter array(Parameter)
     */
    public function __construct($suchmaschineID, $name, $suchmaschineUrl, $positionenJeSeite, array $parameter) {
        $this->suchmaschineID    = (int)$suchmaschineID;
        $this->name              = (string)$name;
        $this->suchmaschineUrl   = (string)$suchmaschineUrl;
        $this->positionenJeSeite = (int)$positionenJeSeite;
        $this->parameter         = $parameter;
    }

    /**
     * @return string
     */
    public function getRegexEintrag() {
        return $this->regexEintrag;
    }

    /**
     * @return string
     */
    public function getRegexSeitenzahl() {
        return $this->regexSeitenzahl;
    }

    /**
     * @return string
     */
    public function getRegexPosition() {
        return $this->regexPosition;
    }

    /**
     * @return string
     */
    public function getRegexAnzahlErgebnisse() {
        return $this->regexAnzahlErgebnisse;
    }

    /**
     * @return string
     */
    public function getRegexSuchbegriff() {
        return $this->regexSuchbegriff;
    }

    /**
     * @return string
     */
    public function getRegexDomaene() {
        return $this->regexDomaene;
    }

    /**
     * @return int
     */
    public function getSuchmaschineID() {
        return $this->suchmaschineID;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSuchmaschineUrl() {
        return $this->suchmaschineUrl;
    }

    /**
     * @return int
     */
    public function getPositionenJeSeite() {
        return $this->positionenJeSeite;
    }

    /**
     * @return array array(Parameter)
     */
    public function getParameter() {
        return $this->parameter;
    }

    /**
     * Gibt das Ergebnis der Suche als array mit Resultat.
     * @param array $suchbegriffe array(Suchbergiffe)
     * @param int $pruefTiefe
     * @param array $webseitenResultate array(Resultat)
     * @return array array(Resultat)
     */
    public function holeAlleResultate(array $suchbegriffe, $pruefTiefe, $webseitenResultate = NULL) {
        $resultate = array();
        $merker    = 0;
        $sprung    = false;

        if (!is_null($webseitenResultate)) {
            $merker = count($webseitenResultate);
        }

        /** @var $suchbegriff Suchbegriff*/
        foreach ($suchbegriffe as $suchbegriff) {
            for ($webseitenIncrement = 0; $webseitenIncrement * $this->positionenJeSeite < $pruefTiefe; $webseitenIncrement++) {
                if ($merker > 0) {
                    $sprung = false;

                    /**@var $resultat Resultat*/
                    foreach ($webseitenResultate as $resultat) {
                        if ($resultat->getSuchbegriff() == $suchbegriff->getSuchbegriff() && $resultat->getSeitenzahl() == $webseitenIncrement) {
                            $resultate[] = $resultat;
                            $merker--;
                            $sprung = true;
                        }
                    }
                }
                if (!$sprung) {
                    $resultate[] = $this->holeResultat($suchbegriff->getSuchbegriff(), $webseitenIncrement);
                }
            }
        }

        return $resultate;
    }

    /**
     * Gibt das Resultat einer Suche mit der Suchmaschine zurück
     * @param string $suchbegriff
     * @param int $webseitenIncrement
     * @return array array(Resultat)
     */
    public function holeResultat($suchbegriff, $webseitenIncrement) {
        $suchParameter   = $this->holeSuchParameter();
        $aufzurufendeUrl = "http://" . $this->suchmaschineUrl . "/search?" . $suchParameter;
        $aufzurufendeUrl = sprintf($aufzurufendeUrl, $suchbegriff, $webseitenIncrement * 10);

        $verbindung = curl_init();
        curl_setopt($verbindung, CURLOPT_URL, $aufzurufendeUrl);
        curl_setopt($verbindung, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($verbindung, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($verbindung, CURLOPT_HTTPHEADER, array(
                                                          'Host: www.google.com',
                                                          'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:14.0) Gecko/20100101 Firefox/14.0.1',
                                                          'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                                                          'Accept-Language: en-gb,en;q=0.5',
                                                          'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7',
                                                          'Proxy-Connection: Close',
                                                          'Cookie: sSN=yGZ3kDE2wWH6InK_01BQT53esvcTyod1v9wKnyOb8dBBfWWmevVCsopZsTFm9S9cBYgKLVm3pJH.sj8yfgjLmg--',
                                                          'Cache-Control: max-age=0',
                                                          'Connection: Close'));
        $resultat = new Resultat(curl_exec($verbindung), $suchbegriff, $webseitenIncrement);
        //ausgeklammert, kann genutzt werden zur fehler erkennung
//        $header_size = curl_getinfo($verbindung, CURLINFO_HEADER_SIZE);
//        $header      = substr($resultatA, 0, $header_size);
//        $body        = substr($resultatA, $header_size);
//        echo curl_getinfo($verbindung, CURLINFO_HEADER_SIZE) . "\n" . substr($resultatA, 0, $header_size) . "\n" . substr($resultatA, $header_size) . "\n";
        curl_close($verbindung);
        return $resultat;
    }

    //neu
    /**
     * Gibt die Parameter der Suchmaschine formatiert zurück
     * hier kommt ein string zurück der zur URL hinzugefügt werden muss
     * @return string
     */
    public function holeSuchParameter() {
        $suchParameter = "";

        foreach (array_keys($this->parameter) as $key) {
            $suchParameter .= $this->parameter[$key]->getBezeichnung() . "=" . $this->parameter[$key]->getWert();
            if ($key < count($this->parameter) - 1) {
                $suchParameter .= "&";
            }
        }

        return $suchParameter;
    }

    /**
     * Gibt das Ergebnis der abgeschlossenen Suche als array mit PageRank zurück
     * @param array $suchbegriffe array(Suchbegriff)
     * @param array $domaenen array(Domaene)
     * @param array $resultate array(Resultate)
     * @param int $benutzerID
     * @return array array(PageRank)
     */
    public function holePageRanks(array $suchbegriffe, array $domaenen, array $resultate, $benutzerID) {
        $pageRankArray         = array();
        $anzahlErgebnisse      = 0;
        $merkerSuchbegriffName = "";
        $merkerSuchbegriff     = "";

        /** @var $resultat Resultat */
        foreach ($resultate as $resultat) {
            if ($merkerSuchbegriffName != $resultat->getSuchbegriff()) {
                $merkerSuchbegriffName = $resultat->getSuchbegriff();
                foreach ($suchbegriffe as $suchbegriff) {
                    if ($suchbegriff->getSuchbegriff() == $merkerSuchbegriffName) {
                        $merkerSuchbegriff = $suchbegriff;
                    }
                }
                $anzahlErgebnisse = $this->holeAnzahlErgebnisse($resultat->getSeite());
            }
            $eintraege = $this->holeEintraege($resultat->getSeite(), $domaenen);

            foreach ($eintraege as $eintrag) {
                $pageRankArray[] = $this->holeEintragWerte($eintrag, $this->getSuchmaschineID(), $merkerSuchbegriff,
                                                           $domaenen, $anzahlErgebnisse, $benutzerID);
            }
        }

        /** @var $suchbegriff Suchbegriff*/
        foreach ($suchbegriffe as $suchbegriff) {
            foreach ($domaenen as $domaene) {
                $pageRank = $this->holeFehlendeDomaene($pageRankArray, $domaene, $this->suchmaschineID,
                                                       $suchbegriff, $anzahlErgebnisse, $benutzerID);
                if ($pageRank != null) {
                    $pageRankArray[] = $pageRank;
                }
            }
        }

        return $pageRankArray;
    }

    /**
     * Entnimmt Anzahl an Gesammtenergebnissen für eine Suche, sofern vorhanden
     * @param string $seite
     * @return int
     */
    public function holeAnzahlErgebnisse($seite) {
        preg_match($this->regexAnzahlErgebnisse, $seite, $dieAnzahlErgebnisse);
        $anzahlErgebnisse = str_replace(",", "", $dieAnzahlErgebnisse[3]);
        return (int)$anzahlErgebnisse;
    }


    /**
     * Entnimmt einzelne Einträge aus einem Suchmaschinenresultat
     * Abhängig von der Suchmaschine wird der Eintrag unterschiedlich validiert, hier mithilfe von der Domaene
     * @param string $resultat
     * @param array $domaenen array(Domaene)
     * @return array array(Eintrag)
     */
    public function holeEintraege($resultat, array $domaenen) {
        $alleEintraege = array();
        preg_match_all($this->regexEintrag, $resultat, $eintraege);

        foreach (array_keys($eintraege[1]) as $key) {
            preg_match($this->regexDomaene, $eintraege[1][$key], $eintragDomaene);
            if ($this->isPosition($eintragDomaene[6], $domaenen) || $this->isPosition($eintragDomaene[4], $domaenen)) {
                $alleEintraege[] = new Eintrag($eintraege[1][$key], null);
            }
        }

        return $alleEintraege;
    }

    /**
     * Entnimmt dem Eintrag die gewünschten Werte und gibt den fertigen PageRank(s) zurück.
     * @param Eintrag $eintrag
     * @param int $suchmaschineGefundenID
     * @param Suchbegriff $suchbegriff
     * @param array $domaenen array(Domaene)
     * @param int $anzahlErgebnisse
     * @param int $benutzerID
     * @return PageRank
     */
    public function holeEintragWerte($eintrag, $suchmaschineGefundenID, $suchbegriff, array $domaenen, $anzahlErgebnisse, $benutzerID) {
        preg_match($this->getRegexDomaene(), $eintrag->getEintrag(), $domaene);
        preg_match($this->regexPosition, $eintrag->getEintrag(), $position);

        /**@var $dieDomaene Domaene*/
        foreach ($domaenen as $dieDomaene) {
            if ($dieDomaene->getUrl() == $domaene[4] || $dieDomaene->getUrl() == $domaene[6]) {
                return new PageRank(null, $dieDomaene, $this->suchmaschineID, $suchmaschineGefundenID, $suchbegriff, $benutzerID, $position[1], $domaene["4"], $anzahlErgebnisse, null);
            }
        }

        return null;
    }

    //neu
    /**
     * Prüft ob eine Position relevant ist, wenn ja return true else false
     * @param string $eintragDomaene
     * @param array $domaenen array(Domaene)
     * @return bool
     */
    public function isPosition($eintragDomaene, array $domaenen) {
        $isPosition = false;

        /**@var $domaene Domaene*/
        foreach ($domaenen as $domaene) {
            if ($domaene->getUrl() == $eintragDomaene) {
                $isPosition = true;
            }
        }

        return $isPosition;
    }

    /**
     * Überprüft ob die übergebenen Domaenen in den PageRanks enthalten sind.
     * Im Anschluss kommt PageRanks mit enventuell fehlenden Domaenen als leerer Eintrag zurück.
     * @param array $pageRanks array(PageRanks)
     * @param Domaene $domaene
     * @param int $suchmaschineGefundenID
     * @param Suchbegriff $suchbegriff
     * @param int $anzahlErgebnisse
     * @param int $benutzerID
     * @return PageRank|null
     */
    public function holeFehlendeDomaene(array $pageRanks, $domaene, $suchmaschineGefundenID, $suchbegriff, $anzahlErgebnisse, $benutzerID) {

        /**@var $pageRank PageRank*/
        foreach ($pageRanks as $pageRank) {
            $pageRankSuchbegriff = $pageRank->getSuchbegriff();
            $pageRankDomaene     = $pageRank->getDomaene();
            if ($pageRankSuchbegriff->getID() == $suchbegriff->getID() && $domaene->getID() == $pageRankDomaene->getID()) {
                return null;
            }
        }

        return new PageRank(null, $domaene, $this->suchmaschineID, $suchmaschineGefundenID, $suchbegriff, $benutzerID, 0, $anzahlErgebnisse, null);
    }
}

?>