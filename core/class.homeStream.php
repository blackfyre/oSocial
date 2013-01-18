<?php
/**
 * User: galiczmiklos
 * Date: 2013.01.18.
 * Time: 10:56
 *
 * @package    oSocial
 * @subpackage homeStreamer
 */
class homeStream
{
    private $db = null;
    private $core = null;
    private $error = null;
    private $var = null;
    private $messenger = null;

    function __construct()
    {

        $dbInstance = database::getInstance();
        $this->db = $dbInstance->getConnection();

        $this->core = new coreFunctions();
        $this->error = new errorHandler();
        $this->var = new varGetter();
        $this->messenger = new messenger();

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

    function  getGlobalMessages()
    {
        $query = "SELECT *, (SELECT CONCAT(firstName, ' ', lastName) FROM user_details WHERE user_details.uid=messages.sender) AS name FROM messages WHERE recipient='-1' ORDER BY sentOn DESC";

        if ($result = $this->db->query($query)) {

            $data = null;

            while ($row = $result->fetch()) {
                $data[] = $row;
            }

            return $this->messenger->renderTable($data);

        } else {
            $this->error->queryError();
            return false;
        }
    }

    /**
     * @return bool|string
     */
    private function getNewMessages()
    {
        $uid = $this->var->getUserID();
        $query = "SELECT * FROM messages WHERE recipient='$uid' AND sentOn>=(SELECT eventOn FROM _log WHERE _log.uid=messages.recipient ORDER BY eventOn DESC LIMIT 1)";

        if ($result = $this->db->query($query)) {

            $count = $result->rowCount();

            $msg = gettext('YOU_HAVE_%C%_NEW_MESSAGES');
            $msg = str_replace('%C%', '<strong>' . $count . '</strong>', $msg);

            return '<p>' . $msg . '</p>';

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

        $r .= $this->getNewMessages();
        return $r;

    }
}
