<?php
/**
 * Erstellung:              14.08.12
 * Autor:                   Daniel Reichelt, mydata GmbH
 * Beschreibung:
 */

require_once("application/models/IParser.php");
require_once("application/models/PageRank.php");
require_once("application/models/Resultat.php");
require_once("application/models//dao/PageRankDAO.php");

class DuckDuckGoCOMParser implements IParser {

    /**
     * Entnimmt die einzelnen Eintraege aus der Seite
     * [1][$i]beeinhaltet alle Eintraege
     *  //unfertig!!!!!!!!!!!!!!!
     * @var string
     */
    private $regexEintrag = '(<div class=".*?" style=".*?">[\n|\s]*?(<a rel=".*?" href=".*?">))'; //ok
//'(<div class=".*?">[\n|\s]*?<div class=".*?" style=".*?">[\n|\s]*?<a rel=".*?" href=".*?">[\n|\s]*?<img width="[0-9]*?" height="[0-9]*?" alt=".*?"[\n|\s]*?src=".*?" style=".*?" name=".*?" />[\n|\s]*?</a>[\n|\s]*?</div>[\n|\s]*?<div class=".*?">.*[\n|\s]*?<a rel=".*?" class=".*?" href=".*?">.*?[\s]*?<div class=".*?">(.*?[\s])*?<div class=".*?">(.*?[\s])*?</div>[\n|\s]*?</div>[\n|\s]*?</div>)'; //ok

    /**
     * Gibt die derzeitige Seitenzahl für ein Resultat aus.
     * @var string
     */
    private $regexSeitenzahl = '(<span class="sb_count" id="count">(([0-9]+)[-]([0-9]+) von ){0,1}(([0-9]{1,3}[\.]?)*) Ergebnisse[n]?</span>)'; //wayne derzeit

    /**
     * Findet die Positionsnummer zu den angefragten Suchbegriffen
     * Kein Teil von DuckDuckGo, wird durch das Programm selber geprüft.
     * @var string
     */
    private $regexPosition = '(/u="[0-9]\|[0-9]{4}\|[0-9]{16}\|)'; //gibt es nicht

    /**
     * Findet auf der Seite die Anzahl aller Einträge zu den Begriffen, die Ergebniss Anzahl liegt unter [2]
     * Ist nicht Bestandteil von DuckDuckGO
     * @var string
     */
    //überarbeitet
    //beeinhaltet auch Eintrag 11-20 ect pepe
    private $regexAnzahlErgebnisse = '(<span class="sb_count" id="count">(([0-9]+)[-]([0-9]+) von ){0,1}(([0-9]{1,3}[\.]?)*) Ergebnisse[n]?</span>)'; //gibt keine

    /**
     * findet alle gesuchten Begriffe aus einem Eintrag, der gesuchte Begriff wird mit [1] ausgelesen
     * @var string
     */
    private $regexSuchbegriff = '(<b>(.*?)</b>)'; //ok

    /**
     * Sucht die URL raus zu der verwiesen wird.
     * Kann für einen einzelnen Eintrag oder die ganze Seite genutzt werden..
     * [1] beeinhaltet die vollständige URL
     * @var string
     */
    private $regexDomaene = '(<a rel=".*?" href="(http[s]?://((www\.){0,1}([a-zA-Z0-9-\.]+))[a-zA-Z0-9/?.=&\-_%]+)")'; //scheint ok

    /**
     * dient dem heraussuchen der nächsten Post Daten
     * @var string
     */
    private $regexPost = '(<input [abceilmnNopstuvxy=\'" ]+>[\n\s]*?(<input .*?>)[\n\s]*?(<input .*?>)[\n\s]*?(<input .*?>)[\n\s]*?(<input .*?>)[\n\s]*?(<input .*?>)[\n\s]*?(<input .*?>)[\n\s]*?(<input .*?>))';

    /**
     * entnimmt einem einzelnen inputs von dem regexPost den Value...
     * @var string
     */
    private $regexInputPostValue = '(value="(.*?)")';

