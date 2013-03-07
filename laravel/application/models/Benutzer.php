<?php
/**
 * Erstellung:              21.08.12
 * Autor:                   Daniel Reichelt, mydata GmbH
 * Beschreibung:
 */

class Benutzer {
    private $id = 0;
    private $gruppeID = 0;
    private $name = "";
    private $email = "";
    private $loginname = "";
    private $password = "";
    private $administrator = false;
    private $lastLogin = "";

    /**
     * @param null|int $id
     * @param int $gruppeID
     * @param string $name
     * @param string $email
     * @param string $loginname
     * @param string $password
     * @param bool $administrator
     * @param null|int $lastLogin
     */
    public function __construct($id = null, $gruppeID, $name, $email, $loginname, $password, $administrator, $lastLogin = null) {
        if (is_null($id)) {
            $this->id = $id;
        }
        else {
            $this->id = (int)$id;
        }

        $this->gruppeID  = (int)$gruppeID;
        $this->name      = (string)$name;
        $this->email     = (string)$email;
        $this->loginname = (string)$loginname;

        if (is_null($id)) {
            $this->password = md5($password);
        }
        else {
            $this->password = (string)$password;
        }

        $this->administrator = (bool)$administrator;

        if (is_null($lastLogin)) {
            $datum           = new DateTime();
            $this->lastLogin = $datum->format('Y-m-d'); // G:i:s
        }
        else {
            $this->lastLogin = (string)$lastLogin;
        }
    }

    /**
     * @return int
     */
    public function getID() {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getGruppeID() {
        return $this->gruppeID;
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
    public function getEmail() {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getLoginname() {
        return $this->loginname;
    }

    /**
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @return bool
     */
    public function getAdministrator() {
        return $this->administrator;
    }

    /**
     * @return string
     */
    public function getLastLogin() {
        return $this->lastLogin;
    }

    /**
     * @param int $id
     */
    public function setID($id) {
        $this->id = (int)$id;
    }

    /**
     * @param string $name
     */
    public function setName($name) {
        $this->name = (string)$name;
    }

    /**
     * @param string $email
     */
    public function setEmail($email) {
        $this->email = (string)$email;
    }

    /**
     * @param string $loginname
     */
    public function setLoginname($loginname) {
        $this->loginname = (string)$loginname;
    }

    /**
     * @param string $password
     */
    public function setPassword($password) {
        $this->id = md5($password);
    }

    /**
     * @param bool $administrator
     */
    public function setAdministrator($administrator) {
        $this->administrator = (bool)$administrator;
    }

    /**
     * @param int $lastLogin
     */
    public function setlastLogin($lastLogin) {
        $this->lastLogin = (int)$lastLogin;
    }
}

?>