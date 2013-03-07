<?php
/**
 * Erstellung:              13.12.12
 * Autor:                   Daniel Reichelt, mydata GmbH
 * Beschreibung:
 */

class UserSession {
    public function __construct() {
    }

    private function isPost($_name, $_password) {
        if (isset($_POST['userName']) && isset($_POST['password'])) {
            if ($_POST['userName'] != "" && $_POST['password'] != "") {
                return true;
            }
        }

        return false;
    }

    private function loginUser($_name, $_password) {
        $userDAO = new UserDAO();

        /*@var $benutzer Benutzer*/
        $user = $userDAO->fetchUserByLoginPW($_name, $_password);

        session_start();
        if ($benutzer->getID() != 0) {
            $_SESSION['userID']     = $user->getID();
            $_SESSION['name']       = $user->getLoginname();
            $_SESSION['categoryID'] = $user->getCategoryID();

        }

    }

    /**
     * @param $_userName
     * @param $_password
     */
    public function session($_name, $_password) {
        if ($this->isPost($_name, $_password)) {
            $this->loginUser($_name, $_password);

        }
    }

    public function isAuthorised() {
        session_start();

        if (isset($_SESSION["name"])) {
            if ($_SESSION["gruppe"] == BenutzerGruppeID::ADMINISTRATOR || $_SESSION["gruppe"] == BenutzerGruppeID::BENUTZER) {
                return true;
            }
            //        if ($_SESSION['gruppe'] == BenutzerGruppeID::BENUTZER) {
            //            return true;
            //        }
            //
            //        if ($_SESSION['gruppe'] == BenutzerGruppeID::ADMINISTRATOR) {
            //            return true;
            //        }
        }
        return false;
    }
}

?>