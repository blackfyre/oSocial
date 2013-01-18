<?php
/**
 * User: galiczmiklos
 * Date: 2013.01.18.
 * Time: 9:01
 * @package oSocial
 * @subpackage offerFriendClass
 */

/**
 * Barát listák és ajánlások
 */
class offerFriend
{
    private $db = null;
    private $core = null;
    private $error = null;
    private $var = null;
    private $avatar = null;

    function __construct()
    {

        $dbInstance = database::getInstance();
        $this->db = $dbInstance->getConnection();
        $this->core = new coreFunctions();
        $this->error = new errorHandler();
        $this->var = new varGetter();
        $this->avatar = new profilePicture();
    }

    /**
     * Beszerzi az adott felhasználó kapcsolatait
     * Ha nincs $uid megadva akkor a $_SESSION felhasználó alapján tölti be az adatokat
     * @param null $uid
     * @return array|bool|null
     */
    private function getConnections($uid = null)
    {
        if (is_null($uid)) {
            $uid = $this->var->getUserID();
        }

        if (is_numeric($uid)) {

            // TODO: limitálni a lekért oszlopokat

            $query = "
            SELECT *,
            (SELECT userName FROM user_base WHERE user_base.id=user_connection.init) AS initUser,
            (SELECT firstName FROM user_details WHERE user_details.id=user_connection.init) AS initFirstName,
            (SELECT lastName FROM user_details WHERE user_details.id=user_connection.init) AS initLastName
             FROM user_connection
            LEFT JOIN user_details ON user_details.uid=user_connection.target
            LEFT JOIN user_base ON user_connection.target=user_base.id
            WHERE (init='$uid' AND state!='0') OR target='$uid'
            ORDER BY state, userName
            ";

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

        } else {
            $this->error->isNotRequiredVariableType();
            return false;
        }
    }

    /**
     * Táblázat megjelenítő
     * @param null $data
     * @param bool $personal
     * @return bool|string
     */
    private function renderTable($data = null)
    {
        if (is_array($data)) {

            $uid = $this->var->getUserID();

            $r = null;

            $r .= '<table class="connectionTable">';
            $r .= ' <thead>';
            $r .= '     <tr>';
            $r .= '         <th>';
            $r .= '         </th>';
            $r .= '         <th>';
            $r .= gettext('LIST_NAME');
            $r .= '         </th>';
            $r .= '         <th>';
            $r .= gettext('LIST_EMAIL');
            $r .= '         </th>';
            $r .= '         <th>';
            $r .= '         </th>';
            $r .= '     </tr>';
            $r .= ' </thead>';
            $r .= ' <tfoot>';
            $r .= '     <tr>';
            $r .= '         <th>';
            $r .= '         </th>';
            $r .= '         <th>';
            $r .= gettext('LIST_NAME');
            $r .= '         </th>';
            $r .= '         <th>';
            $r .= gettext('LIST_EMAIL');
            $r .= '         </th>';
            $r .= '         <th>';
            $r .= '         </th>';
            $r .= '     </tr>';
            $r .= ' </tfoot>';
            $r .= ' <tbody>';

            foreach ($data as $row) {


                if ($row['init'] == $uid) {
                    $initer = true;
                } else {
                    $initer = false;
                }

                if ($row['target'] == $uid) {
                    $targeted = true;
                } else {
                    $targeted = false;
                }


                $r .= '     <tr>';
                $r .= '         <td>';

                if (!$initer) {
                    $url = $row['initUser'];
                } else {
                    $url = $row['userName'];
                }

                $r .= '<img src="' . $this->avatar->getSmallProfilePic($url) . '" />';
                $r .= '         </td>';
                $r .= '         <td>';

                if (!$initer) {
                    $r .= $row['initFirstName'] . '&nbsp;' . $row['initLastName'];
                } else {
                    $r .= $row['firstName'] . '&nbsp;' . $row['lastName'];
                }

                $r .= '         </td>';
                $r .= '         <td>';

                if (!$initer) {
                    $r .= $row['initUser'];
                } else {
                    $r .= $row['userName'];
                }

                $r .= '         </td>';
                $r .= '         <td>';

                switch ($row['state']) {
                    case '-1':
                        $r .= '<a href="/connect/' . $row['target'] . '.html">';
                        $r .= gettext('FRIEND');
                        $r .= '</a>';
                        break;
                    case '1':
                        $r .= '<a href="/block/' . $row['target'] . '.html">';
                        $r .= gettext('BLOCK');
                        $r .= '</a>';
                        break;
                    default:

                        if (!$initer) {
                            $r .= '<a href="/connect/' . $row['init'] . '.html">';
                            $r .= gettext('FRIEND');
                            $r .= '</a>';
                            $r .= '&nbsp;';
                            $r .= '<a href="/block/' . $row['init'] . '.html">';
                            $r .= gettext('BLOCK');
                            $r .= '</a>';
                        } else {
                            $r .= '<a href="/connect/' . $row['target'] . '.html">';
                            $r .= gettext('FRIEND');
                            $r .= '</a>';
                            $r .= '&nbsp;';
                            $r .= '<a href="/block/' . $row['target'] . '.html">';
                            $r .= gettext('BLOCK');
                            $r .= '</a>';
                        }


                        break;
                }

                $r .= '         </td>';
                $r .= '     </tr>';
            }

            $r .= ' </tbody>';
            $r .= '</table>';

            return $r;

        } else {
            $this->error->isNotArrayError();
            return false;
        }
    }

