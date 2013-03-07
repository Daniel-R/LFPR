<?php
/**
 * Erstellung:              28.01.13
 * Autor:                   Daniel Reichelt, mydata GmbH
 * Beschreibung:
 */

//require_once('application/controllers/dao/DomainDAO.php');
//require_once('models/domain.php');
//require_once('laravel/auth.drivers/eloquent.php');
//require_once('eloquent.php');
//require_once('laravel/asset.php');
//require_once('laravel/auth.drivers/driver.php');
//require_once('laravel/auth.drivers/fluent.php');
//require_once('laravel/database/eloquent/model.php');
//require_once('DomainDAO.php');
//require_once('../controllers/dao/DomainDAO.php');
//require_once('praktikant/Dropbox/Projekte/laravel/application/controllers/dao/DomainDao.php');
//require_once('laravel/application/controllers/dao/DomainDao.php');
//require_once('/application/controllers/dao/DomainDao.php');
//require_once('controllers/dao/DomainDao.php');


class DomainTest extends PHPUnit_Framework_TestCase {

    public function test_falseIfNoAtSign() {
        $actual = false;
        $this->assertFalse($actual);
    }

    public function test_fetchDomaeneByIDs() {
        $domainDAO = new DomainDAO();
        $false     = $domainDAO->fetchDomainsByIDs(array(1, 2));
//        $this->assertFalse(false);
    }

}

?>