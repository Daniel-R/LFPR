<?php
/**
 * Erstellung:              14.06.12
 * Autor:                   Daniel Reichelt, mydata GmbH
 * Beschreibung:
 */

class Verbindung {

    /**
     * @var mysqli
     */
    static private $mysqli = null;

    /**
     * Singleton Klasse, als Constructor dient initMySQLi()
     */
    public function __construct() {
    }

    /**
     * singleton contructor
     * Öffnet die Verbindung mit der Datenbank
     * @static
     * @param $host
     * @param $username
     * @param $password
     * @param $database
     */
    public static function initMySQLi($host, $username, $password, $database) {
        self::$mysqli = new mysqli($host, $username, $password, $database); //@ ignoriert erstmal den error, er ist jedoch weiterhin da und auslesbar
        if (self::$mysqli->connect_errno) {
            echo "Failed to connect to connect to MySQL \nGrund des Fehlers ist: " . self::$mysqli->connect_errno . "\n";
        }
    }

    /**
     * @static
     * @return mysqli
     */
    public static function getMySQLi() {
        return self::$mysqli;
    }

    /**
     * Beendet die Verbindung die Verbindung mit der Datenbank
     * @static
     */
    public static function schliesseMySQLi() { //geht auch closeMySQLi
        self::$mysqli->close();
    }
}

?>