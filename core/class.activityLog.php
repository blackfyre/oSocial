<?php
/**
 * Created by Galicz Miklós.
 * BlackFyre Studio
 * http://blackworks.org
 * galicz.miklos@blackworks.org
 *
 * Date: 2013.01.17.
 * Time: 18:52
 *
 * @package oSocial
 * @subpacakage activityLog
 */
class activityLog
{
    private $db = null;
    private $core = null;
    private $var = null;

    function __construct()
    {

        $dbi = database::getInstance();
        $this->db = $dbi->getConnection();

        $this->core = new coreFunctions();
        $this->var = new varGetter();
    }


    /**
     * Naplózza az aktuális felhasználó bejelentkezését
     * @return bool
     */
    function logLogin()
    {

        $uid = $this->var->getUserID();

        $query = "INSERT INTO _log (uid,login) VALUES ('$uid',1)";

        if ($this->db->query($query)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Naplózza az aktuális felhasználó kilépését
     * @return bool
     */
    function logLogout()
    {

        $uid = $this->var->getUserID();

        $query = "INSERT INTO _log (uid,login) VALUES ('$uid',0)";

        if ($this->db->query($query)) {
            return true;
        } else {
            return false;
        }
    }
}
