<?php
/**
 * Erstellung:              20.11.12
 * Autor:                   Daniel Reichelt, mydata GmbH
 * Beschreibung:
 */

class Category extends Eloquent {

    public static $table = 'category';
    public static $accessible = array('name', 'created_at', 'updated_at');

    public function user() {
        return $this->has_many('user', 'categoryID');
    }
}
?>