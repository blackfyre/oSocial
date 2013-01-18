<?php
/**
 * Created by Galicz Miklós.
 * BlackFyre Studio
 * http://blackworks.org
 * galicz.miklos@blackworks.org
 *
 * Date: 2013.01.14.
 * Time: 22:46
 *
 * Egy megadott CSV fájl alapján feltölti az adatbázis felhasználókkal
 *
 * @deprecated
 */

$csvPath = "_populator/simplified.csv";

/**
 * AUTOLOADER
 */
spl_autoload_register(function ($className) {
    $file = 'core/class.' . $className . '.php';
    include_once $file;
});

// Jelszó minden felhasználó részére
$passForAllUsers = md5('salt' . sha1('salt' . 'nincsJelszo' . 'salt') . 'salt');

//unicode megjelenítés erőltetése
ini_set("default_charset", "utf-8");

// CSV beolvasása
if (($handle = fopen($csvPath, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 0, ";")) !== FALSE) {

        // meghatározott adatok beszerzése
        $tempArray['gender'] = ($data[0] == 'male' ? 1 : 2);
        $tempArray['firstName'] = htmlspecialchars($data[1], ENT_QUOTES);
        $tempArray['lastName'] = htmlspecialchars($data[2], ENT_QUOTES);
        $tempArray['email'] = strtolower($data[3]);

        $dob = explode('/', $data[4]);

        $tempArray['dateOfBirth'] = $dob[2] . '-' . $dob[0] . '-' . $dob[1];

        //több adat esetén az importálásnál is több kört szükséges csinálni, mert a földrajzi és egyébb kiszervezhető adatokat (cégnév, stb.) célszerű külön táblában tárolni

        $r[] = $tempArray;
    }
    fclose($handle);
}

//var_dump($r);

//adatbázis osztály betöltése

$dbInstance = database::getInstance();
$db = $dbInstance->getConnection();

//hiba kezelő osztály betöltése

$error = new errorHandler();

foreach ($r as $entry) {

    // A duplikátumok elkerülése végett egy előzetes ellenőrzés, lassabb lesz az importálás, de biztonságosabb

    $email = $entry['email'];
    $gender = $entry['gender'];
    $firstName = $entry['firstName'];
    $lastName = $entry['lastName'];
    $dob = $entry['dateOfBirth'];

    $userCheck = "SELECT * FROM `user_base` WHERE `userName`='$email'";

    if ($userCheckResult = $db->query($userCheck)) {

        //ha a találati sorok száma 0, akkor nem illesztjük be még 1 alkalommal a tételt
        if ($userCheckResult->rowCount() == 0) {

            $userBaseInsert = "INSERT INTO `user_base` (`userName`,`userPass`) VALUES ('$email','$passForAllUsers')";

            if (!$db->query($userBaseInsert)) {
                //ha valamiért nem sikerülne a lekérdezés, álljon le és írja ki, hogy mi a hiba
                $error->queryError();
                exit;
            } else {
                echo 'userBase: ' . $email . '-> OK ';
            }

        }

    } else {
        //ha valamiért nem sikerülne a lekérdezés, álljon le és írja ki, hogy mi a hiba
        $error->queryError();
        exit;
    }


    // szerezzük be az elöbb felvitt felhasználó azonosítóját
    if ($userCheckResultSecond = $db->query($userCheck)) {

        //ha a találati sorok száma 1, akkor tovább haladunk
        if ($userCheckResultSecond->rowCount() == 1) {

            $userBase = $userCheckResultSecond->fetch();
            $userId = $userBase['id'];

            $checkUserDeatils = "SELECT * FROM `user_details` WHERE `uid`='$userId'";

            if ($checkUserDeatilsResult = $db->query($checkUserDeatils)) {

                if ($checkUserDeatilsResult->rowCount() == 0) {

                    $userDetailInsert = "INSERT INTO `user_details` (`uid`,`firstName`,`lastName`,`gender`,`dateOfBirth`) VALUES ('$userId','$firstName','$lastName','$gender','$dob')";

                    if (!$db->query($userDetailInsert)) {
                        //ha valamiért nem sikerülne a lekérdezés, álljon le és írja ki, hogy mi a hiba
                        $error->queryError();
                        exit;
                    } else {
                        echo 'userDetailsInsert -> OK <br />';
                    }

                }

            } else {
                //ha valamiért nem sikerülne a lekérdezés, álljon le és írja ki, hogy mi a hiba
                $error->queryError();
                exit;
            }

        }

    } else {
        //ha valamiért nem sikerülne a lekérdezés, álljon le és írja ki, hogy mi a hiba
        $error->queryError();
        exit;
    }
}


