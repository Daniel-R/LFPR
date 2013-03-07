<?php
/**
 * Created by IntelliJ IDEA.
 * User: Daniel Reichelt, myData GmbH
 * Date: 05.06.12
 * Time: 16:04
 */

interface IParser {

    /**
     * @return string
     */
    public function getRegexEintrag();

    /**
     * @return string
     */
    public function getRegexSeitenzahl();

    /**
     * @return string
     */
    public function getRegexPosition();

    /**
     * @return string
     */
    public function getRegexAnzahlErgebnisse();

    /**
     * @return string
     */
    public function getRegexSuchbegriff();

    /**
     * @return string
     */
    public function getRegexDomaene();

    /**
     * @return int
     */
    public function getSuchmaschineID();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getSuchmaschineUrl();

    /**
     * @return int
     */
    public function getPositionenJeSeite();

    /**
     * @return Parameter
     */
    public function getParameter();

    /**
     * Gibt das Ergebnis der Suche als array mit Resultat.
     * @param array $suchbegriffe array(Suchbergiffe)
     * @param int $pruefTiefe
     * @return array array(Resultat)
     */
    public function holeAlleResultate(array $suchbegriffe, $pruefTiefe);

    /**
     * Gibt das Resultat einer Suche mit der Suchmaschine zurück
     * @param Suchbegriff $suchbegriff
     * @param int $webseitenIncrement
     * @return string
     */
    public function holeResultat($suchbegriff, $webseitenIncrement);

    /**
     * Gibt die Parameter der Suchmaschine formatiert zurück
     * Unterschiede je nach Suchmaschine
     * @return string
     */
    public function holeSuchParameter();

    /**
     * Gibt das Ergebnis der abgeschlossenen Suche als array mit PageRank zurück
     * @param array $suchbegriffe array(Suchbegriff)
     * @param array $domaenen array(Domaene)
     * @param array $resultate array(Resultat)
     * @param int $benutzerID
     * @return array array(PageRank)
     */
    public function holePageRanks(array $suchbegriffe, array $domaenen, array $resultate, $benutzerID);

    /**
     * Entnimmt Anzahl an Gesammtenergebnissen für eine Suche, sofern vorhanden
     * @param string $seite
     * @return int
     */
    public function holeAnzahlErgebnisse($seite);

    /**
     * Entnimmt einzelne Sucheinträge aus einem Webseitenresultat
     * Abhängig von der Suchmaschine wird der Eintrag unterschiedlich validiert
     * @param string $resultat
     * @param array $domaenen array(mixed)
     * @return array array(Eintrag)
     */
    public function holeEintraege($resultat, array $domaenen);

    /**
     *Entnimmt dem Eintrag die gewuchten Werte und gibt den fertigen PageRank zurück
     * @param Eintrag $eintrag
     * @param int $suchmaschineGefundenID
     * @param int $suchbegriff
     * @param array $domaenen array(Domaene)
     * @param int $anzahlErgebnisse
     * @param int $benutzerID
     * @return PageRank
     */
    public function holeEintragWerte($eintrag, $suchmaschineGefundenID, $suchbegriff, array $domaenen, $anzahlErgebnisse, $benutzerID);

    /**
     * prüft ob eine Position ist, wenn ja muss anhand des Eintrages die Position noch rausgesucht werden -> design fehler?
     * @param string $eintragDomaene
     * @param array $domaenen array(string)
     * @return bool
     */
    public function isPosition($eintragDomaene, array $domaenen);

    /**
     * Überprüft ob die übergebennen Domaenen in den PageRanks enthalten sind.
     * Im Anschluss kommt PageRanks mit eventuell fehlenden Domaenen als leerer Eintrag zurück.
     * @param array $pageRanks array(PageRanks)
     * @param Domaene $domaene
     * @param int $suchmaschineGefundenID
     * @param Suchbegriff $suchbegriff
     * @param int $anzahlErgebnisse
     * @param int $benutzerID
     * @return bool
     */
    public function holeFehlendeDomaene(array $pageRanks, $domaene, $suchmaschineGefundenID, $suchbegriff, $anzahlErgebnisse, $benutzerID);

}

?>