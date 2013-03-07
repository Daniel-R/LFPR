<?php
/**
 * Erstellung:              29.11.12
 * Autor:                   Daniel Reichelt, mydata GmbH
 * Beschreibung:
 */

class ParameterValue extends Eloquent {

    public static $table = 'parameterValue';
    public static $accessible = array('parameterID', 'searchengineID', 'value');
//    public static $timestamps = false;
//    public static $key = array('suchmaschineID', 'parameterID');
//TODO: created_at crap ist hier nie gewesen und wird verneint, will aber gefunden werden????

    public function searchengine()
    {
        return $this->has_one('Searchengine');
    }

    public function parameter()
    {
        return $this->has_one('Parameter');
    }
}
?>