    private $suchmaschineID = 0;
    private $name = 0;
    private $suchmaschineUrl = "";
    private $positionenJeSeite = 0;
    private $parameter;
    private $derzeitigeSeite = 0;
    private $post = array();
    private $position = 0;

    /**
     * @param int $suchmaschineID
     * @param string $name
     * @param string $suchmaschineUrl
     * @param int $positionenJeSeite
     * @param array $parameter array(Parameter)
     */
    public function __construct($suchmaschineID, $name, $suchmaschineUrl, $positionenJeSeite, array $parameter) {
        $this->suchmaschineID    = $suchmaschineID;
        $this->name              = $name;
        $this->suchmaschineUrl   = $suchmaschineUrl;
        $this->positionenJeSeite = $positionenJeSeite;
        $this->parameter         = $parameter;
        $this->post              = array(
//                                         'q'  => urlencode("mysqli"),
            'p'  => urlencode("1"),
//                                         's'  => urlencode("0"),
            'o'  => urlencode("json"),
//                                         'dc' => urlencode("-25"),
            'kl' => urlencode(""),
            'api'=> urlencode("d.js")
        );
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

    public function getPost() {
        return $this->post;
    }

    public function getPosition() {
        return $this->position;
    }

    public function setDerzeitigeSeite($derzeitigeSeite) {
        $this->derzeitigeSeite = $derzeitigeSeite;
    }

    /**
     * @param Resultat $resultat
     */
    public function setPost($resultat) {
        preg_match_all($this->regexPost, $resultat->getSeite(), $post);
        preg_match($this->regexInputPostValue, $post[3][0], $s);
        preg_match($this->regexInputPostValue, $post[5][0], $dc);
        $this->post["s"]  = urlencode($s[1]);
        $this->post["dc"] = urlencode($dc[1]);
    }

    /**
     * Suchbegriff für die nächste Suche
     * @param string $suchbegriff
     */
    public function setPostSuchbegriff($suchbegriff) {
        $this->post["q"] = $suchbegriff;
    }

    /**
     * erhöht die Position um 1 oder reseted den counter
     * @param bool $reset
     */
    public function setPosition($reset) {
        if ($reset) {
            $this->position = 0;
        }
        else {
            $this->position += 1;
        }
    }

    /**
     * Gibt das Ergebnis der Suche als array mit Resultat.
     * @param array $suchbegriffe array(Suchbergiffe)
     * @param int $pruefTiefe
     * @return array array(Resultat)
     */
    public function holeAlleResultate(array $suchbegriffe, $pruefTiefe) {
        $resultate = array();

        /** @var $suchbegriff Suchbegriff*/
        foreach ($suchbegriffe as $suchbegriff) {
            $position        = 0;
            $this->post["q"] = $suchbegriff->getSuchbegriff();
            for ($webseitenIncrement = 0; $position < $pruefTiefe; $webseitenIncrement++) {
                $resultate[] = $this->holeResultat($suchbegriff->getSuchbegriff(), $webseitenIncrement);

                /**@var $resultat Resultat*/
                $resultat = end($resultate);
                $this->setPost($resultat->getSeite());
                $position += count(preg_match_all($this->getRegexEintrag(), $resultat->getSeite(), $anzahlErg));
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
//        $suchParameter   = $this->holeSuchParameter();
        $aufzurufendeUrl = "http://" . $this->suchmaschineUrl . "/html/"; // . $suchParameter;
//        $aufzurufendeUrl = sprintf($aufzurufendeUrl, $suchbegriff, $webseitenIncrement * 10 + 1);
        $post      = $this->post;
        $post['q'] = urlencode($suchbegriff);

        $verbindung = curl_init();
        curl_setopt($verbindung, CURLOPT_URL, $aufzurufendeUrl);
        curl_setopt($verbindung, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($verbindung, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($verbindung, CURLOPT_HTTPHEADER, array(
                                                          //                                                          'Host: www.duckduckgo.com',
                                                          'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:14.0) Gecko/20100101 Firefox/14.0.1',
                                                          'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                                                          'Accept-Language: en-us,en;q=0.5',
                                                          'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7',
                                                          'Proxy-Connection: Close',
                                                          //                                                          'Cookie: MUID=083C4449403463203BE6470143346303;
                                                          //                                                                   RMS=F=OAAAIAAAAARB&A=S;
                                                          //                                                                   SCRHDN=ASD=0&DURL=#;
                                                          //                                                                   SRCHD=MS=2428201&SM=1&D=2280170&AF=NOFORM',
                                                          'Cache-Control: max-age=0',
                                                          'Connection: Close'));
        curl_setopt($verbindung, CURLOPT_POST, 1);
        curl_setopt($verbindung, CURLOPT_POSTFIELDS, $post);
        $resultat = new Resultat(curl_exec($verbindung), $suchbegriff, $webseitenIncrement);
        curl_close($verbindung);
        return $resultat;
    }

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
        $merkerSuchbegriff     = 0;

        /** @var $resultat Resultat */
        foreach ($resultate as $resultat) {
            if ($resultat->getSeitenzahl() != $this->derzeitigeSeite) {
                $this->derzeitigeSeite = $resultat->getSeitenzahl();
            }
            if ($merkerSuchbegriffName != $resultat->getSuchbegriff()) {
                $merkerSuchbegriffName = $resultat->getSuchbegriff();
                $this->setPosition(true);
                foreach ($suchbegriffe as $suchbegriff) {
                    if ($suchbegriff->getSuchbegriff() == $merkerSuchbegriffName) {
                        $merkerSuchbegriff = $suchbegriff;
                    }
                }
//                $anzahlErgebnisse = $this->holeAnzahlErgebnisse($resultat->getSeite());
            }

            $eintraege = $this->holeEintraege($resultat->getSeite(), $domaenen);
            foreach ($eintraege as $eintrag) {
                $pageRankArray[] = $this->holeEintragWerte($eintrag, $this->getSuchmaschineID(),
                                                           $merkerSuchbegriff, $domaenen, $anzahlErgebnisse,
                                                           $benutzerID);
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
     * Gibt es nicht bei DuckDuckGo
     * @param string $seite
     * @return int
     */
    public function holeAnzahlErgebnisse($seite) {
//        preg_match($this->regexAnzahlErgebnisse, $seite, $ergebnisAnzahl);
//        $anzahlErgebnisse = str_replace(".", "", $ergebnisAnzahl[4]);
        $anzahlErgebnisse = 0;
        return (int)$anzahlErgebnisse;
    }


    //neu
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
            $this->setPosition(false);
            preg_match($this->regexDomaene, $eintraege[1][$key], $eintragDomaene);
            if ($this->isPosition($eintragDomaene[2], $domaenen) || $this->isPosition($eintragDomaene[4], $domaenen)) {
                $alleEintraege[] = new Eintrag($eintraege[1][$key], $this->getPosition());
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
        preg_match($this->regexDomaene, $eintrag->getEintrag(), $domaene);

        /**@var $dieDomaene Domaene*/
        foreach ($domaenen as $dieDomaene) {
            if ($dieDomaene->getUrl() === $domaene[2] || $dieDomaene->getUrl() === $domaene[4]) {
                $extra = 0;
                if ($this->derzeitigeSeite > 0) {
                    $extra = 24;
                    if ($this->derzeitigeSeite > 1) {
                        $extra += 31 * ($this->derzeitigeSeite - 1);
                    }
                }
                return new PageRank(null, $dieDomaene, $this->suchmaschineID, $suchmaschineGefundenID, $suchbegriff, $benutzerID, $eintrag->getPosition(), "gefunden", $anzahlErgebnisse, null);
            }
        }

        return null;
    }

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
            if ($domaene->getUrl() === $eintragDomaene) {
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

        return new PageRank(null, $domaene, $this->suchmaschineID, $suchmaschineGefundenID, $suchbegriff, $benutzerID, 0, "nicht gefunden", $anzahlErgebnisse, null);
    }
}

?>