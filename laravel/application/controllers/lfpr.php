<?php
/**
 * Erstellung:              05.12.12
 * Autor:                   Daniel Reichelt, mydata GmbH
 * Beschreibung:            View Controller für das LFPR Programm
 */

require_once("application/controllers/phpCommands/user_session.php");
require_once('application/models/dao/DomaeneDAO.php');
require_once('application/models/dao/SuchbegriffDAO.php');
require_once('application/models/dao/SuchmaschineDAO.php');


class LFPR_Controller extends Base_Controller {


    public $restful = true;

    public function get_login() {
        return View::make('lfpr.login');
    }

    public function get_logout() {
        Auth::logout();
        return View::make('lfpr.login');
    }

    public function get_search() {
        return View::make('lfpr.search');
    }

    public function post_search() {
        $name     = Input::get('userName');
        $password = Input::get('password');

        if (!Auth::attempt(array('username' => $name, 'password' => $password, 'remember' => true))) {
            return View::make('lfpr.login');
        }

        return View::make('lfpr.search')
                ->with('name', $name)
                ->with('password', $password);
    }

    public function get_searchresult() {
        return View::make('lfpr.searchresult');
    }

    public function post_searchresult() {
        $domain       = Input::get('domaenen');
        $searchterm   = Input::get('schlagworte');
        $suchmaschine = Input::get('suchmaschinen');
        $entryCount   = Input::get('entryCount');
        $pageRanks = $this->get_pageranks($domain, $searchterm, $suchmaschine, $entryCount);
        return View::make('lfpr.searchresult')
                ->with('pageRanks', $pageRanks)
                ->with('domain', $domain)
                ->with('searchterm', $domain);
    }

    public function get_archiv() {
        $pageRank = PageRank::all();
        return View::make('lfpr.archiv')->with('test', $pageRank);
    }

    public function get_details() {
        return View::make('lfpr.details');
    }

    public function get_settings() {
        return View::make('lfpr.settings');
    }


    /**
     * @param $_domain
     * @param $_searchterm
     * @param $_searchengine
     * @param $_entryCount
     */
    private function get_pageranks($_domain, $_searchterm, $_searchengineID, $_entryCount) {
        Verbindung::initMySQLi("localhost", "root", "pktpktpkt", "laravel");

        //do Domaenenkram, ergebnis ist useableDomains als fertige Objekte
        $domainDAO      = new DomaeneDAO();
        $domains        = explode(', ', $_domain); //die aufgespaleteten eingaben
        $useableDomains = array(); //für verwendung mit pagerank rdy

        foreach ($domains as $domain) {
            $testDomain = new Domaene(NULL, $domain, $domain);

            if (!$domainDAO->fetchIsDomaeneRegisteriert($testDomain)) {
                $domainDAO->speichereDomaenen(array($testDomain));
            }

            $domainDAO->setzeDomaeneIDByURL($testDomain);
            $useableDomains[] = $testDomain;
        }

//ok
        //do Suchbegriffkram, ergebnis ist useableSearchterms als fertige Objekte
        $searchtermDAO      = new SuchbegriffDAO();
        $searchterms        = explode(', ', $_searchterm); //die aufgespaleteten eingaben
        $useableSearchterms = array(); //für verwendung mit pagerank rdy

        foreach ($searchterms as $searchterm) {
            $testSearchterm = new Suchbegriff(NULL, $_searchterm);

            if (!$searchtermDAO->fetchIsSuchbegriffRegisteriert($testSearchterm)) {
                $searchtermDAO->speichereSuchbegriffe(array($testSearchterm));
            }

            $searchtermDAO->setzeSuchbegriffIDByName($testSearchterm);
            $useableSearchterms[] = $testSearchterm;
        }

        //do Suchmaschinenkram, ergebnis sind die ausgewerteten PageRanks
        $searchengineDAO = new SuchmaschineDAO();
//        foreach($_searchengineID as $id) { //derzeit wird es nicht behandelt wenn mehrere übergeben werden
        $searchengine = $searchengineDAO->fetchSuchmaschineByID($_searchengineID); // beeinhaltet eine gewählten Suchmaschinen, alle Suchmaschinen funktioniert (noch) nicht
//        }

        $searchengine->holePageRanks($useableSearchterms, $useableDomains, $_entryCount, 1);

        //do PageRankkram
        $pageRankDAO = new PageRankDAO();
        $pageRankDAO->speicherePageRanks($searchengine->getPageRanks());
        return $searchengine->getPageRanks();
    }
}

?>