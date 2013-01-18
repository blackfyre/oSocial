<?php
/**
 * User: galiczmiklos
 * Date: 2013.01.18.
 * Time: 10:56
 *
 * @package oSocila
 * @subpackage homeStreamer
 */
class homeStream
{
    private $db = null;
    private $core = null;
    private $error = null;
    private $var = null;

    function __construct()
    {

        $dbInstance = database::getInstance();
        $this->db = $dbInstance->getConnection();

        $this->core = new coreFunctions();
        $this->error = new errorHandler();
        $this->var = new varGetter();

    }

    /**
     * Beszerzi a várólistán lévő emberek számát
     * @return bool
     */
    private function getWaiting()
    {
        $uid = $this->var->getUserID();

        $query = "SELECT COUNT(id) AS counter FROM user_connection WHERE target='$uid' AND state='0'";

        if ($result = $this->db->query($query)) {

            $r = $result->fetch();
            $r = $r['counter'];

            return $r;

        } else {
            $this->error->queryError();
            return false;
        }

    }

    function renderWaitingMessage()
    {

        $counter = $this->getWaiting();
        $r = null;


        if ($counter != 0) {
            $r .= '<p>';
            $r .= '<strong>' . $counter . '</strong> ' . gettext('WAITING_FOR_APPROVAL');
            $r .= '</p>';
        }

        return $r;

    }
}
