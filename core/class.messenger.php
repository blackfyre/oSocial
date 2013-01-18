<?php
/**
 * User: galiczmiklos
 * Date: 2013.01.18.
 * Time: 13:09
 */
class messenger
{
    private $db = null;
    private $core = null;
    private $error = null;
    private $var = null;
    private $form = null;

    function __construct()
    {
        $dbInstance = database::getInstance();
        $this->db = $dbInstance->getConnection();
        $this->core = new coreFunctions();
        $this->error = new errorHandler();
        $this->var = new varGetter();
        $this->form = new form();
    }

    /**
     * A beérkezett levelek beszerzése
     * @return array|bool|null
     */
    private function getRecievedMessages()
    {
        $uid = $this->var->getUserID();

        $query = "SELECT *,
         (
         SELECT CONCAT(firstName, ' ', lastName)
         FROM user_details WHERE messages.sender=user_details.uid
         ) AS name
         FROM messages WHERE recipient='$uid'";

        if ($result = $this->db->query($query)) {

            $r = null;

            while ($row = $result->fetch()) {
                $r[] = $row;
            }

            return $r;

        } else {
            $this->error->queryError();
            return false;
        }
    }

    private function getSentMessages()
    {
        $uid = $this->var->getUserID();

        $query = "SELECT *,
         (
         SELECT CONCAT(firstName, ' ', lastName)
         FROM user_details
         WHERE messages.recipient=user_details.uid
         ) AS name
         FROM messages WHERE sender='$uid'";

        if ($result = $this->db->query($query)) {

            $r = null;

            while ($row = $result->fetch()) {
                $r[] = $row;
            }

            return $r;

        } else {
            $this->error->queryError();
            return false;
        }
    }

    /**
     * @param array $data
     * @return bool|string
     */
    function renderTable($data = null)
    {
        if (is_array($data)) {

            $r = null;

            $r .= '<table class="messageTable">';
            $r .= ' <thead>';
            $r .= '     <tr>';
            $r .= '         <th>';
            $r .= gettext('EMAIL_NAME');
            $r .= '         </th>';
            $r .= '         <th>';
            $r .= gettext('EMAIL_BODY');
            $r .= '         </th>';
            $r .= '     </tr>';
            $r .= ' </thead>';
            $r .= ' <tbody>';

            foreach ($data as $row) {
                $r .= '     <tr>';
                $r .= '         <td>';
                $r .= $row['name'];
                $r .= '         </td>';
                $r .= '         <td>';
                $r .= $row['body'];
                $r .= '         </td>';
                $r .= '     </tr>';
            }

            $r .= ' </tbody>';
            $r .= '</table>';

            return $r;

        } elseif (is_null($data)) {
            return false;
        } else {
            $this->error->isNotArrayError();
            return false;
        }
    }

    function renderMessages()
    {

        $sent = $this->getSentMessages();
        $inbox = $this->getRecievedMessages();

        $r = null;

        $r .= $this->renderEmailForm();

        $r .= '<div id="tabs">';
        $r .= ' <ul>';

        if (!is_null($inbox) AND !is_bool($inbox)) {
            $r .= '     <li><a href="#tabs-1">' . gettext('INBOX') . '</a></li>';
        }

        if (!is_null($sent) AND !is_bool($sent)) {
            $r .= '     <li><a href="#tabs-2">' . gettext('SENT') . '</a></li>';
        }


        $r .= ' </ul>';

        if (!is_null($inbox) AND !is_bool($inbox)) {
            $r .= ' <div id="tabs-1">';
            $r .= $this->renderTable($inbox);
            $r .= ' </div>';
        }

        if (!is_null($sent) AND !is_bool($sent)) {
            $r .= ' <div id="tabs-2">';
            $r .= $this->renderTable($sent);
            $r .= ' </div>';
        }


        $r .= '</div>';

        $r .= '
         <script>
            $(function() {
                $( "#tabs" ).tabs();
            });
        </script>
        ';

        return $r;
    }

    /**
     * @return array|bool|null
     */
    private function getFriends()
    {
        $uid = $this->var->getUserID();
        $query = "SELECT init, target,
            (SELECT userName FROM user_base WHERE user_base.id=user_connection.init) AS initUser,
            (SELECT firstName FROM user_details WHERE user_details.id=user_connection.init) AS initFirstName,
            (SELECT lastName FROM user_details WHERE user_details.id=user_connection.init) AS initLastName,
             (SELECT userName FROM user_base WHERE user_base.id=user_connection.target) AS targetUser,
             (SELECT firstName FROM user_details WHERE user_details.id=user_connection.target) AS targetFirstName,
            (SELECT lastName FROM user_details WHERE user_details.id=user_connection.target) AS targetLastName
            FROM user_connection WHERE (init='$uid' OR target='$uid') AND state='1'";

        if ($result = $this->db->query($query)) {

            $temp = null;

            while ($row = $result->fetch()) {
                if ($row['init'] != $uid AND $row['target'] == $uid) {
                    $temp[] = $row;
                } elseif ($row['target'] != $uid AND $row['init'] == $uid) {
                    $temp[] = $row;
                } else {

                }
            }

            return $temp;

        } else {
            $this->error->queryError();
            return false;
        }
    }

    private function renderSelectOptions()
    {
        $data = $this->getFriends();

        $uid = $this->var->getUserID();

        $r = null;

        foreach ($data as $row) {
            if ($row['init'] == $uid) {
                $r[] = $row['target'] . '||' . $row['targetFirstName'] . ' ' . $row['targetLastName'];
            } else {
                $r[] = $row['init'] . '||' . $row['initFirstName'] . ' ' . $row['initLastName'];
            }

        }

        $r = '-1||' . gettext('MESSAGE_ALL') . '|;|' . implode('|;|', $r);
        return $r;

    }

    private function renderEmailForm()
    {
        $form = array(
            array('type' => 'select', 'name' => 'target', 'value' => $this->renderSelectOptions(), 'placeH' => gettext('EMAIL_RECIPIENT')),
            array('type' => 'textArea', 'name' => 'body', 'value' => '', 'placeH' => gettext('EMAIL_BODY'))
        );

        return $this->form->generateForm(gettext('EMAIL_WRITE'), 'email', $form);
    }

    function processMessage()
    {
        $data = $this->form->validator();

        var_dump($data);

        var_dump(!$this->form->checkForErrors($data));

        if (!$this->form->checkForErrors($data)) {

            //var_dump($data);

            $from = $this->var->getUserID();
            $to = $data['target'];
            $body = $data['body'];

            $query = "INSERT INTO messages (sender, recipient, body) VALUES ('$from', '$to', '$body')";

            if ($this->db->query($query)) {
                return true;
            } else {
                $this->error->queryError();
                return false;
            }

        } else {
            return false;
        }
    }

}
