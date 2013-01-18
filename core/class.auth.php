<?php
/**
 * User: galiczmiklos
 * Date: 2013.01.14.
 * Time: 12:31
 */
class auth
{
    private $db = null;
    private $core = null;
    private $error = null;
    private $form = null;
    private $getter = null;
    private $log = null;

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

        if (is_null($this->form)) {
            $this->form = new form();
        }

        if (is_null($this->getter)) {
            $this->getter = new varGetter();
        }

        if (is_null($this->log)) {
            $this->log = new activityLog();
        }
    }

    /**
     * A megadott felhasználói adatokat ellenőri le, hogy léteznek -e, helyesek -e és aktív -e a megadott felhasználó
     *
     * @param string $user Felhasználó
     * @param string $pass Jelszó
     * @return bool
     */
    private function checkLogin($user = null, $pass = null)
    {

        $user = $this->core->cleanVar($user);
        $pass = $this->core->cleanVar($pass);

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

    }

    function spicer($passToSpice = null)
    {
        return md5('salt' . sha1('salt' . $passToSpice . 'salt') . 'salt');
    }

    /**
     *
     * Bejelentkezési adatok forrás szerinti ellenőrzése
     * Ez egy rövidített változat... az eredeti a TASKNET -ben lelhető fel.
     *
     * @param string $source session
     * @return bool
     */
    function checkLoginSource($source = 'session')
    {
        if (!is_null($source)) {

            $source = $this->core->cleanVar($source);

            switch ($source) {
                case 'session':
                    if (isset($_SESSION['user']) AND isset($_SESSION['pass'])) {
                        $user = $this->core->cleanVar($_SESSION['user']);
                        $pass = $this->core->cleanVar($_SESSION['pass']);
                    } else {
                        return false;
                    }

                    break;
                case 'post':

                    if (isset($_POST['text-user']) AND isset($_POST['pass-pass'])) {
                        $user = $this->core->cleanVar($_POST['text-user']);
                        $pass = $this->spicer($this->core->cleanVar($_POST['pass-pass']));
                    } else {
                        return false;
                    }

                    break;
                default:
                    return false;
                    break;
            }

            if ($this->checkLogin($user, $pass)) {
                return true;
            } else {
                return false;
            }

        } else {
            return false;
        }
    }

    public function loginForm()
    {
        $form = array(
            array('type' => 'text', 'name' => 'user', 'value' => '', 'placeH' => gettext('PLACEHOLDER_USERNAME')),
            array('type' => 'pass', 'name' => 'pass', 'value' => '', 'placeH' => gettext('PLACEHOLDER_PASSWORD'))
        );

        return $this->form->generateForm(gettext('FORM_LOGIN_HEADER'), 'login', $form);
    }

    public function registerForm()
    {

        $genderSelect = $this->getter->formatGendersForSelect();

        $form = array(
            array('type' => 'text', 'name' => 'firstName', 'value' => '', 'placeH' => gettext('PLACEHOLDER_FIRSTNAME')),
            array('type' => 'text', 'name' => 'lastName', 'value' => '', 'placeH' => gettext('PLACEHOLDER_LASTNAME')),
            array('type' => 'email', 'name' => 'email', 'value' => '', 'placeH' => gettext('PLACEHOLDER_EMAIL')),
            array('type' => 'pass', 'name' => 'pass1', 'value' => '', 'placeH' => gettext('PLACEHOLDER_PASSWORD')),
            array('type' => 'pass', 'name' => 'pass2', 'value' => '', 'placeH' => gettext('PLACEHOLDER_PASSWORD')),
            array('type' => 'select', 'name' => 'gender', 'value' => $genderSelect, 'placeH' => gettext('PLACEHOLDER_SELECT_GENDER')),
            array('type' => 'date', 'name' => 'dateOfBirth', 'value' => '', 'placeH' => gettext('PLACEHOLDER_DATE_OF_BIRTH'))
        );

        $r = $this->form->generateForm(gettext('FORM_REGISTER_HEADER'), 'register', $form);

        $r .= '
        <script type="text/javascript">
        $().ready(function() {
            $("#pass2").keyup(function() {
                if ($("#pass1").val() != $("#pass2").val()) {
                    $("#pass2").addClass("formError");
                } else {
                    $("#pass2").removeClass("formError");
                }
            });


        });

        $(function() {
                $( "#dateOfBirth" ).datepicker({
        		  showAnim: "fold",
                  firstDay: 1,
        		  dateFormat: "yy-mm-dd",
        		  altFormat: "yy-mm-dd" });
            });
        </script>
        ';

        return $r;
    }

    public function doLogin()
    {
        $valid = $this->checkLoginSource('post');

        if ($valid) {
            $_SESSION['user'] = $_POST['text-user'];
            $_SESSION['pass'] = $this->spicer($_POST['pass-pass']);
            $_SESSION['logged'] = true;

            unset($_POST);

            $this->log->logLogin();

            return true;
        } else {
            $this->error->errorMSG('Érvénytelen felhasználónév vagy jelszó!');

            unset($_POST);
            return false;
        }
    }

    /**
     * Regisztráció
     * @return bool
     */
    public function doRegister()
    {
        $data = $this->form->validator();
        //var_dump($data);

        //ha az űrlapban nincs validálási hoba
        if (!$this->form->checkForErrors($data)) {
            if ($data['pass1'] == $data['pass2']) {

                //szedjük össze  a szükséges adatokat
                $spicedPass = $this->spicer($data['pass1']);
                $userName = $data['email'];

                $firstName = $data['firstName'];
                $lastName = $data['lastName'];

                $gender = $data['gender'];
                $dateOfBirth = $data['dateOfBirth'];

                //illesszük be az alap táblába
                $insertBase = "INSERT INTO user_base(`userName`,`userPass`) VALUES ('$userName','$spicedPass')";

                if ($this->db->query($insertBase)) {

                    //kérjük le a felhasználó azonosítót... nem feltétlenül szükséges, de az esetleges azonosító elcsúszások kezelését megoldják
                    $queryUserID = "SELECT `id` FROM `user_base` WHERE `userName`='$userName' AND `userPass`='$spicedPass'";

                    if ($result = $this->db->query($queryUserID)) {

                        $uid = $result->fetch();
                        $uid = $uid['id'];

                        // illeszük be a részleteket
                        $insertDetails = "INSERT INTO user_details (`uid`, `firstName`, `lastName`, `gender`, `dateOfBirth`, `regDate`)
                        VALUES ('$uid','$firstName','$lastName','$gender','$dateOfBirth', NOW() )";

                        if ($this->db->query($insertDetails)) {

                            //ha minden jó
                            return true;

                        } else {
                            $this->error->queryError();
                            return false;
                        }

                    } else {
                        $this->error->queryError();
                        return false;
                    }

                } else {
                    $this->error->queryError();
                    return false;
                }

            } else {
                $this->error->errorMSG(gettext('ERROR_PASSWORDS_DONT_MATCH'));
                return false;
            }
        } else {
            $this->error->errorMSG(gettext('ERROR_INVALID_DATA'));
            return false;
        }

    }

    function destroyEverything()
    {
        $this->log->logLogout();

        if (isset($_SESSION['user'])) {
            $_SESSION['user'] = null;
        }

        if (isset($_SESSION['pass'])) {
            $_SESSION['pass'] = null;
        }

        session_unset(); //session kiürítése

        return true;
    }

}
