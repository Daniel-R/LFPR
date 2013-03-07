<?php
/**
 * Erstellung:              29.11.12
 * Autor:                   Daniel Reichelt, mydata GmbH
 * Beschreibung:
 */

class Parameter extends Eloquent {

    public static $table = 'parameter';
    public static $accessible = array('label');
//    public static $timestamps = false;

    public function parameterValue() {
        return $this->has_many_and_belongs_to('Searchengine', 'ParameterValue', 'parameterID', 'searchengineID');
    }
}
?>