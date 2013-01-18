<?php
/**
 * User: galiczmiklos
 * Date: 2013.01.16.
 * Time: 16:14
 *
 * @package oSocial
 * @subpacakage varGetter
 */
class varGetter
{
    /**
     * Adatbázis kapcsolat az adatbázis hibák megjelenítéséhez
     *
     * @var null|PDO
     */
    private $db = null;

    /**
     * Az alapfunkciók, mert a rendszerben sem bízunk
     *
     * @var coreFunctions|null
     */
    private $core = null;

    private $error = null;

    /**
     * Szükséges osztályok betöltése
     */

    private $siteLang = null;

    function __construct()
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

        $this->siteLang = $this->getSiteLang();
    }

    function getSiteLang()
    {
        return $this->core->cleanVar($_SESSION['lang']);
    }

    /**
     * Lekérdezi és tömbként visszaadja a nemeket
     *
     * Tömb ha jó|null ha nincs|false ha valami nem jött össze
     *
     * @return array|bool|null
     */
    private function getGenders()
    {

        $colName = 'name_' . $this->siteLang;

        $query = "SELECT `id`, `$colName` FROM _gender";

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
     * Formázza a nemeket a regisztrációs űrlap részére
     * @return bool|string
     */
    function formatGendersForSelect()
    {
        $data = $this->getGenders();

        if (!is_bool($data) OR !is_null($data)) {
            $nameKey = 'name_' . $this->siteLang;

            $r = null;

            foreach ($data as $row) {
                $r[] = $row['id'] . '||' . $row[$nameKey];
            }

            $r = implode('|;|', $r);
            return $r;
        } else {
            return false;
        }
    }

    /**
     * A $_SESSION-ben található felhasználó adatok hitelesítése, ha van mit hitelesíteni
     * @return bool
     */
    private function validateLoginState()
    {
        if (isset($_SESSION['logged']) AND is_bool($_SESSION['logged'])) {

            $user = $_SESSION['user'];
            $pass = $_SESSION['pass'];

            $query = "SELECT user_base.id AS id FROM user_base LEFT JOIN user_details ON user_details.uid=user_base.id WHERE `userName`='$user' AND `userPass`='$pass' AND userActive='1'";

            if ($result = $this->db->query($query)) {
                if ($result->rowCount() == 1) {
                    return true;
                } else {
                    return false;
                }
            } else {
                $this->error->queryError();
                return false;
            }

        } else {
            return false;
        }
    }

    /**
     * Aktuális felhasználó azonosítójának beszerzése
     * @return bool|int
     */
    function getUserID()
    {
        if ($this->validateLoginState()) {
            $user = $_SESSION['user'];
            $pass = $_SESSION['pass'];

            $query = "SELECT user_base.id AS id FROM user_base LEFT JOIN user_details ON user_details.uid=user_base.id WHERE `userName`='$user' AND `userPass`='$pass' AND userActive='1'";

            if ($result = $this->db->query($query)) {
                if ($result->rowCount() == 1) {

                    $uid = $result->fetch();
                    return $uid['id'];

                } else {
                    return false;
                }
            } else {
                $this->error->queryError();
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Aktuális felhasználó hozzáférési szintjének beszerzése
     * @return bool|int
     */
    function getUserAccessLevel()
    {
        if ($this->validateLoginState()) {
            $user = $_SESSION['user'];
            $pass = $_SESSION['pass'];

            $query = "SELECT user_details.userLevel AS level FROM user_base LEFT JOIN user_details ON user_details.uid=user_base.id WHERE `userName`='$user' AND `userPass`='$pass' AND userActive='1'";

            if ($result = $this->db->query($query)) {
                if ($result->rowCount() == 1) {

                    $uid = $result->fetch();
                    return $uid['level'];

                } else {
                    return false;
                }
            } else {
                $this->error->queryError();
                return false;
            }
        } else {
            return false;
        }
    }

}
