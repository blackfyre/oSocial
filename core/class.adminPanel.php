<?php
/**
 * Created by Galicz Miklós.
 * BlackFyre Studio
 * http://blackworks.org
 * galicz.miklos@blackworks.org
 *
 * Date: 2013.01.17.
 * Time: 19:04
 *
 * @package oSocial
 * @subpacakage adminPanel
 */
class adminPanel
{
    private $db = null;
    private $core = null;
    private $error = null;
    private $var = null;

    function __construct()
    {

        $dbi = database::getInstance();
        $this->db = $dbi->getConnection();

        $this->core = new coreFunctions();
        $this->error = new errorHandler();
        $this->var = new varGetter();

    }

    /**
     * @return array|bool|null
     */
    private function getRegistrantsToProcess()
    {
        $query = "SELECT * FROM user_base LEFT JOIN user_details ON user_details.uid=user_base.id
        WHERE userActive IS NULL";

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
     * @return bool|string
     */
    function renderRegistrantsToProcess()
    {
        $data = $this->getRegistrantsToProcess();

        $r = null;

        if (is_null($data)) {

            $r .= '<p>';
            $r .= gettext('NO_REGISTRANTS_TO_PROCESS');
            $r .= '</p>';

            return $r;

        } elseif (is_array($data)) {

            $r .= '<table class="adminTable">';
            $r .= ' <thead>';
            $r .= '     <tr>';
            $r .= '         <th>';
            $r .= gettext('ADMIN_H_EMAIL');
            $r .= '         </th>';
            $r .= '         <th>';
            $r .= gettext('ADMIN_H_FIRSTNAME');
            $r .= '         </th>';
            $r .= '         <th>';
            $r .= gettext('ADMIN_H_LASTNAME');
            $r .= '         </th>';
            $r .= '         <th>';
            $r .= gettext('ADMIN_H_REGDATE');
            $r .= '         </th>';
            $r .= '         <th>';
            $r .= gettext('ADMIN_H_ACTION');
            $r .= '         </th>';
            $r .= '     </tr>';
            $r .= ' </thead>';
            $r .= ' <tfoot>';
            $r .= '     <tr>';
            $r .= '         <th>';
            $r .= gettext('ADMIN_H_EMAIL');
            $r .= '         </th>';
            $r .= '         <th>';
            $r .= gettext('ADMIN_H_FIRSTNAME');
            $r .= '         </th>';
            $r .= '         <th>';
            $r .= gettext('ADMIN_H_LASTNAME');
            $r .= '         </th>';
            $r .= '         <th>';
            $r .= gettext('ADMIN_H_REGDATE');
            $r .= '         </th>';
            $r .= '         <th>';
            $r .= gettext('ADMIN_H_ACTION');
            $r .= '         </th>';
            $r .= '     </tr>';
            $r .= ' </tfoot>';
            $r .= ' <tbody>';

            foreach ($data as $row) {
                $r .= '     <tr>';
                $r .= '         <td>';
                $r .= $row['userName'];
                $r .= '         </td>';
                $r .= '         <td>';
                $r .= $row['firstName'];
                $r .= '         </td>';
                $r .= '         <td>';
                $r .= $row['lastName'];
                $r .= '         </td>';
                $r .= '         <td>';
                $r .= $row['regDate'];
                $r .= '         </td>';
                $r .= '         <td>';
                $r .= '<a href="/admin/activate/' . $row['uid'] . '.html">';
                $r .= gettext('ADMIN_ACTIVATE_USER');
                $r .= '</a>';
                $r .= '&nbsp;';
                $r .= '<a href="/admin/ban/' . $row['uid'] . '.html">';
                $r .= gettext('ADMIN_BAN_USER');
                $r .= '</a>';
                $r .= '         </td>';
                $r .= '     </tr>';
            }


            $r .= ' </tbody>';
            $r .= '</table>';

            return $r;

        } else {
            return false;
        }

    }

    /**
     * A felhasználót engedélyezi vagy tiltja
     * @param bool $activate true aktivál | false kitilt
     * @param int $uid
     * @return bool
     */
    function processUser($activate = false, $uid = null)
    {
        if (is_bool($activate) AND is_numeric($uid)) {

            $action = ($activate ? 1 : 0);
            $uid = $this->core->cleanVar($uid);

            $query = "UPDATE user_details SET userActive='$action' WHERE uid='$uid'";

            if ($this->db->query($query)) {
                return true;
            } else {
                $this->error->queryError();
                return false;
            }

        } else {
            $this->error->isNotRequiredVariableType();
            return false;
        }
    }
}
