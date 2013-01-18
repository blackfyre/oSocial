<?php
/**
 * Created by Galicz Miklós.
 * BlackFyre Studio
 * http://blackworks.org
 * galicz.miklos@blackworks.org
 *
 * Date: 2013.01.17.
 * Time: 18:21
 */
class userProfile
{
    /**
     * Adatbázis kapcsolat az adatbázis hibák megjelenítéséhez
     * @var null|PDO
     */
    private $db = null;

    /**
     * Az alapfunkciók, mert a rendszerben sem bízunk
     * @var coreFunctions|null
     */
    private $core = null;

    /**
     * Hibakezelő
     * @var errorHandler|null
     */
    private $error = null;

    /**
     * Környezeti változó beszerző
     * @var null|varGetter
     */
    private $var = null;

    private $avatar = null;

    /**
     * Szükséges osztályok betöltése
     */

    private $siteLang = null;
    private $userID = null;

    function __construct($userID = null)
    {
        if (is_null($this->db)) {
            $dbInstance = database::getInstance();
            $this->db = $dbInstance->getConnection();
        }

        if (is_null($this->core)) {
            $this->core = new coreFunctions();
        }

        if (is_null($this->error)) {
            $this->error = new errorHandler();
        }

        if (is_null($this->var)) {
            $this->var = new varGetter();
        }

        if (is_null($this->avatar)) {
            $this->avatar = new profilePicture();
        }

        $this->siteLang = $this->var->getSiteLang();

    }

    /**
     * Ellenőrizzük, hogy a uid egyáltalán szám -e
     * @param null $userID
     * @return bool|int
     */
    private function setUserID($userID = null)
    {

        $userID = $this->core->cleanVar($userID);

        if (is_numeric($userID)) {

            return $this->userID;

        } else {
            $this->error->isNotRequiredVariableType();
            return false;
        }
    }

    private function getUserData($uid = null)
    {

        if (is_null($uid)) {
            $uid = $this->userID;
        }

        $genderLang = 'name_' . $this->siteLang;

        $query = "SELECT user_base.id AS uid, firstName, lastName, dateOfBirth, $genderLang AS gender, userName AS email  FROM user_base
        LEFT JOIN user_details ON user_details.uid=user_base.id
        LEFT JOIN _gender ON user_details.gender=_gender.id
        WHERE user_base.id='$uid'";

        if ($result = $this->db->query($query)) {

            if ($result->rowCount() == 1) {

                return $result->fetch();

            } else {
                $this->error->queryMultipleResults();
                return false;
            }

        } else {
            $this->error->queryError();
            return false;
        }

    }

    function renderUserProfile($uid = null)
    {

        $data = $this->getUserData($uid);

        if (is_array($data)) {

            $currentUser = $this->var->getUserID();

            //var_dump($data);

            $r = null;

            $r .= '<table class="profileTable">';
            $r .= ' <tbody>';

            if ($uid != $currentUser) {
                $r .= '     <tr class="userActions">';
                $r .= '         <td colspan="2">';
                $r .= 'kapcsolat';
                $r .= '         </td>';
                $r .= '     </tr>';
            }

            $r .= '     <tr>';
            $r .= '         <td>';
            $r .= '             <img src="' . $this->avatar->getBigProfilePic($data['email']) . '" />';
            $r .= '         </td>';
            $r .= '         <td>';
            $r .= '             <ul>';

            //Csak az adat megjelenítés végett van így, normális esetben tételenként, nyelvi változóval együtt kerül megjelenítésre

            foreach ($data as $key => $value) {

                $r .= '<li>';
                $r .= $key . ': ' . $value;
                $r .= '</li>';

            }
            $r .= '             </ul>';
            $r .= '         </td>';
            $r .= '     </tr>';
            $r .= ' </tbody>';
            $r .= '</table>';

            return $r;

        } else {
            $this->error->isNotArrayError();
            return false;
        }

    }

    function renderCurrentUserProfile()
    {
        return $this->renderUserProfile($this->var->getUserID());
    }

}
