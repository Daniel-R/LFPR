<?php
/**
 * Erstellung:              15.02.13
 * Autor:                   Daniel Reichelt, mydata GmbH
 * Beschreibung:
 */
require_once('application/models/Parameter.php');
require_once('application/models/Suchbegriff.php');
require_once('application/models/Domaene.php');
require_once('application/models/GoogleDEParser.php');

class GoogleDETest extends PHPUnit_Framework_TestCase {
    //    public function testFetchDomaeneByIDsTest() {
    //        $this->assertClassHasAttribute('foo', 'stdClass');
    //        $this->assertArrayHasKey('foo', array('bar' => 'baz'));
    //        $this->assertClassHasStaticAttribute('foo', 'stdClass');
    //        $this->assertContains(4, array(1, 2, 3));
    //        $this->assertContains('baz', 'foobar');
    //    }
    //
    //    public function test_falseIfNoAtSign() {
    //        $actual = Verify::checkEmail('manuel.kiessling.net');
    //        $this->assertFalse($actual);
    //    }

    //    public function test_fetchPageRankByIDs() {
    //        $domainDAO = new DomainDAO();
    ////        $false = false;
    ////        $false = $domainDAO->fetchDomainsByIDs(array(1, 2));
    //        $this->assertFalse(false);
    //    }

//$this->eintraegeA = array(new Eintrag('<a href="http://www.selfphp.info/praxisbuch/praxisbuchseite.php?site=263&amp;group=45" class=l onmousedown="return rwt(this,\'\',\'\',\'\',\'6\',\'AFQjCNEAB4Iktd7hnhITQkQxQnm9exPMqA\',\'\',\'0CGEQFjAF\',null,event)"><em>MySQLi</em> – erste Gehversuche</a></h3><div class="s"><div class="f kv"><cite>www.selfphp.info/praxisbuch/praxisbuchseite.php?site=263...45</cite><span class="vshid"><a href="http://webcache.googleusercontent.com/search?q=cache:JAZMInAHaOUJ:www.selfphp.info/praxisbuch/praxisbuchseite.php%3Fsite%3D263%26group%3D45+mysqli&amp;cd=6&amp;hl=de&amp;ct=clnk" onmousedown="return rwt(this,\'\',\'\',\'\',\'6\',\'AFQjCNHafmKfz3xO0KQSnmWb9ZmwI0Hirg\',\'\',\'0CCcQIDAF\',null,event)">Im&nbsp;Cache</a>&nbsp;-&nbsp;<a href="/search?hl=de&amp;q=related:www.selfphp.info/praxisbuch/praxisbuchseite.php%3Fsite%3D263%26group%3D45+mysqli&amp;tbo=1&amp;sa=X&amp;ei=UYUaULrvBoj0sgbs14C4DA&amp;ved=0CCgQHzAF">Ähnliche Seiten</a></span></div><div class="esc slp" id="poS5" style="display:none">Sie geben hierfür öffentlich +1.&nbsp;<a href="#" class="fl">Rückgängig machen</a></div><span class="st">Das Praxisbuch zu SELFPHP - <em>MySQLi</em> – erste Gehversuche - SELFPHP.<br></span></div></div>', null),
//                          new Eintrag('<a href="http://www.selfphp.info/praxisbuch/praxisbuchseite.php?site=262&amp;group=45" class=l onmousedown="return rwt(this,\'\',\'\',\'\',\'7\',\'AFQjCNHnMB9qkX58o_4nn7GG21EVTxIBzA\',\'\',\'0CGIQFjAG\',null,event)"><em>MySQLi</em>-Installation</a></h3><div class="s"><div class="f kv"><cite>www.selfphp.info/praxisbuch/praxisbuchseite.php?site=262...45</cite><span class="vshid"><a href="http://webcache.googleusercontent.com/search?q=cache:Zdh_XfFbUqwJ:www.selfphp.info/praxisbuch/praxisbuchseite.php%3Fsite%3D262%26group%3D45+mysqli&amp;cd=7&amp;hl=de&amp;ct=clnk" onmousedown="return rwt(this,\'\',\'\',\'\',\'7\',\'AFQjCNFZiMnh5SJN-ntpdTozOy2jiQ-ftA\',\'\',\'0CC0QIDAG\',null,event)">Im&nbsp;Cache</a>&nbsp;-&nbsp;<a href="/search?hl=de&amp;q=related:www.selfphp.info/praxisbuch/praxisbuchseite.php%3Fsite%3D262%26group%3D45+mysqli&amp;tbo=1&amp;sa=X&amp;ei=UYUaULrvBoj0sgbs14C4DA&amp;ved=0CC4QHzAG">Ähnliche Seiten</a></span></div><div class="esc slp" id="poS6" style="display:none">Sie geben hierfür öffentlich +1.&nbsp;<a href="#" class="fl">Rückgängig machen</a></div><span class="st">Das Praxisbuch zu SELFPHP - <em>MySQLi</em>-Installation - SELFPHP.<br></span></div></div>', null),
//                          new Eintrag('<a href="http://www.selfphp.info/praxisbuch/praxisbuchseite.php?site=264&amp;group=45" class=l onmousedown="return rwt(this,\'\',\'\',\'\',\'8\',\'AFQjCNGtg4lIK6vdQIAugRQt5Ayr2XJQiw\',\'\',\'0CGMQFjAH\',null,event)"><em>MySQLi</em> und SQL-Abfragen: Seite 1</a></h3><div class="s"><div class="f kv"><cite>www.selfphp.info/praxisbuch/praxisbuchseite.php?site=264...45</cite><span class="vshid"><a href="http://webcache.googleusercontent.com/search?q=cache:w2xXSOiFO9QJ:www.selfphp.info/praxisbuch/praxisbuchseite.php%3Fsite%3D264%26group%3D45+mysqli&amp;cd=8&amp;hl=de&amp;ct=clnk" onmousedown="return rwt(this,\'\',\'\',\'\',\'8\',\'AFQjCNEJLrDQAL8U_YD-HhIQj4qgE_jS_A\',\'\',\'0CDMQIDAH\',null,event)">Im&nbsp;Cache</a>&nbsp;-&nbsp;<a href="/search?hl=de&amp;q=related:www.selfphp.info/praxisbuch/praxisbuchseite.php%3Fsite%3D264%26group%3D45+mysqli&amp;tbo=1&amp;sa=X&amp;ei=UYUaULrvBoj0sgbs14C4DA&amp;ved=0CDQQHzAH">Ähnliche Seiten</a></span></div><div class="esc slp" id="poS7" style="display:none">Sie geben hierfür öffentlich +1.&nbsp;<a href="#" class="fl">Rückgängig machen</a></div><span class="st">Das Praxisbuch zu SELFPHP - <em>MySQLi</em> und SQL-Abfragen - SELFPHP.<br></span></div></div><div id="mbf8"><span></span></div>', null));
//$this->eintraegeB = array(new Eintrag('<a href="http://www.strassenprogrammierer.de/php-5-mysql-mit-mysqli-nutzen_tipp_328.html" class=l onmousedown="return rwt(this,\'\',\'\',\'\',\'19\',\'AFQjCNHztVJlh3kW3RlcX1gwMWtDtHoEZQ\',\'\',\'0CG8QFjAIOAo\',null,event)">PHP 5: MySQL mit <em>mysqli</em> nutzen</a></h3><div class="s"><div class="f kv"><cite><span class=bc>www.strassenprogrammierer.de &rsaquo; <a href="/url?url=http://www.strassenprogrammierer.de/PHP-kw.html&amp;rct=j&amp;sa=X&amp;ei=UYUaUIi5EobNswaDjIGwBQ&amp;ved=0CHAQ6QUoADAIOAo&amp;q=mysqli&amp;usg=AFQjCNFCGie_mBJ6l3agVLU3kUVIWph3Dw">PHP</a></span></cite><span class="vshid"><a href="http://webcache.googleusercontent.com/search?q=cache:rEVmA8wg7kQJ:www.strassenprogrammierer.de/php-5-mysql-mit-mysqli-nutzen_tipp_328.html+mysqli&amp;cd=19&amp;hl=de&amp;ct=clnk" onmousedown="return rwt(this,\'\',\'\',\'\',\'19\',\'AFQjCNGbFZYOkt0MbkTIV1u5mvO38J22CQ\',\'\',\'0CDgQIDAIOAo\',null,event)">Im&nbsp;Cache</a>&nbsp;-&nbsp;<a href="/search?hl=de&amp;q=related:www.strassenprogrammierer.de/php-5-mysql-mit-mysqli-nutzen_tipp_328.html+mysqli&amp;tbo=1&amp;sa=X&amp;ei=UYUaUIi5EobNswaDjIGwBQ&amp;ved=0CDkQHzAIOAo">Ähnliche Seiten</a></span></div><div class="esc slp" id="poS18" style="display:none">Sie geben hierfür öffentlich +1.&nbsp;<a href="#" class="fl">Rückgängig machen</a></div><span class="st">PHP 5: MySQL mit <em>mysqli</em> nutzen: Mit <em>mysqli</em> wurde eine aufgeräumte ganz neue Funktionssammlung entwickelt. Das neue <em>mysqli</em> lehnt sich zwar an die <b>...</b><br></span></div></div>', null));