    /**
     * Kapcsolataim oldalhoz megjelenítendő adatok
     * @param null $uid
     * @return bool|null|string
     */
    function renderFriendList($uid = null)
    {
        if (is_null($uid)) {
            $uid = $this->var->getUserID();
        }

        if (is_numeric($uid)) {

            $data = $this->getConnections($uid);

            if (is_array($data)) {

                $neutral = null;
                $friends = null;
                $blocked = null;

                $r = null;

                foreach ($data as $row) {
                    switch ($row['state']) {
                        case '-1':
                            $blocked[] = $row;
                            break;
                        case '0':

                            $neutral[] = $row;
                            break;
                        case '1':
                            $friends[] = $row;
                            break;
                        default:
                            break;
                    }
                }

                if (is_array($neutral)) {
                    $r .= '<h2>';
                    $r .= gettext('WAITING');
                    $r .= '</h2>';
                    $r .= $this->renderTable($neutral);
                }

                if (is_array($friends)) {
                    $r .= '<h2>';
                    $r .= gettext('FRIENDS');
                    $r .= '</h2>';
                    $r .= $this->renderTable($friends);
                }

                if (is_array($blocked)) {
                    $r .= '<h2>';
                    $r .= gettext('BLOCKED');
                    $r .= '</h2>';
                    $r .= $this->renderTable($blocked);
                }

                return $r;


            } elseif (is_null($data)) {
                return '<p>' . gettext('YOU_HAVE_NO_FRIENDS') . '</p>';
            } else {
                $this->error->isNotRequiredVariableType();
                return false;
            }

        } else {
            $this->error->isNotRequiredVariableType();
            return false;
        }
    }

    /**
     * Teljes felhasználói lista amely megjeleníti a  $_SESSION felhasználóhoz képest a kapcsolati legetőségeket
     * @return array|bool|null
     */
    private function getUserList()
    {
        $uid = $this->var->getUserID();

        $query = "
        SELECT *, (
        SELECT state
        FROM user_connection
        WHERE init='$uid' AND target=user_base.id) AS state,
        user_details.uid AS target
        FROM user_base
        LEFT JOIN user_details ON user_details.uid=user_base.id
        WHERE user_base.id!='$uid'
        ORDER BY firstName
        LIMIT 40
        ";


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

    function renderUserList()
    {
        $data = $this->getUserList();

        if (is_array($data)) {

            $uid = $this->var->getUserID();

            $r = null;

            $r .= '<table class="connectionTable">';
            $r .= ' <thead>';
            $r .= '     <tr>';
            $r .= '         <th>';
            $r .= '         </th>';
            $r .= '         <th>';
            $r .= gettext('LIST_NAME');
            $r .= '         </th>';
            $r .= '         <th>';
            $r .= gettext('LIST_EMAIL');
            $r .= '         </th>';
            $r .= '         <th>';
            $r .= '         </th>';
            $r .= '     </tr>';
            $r .= ' </thead>';
            $r .= ' <tfoot>';
            $r .= '     <tr>';
            $r .= '         <th>';
            $r .= '         </th>';
            $r .= '         <th>';
            $r .= gettext('LIST_NAME');
            $r .= '         </th>';
            $r .= '         <th>';
            $r .= gettext('LIST_EMAIL');
            $r .= '         </th>';
            $r .= '         <th>';
            $r .= '         </th>';
            $r .= '     </tr>';
            $r .= ' </tfoot>';
            $r .= ' <tbody>';

            foreach ($data as $row) {


                $r .= '     <tr>';
                $r .= '         <td>';

                $url = $row['userName'];


                $r .= '<img src="' . $this->avatar->getSmallProfilePic($url) . '" />';
                $r .= '         </td>';
                $r .= '         <td>';

                $r .= $row['firstName'] . '&nbsp;' . $row['lastName'];


                $r .= '         </td>';
                $r .= '         <td>';

                $r .= $row['userName'];


                $r .= '         </td>';
                $r .= '         <td>';

                switch ($row['state']) {
                    case '-1':
                        $r .= '<a href="/connect/' . $row['target'] . '.html">';
                        $r .= gettext('FRIEND');
                        $r .= '</a>';
                        break;
                    case '1':
                        $r .= '<a href="/block/' . $row['target'] . '.html">';
                        $r .= gettext('BLOCK');
                        $r .= '</a>';
                        break;
                    default:

                        $r .= '<a href="/connect/' . $row['target'] . '.html">';
                        $r .= gettext('FRIEND');
                        $r .= '</a>';
                        $r .= '&nbsp;';
                        $r .= '<a href="/block/' . $row['target'] . '.html">';
                        $r .= gettext('BLOCK');
                        $r .= '</a>';


                        break;
                }

                $r .= '         </td>';
                $r .= '     </tr>';
            }

            $r .= ' </tbody>';
            $r .= '</table>';

            return $r;

        } else {
            $this->error->isNotArrayError();
            return false;
        }
    }
}
