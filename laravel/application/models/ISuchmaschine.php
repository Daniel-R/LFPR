<?php
/**
 * Created by IntelliJ IDEA.
 * User: Daniel Reichelt, myData GmbH
 * Date: 05.06.12
 * Time: 16:03
 */

interface ISuchmaschine {

    /**
     * @return int
     */
    public function getID();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @return int
     */
    public function getPositionenJeSeite();

    /**
     * @return array array(Parameter)
     */
    public function getParameter();
}

?>