    public function test_vollständigeSuche() {
        //               -----Startwerte-----
        $suchmaschinenID  = 1;
        $anzahlErgebnisse = 729000;
        $userID = 1;
        $pruefTiefe = 20;
        $suchbegriffe     = array(new Suchbegriff(1, 'mysqli'));
        $domaenen = array(new Domaene(1, "selfPHP", "selfphp.info"),
                          new Domaene(2, "strassenprogrammierer", "strassenprogrammierer.de"),
                          new Domaene(3, "fehlendeDomaene", "www.fehlt.de"));
        $parameterWerte = array(new Parameter(1, 'hl', 'de'),
                                new Parameter(2, 'q', '%s'),
                                new Parameter(3, 'start', '%s'));
        $googleDEParser = New GoogleDEParser(1, 'GoogleDE', 'www.google.de', 10, $parameterWerte);
        $resultate  = array(new Resultat(file_get_contents('application/tests/lib/GoogleDETestSeiteA.html'), $suchbegriffe[0]->getSuchbegriff(), 0),
                            new Resultat(file_get_contents('application/tests/lib/GoogleDETestSeiteB.html'), $suchbegriffe[0]->getSuchbegriff(), 1));

        $pageRanksA = array(new PageRank(null, $domaenen[0], $suchmaschinenID, $suchmaschinenID, $suchbegriffe[0], $userID, 6, "gefunden", $anzahlErgebnisse, null),
                            new PageRank(null, $domaenen[0], $suchmaschinenID, $suchmaschinenID, $suchbegriffe[0], $userID, 7, "gefunden", $anzahlErgebnisse, null),
                            new PageRank(null, $domaenen[0], $suchmaschinenID, $suchmaschinenID, $suchbegriffe[0], $userID, 8, "gefunden", $anzahlErgebnisse, null));
        $pageRanksB = array(new PageRank(null, $domaenen[1], $suchmaschinenID, $suchmaschinenID, $suchbegriffe[0], $userID, 19, "gefunden", $anzahlErgebnisse, null));
        $pageRanks  = array();

        $pageRanks = $pageRanksA;
        foreach($pageRanksB as $pageRank) {
            $pageRanks[] = $pageRank;
        }

        //               -----Testlogik-----
        $resultate = $googleDEParser->holeAlleResultate($suchbegriffe, $pruefTiefe, $resultate);
//        var_dump(count($resultate));
        $ergebnisse = $googleDEParser->holePageRanks($suchbegriffe, $domaenen, $resultate, $userID);
        var_dump($ergebnisse);

        //               -----Testlogik-----

        //hier müssen die asserts beginnen und die ergebnisse testen im vergleich mit erwartetem
    }


//    public function test_holeAlleResultate() {
//        //-------stop-------
//    }
//
//
//    public function test_holeResultat() {
//        //($suchbegriff, $webseitenIncrement) return resultate
//    }
//
//    public function test_holeSuchParameter() {
//        //-------stop-------
//    }
//
//    /**
//     * @param Pageranks $_pageRanks
//     * @param array $_expected array(string)
//     */
//    private function tryPageRankContent($_pageRanks, $_expected) {
//        $id                  = $_pageRanks->id;
//        $domainID            = $_pageRanks->domainid;
//        $searchengineID      = $_pageRanks->searchengineid;
//        $searchengineFoundID = $_pageRanks->searchenginefoundid;
//        $searchTermID        = $_pageRanks->searchtermid;
//        $userID              = $_pageRanks->userid;
//        $position            = $_pageRanks->position;
//        $foundURL            = $_pageRanks->foundurl;
//        $ammountResults      = $_pageRanks->ammountresults;
//        $resultDepth         = $_pageRanks->resultdepth;
//        $date                = $_pageRanks->date;
//
//        $this->assertStringStartsWith($_expected['id'], $id);
//        $this->assertStringStartsWith($_expected['domainID'], $domainID);
//        $this->assertStringStartsWith($_expected['searchengineID'], $searchengineID);
//        $this->assertStringStartsWith($_expected['searchengineFoundID'], $searchengineFoundID);
//        $this->assertStringStartsWith($_expected['searchTermID'], $searchTermID);
//        $this->assertStringStartsWith($_expected['userID'], $userID);
//        $this->assertStringStartsWith($_expected['position'], $position);
//        $this->assertStringStartsWith($_expected['foundURL'], $foundURL);
//        $this->assertStringStartsWith($_expected['ammountResults'], $ammountResults);
//        $this->assertStringStartsWith($_expected['resultDepth'], $resultDepth);
//        $this->assertStringStartsWith($_expected['date'], $date);
//    }

//    private function  test() {
//        SELECT `searchengine`.*,
//        `parameterWerte`.`id` AS `pivot_id`,
//        `parameterWerte`.`parameterID` AS `pivot_parameterID`,
//        `parameterWerte`.`searchengineID` AS `pivot_searchengineID`
//        FROM `searchengine`
//        INNER JOIN `parameterWerte` ON `searchengine`.`id` = `parameterWerte`.`searchengineID`
//        WHERE `parameterWerte`.`parameterID` = ? AND `id` = ?
//        LIMIT 1
//    }
}

?>