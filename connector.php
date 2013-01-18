<?php
/**
 * Created by Galicz Miklós.
 * BlackFyre Studio
 * http://blackworks.org
 * galicz.miklos@blackworks.org
 *
 * Date: 2013.01.15.
 * Time: 20:15
 */

/**
 * AUTOLOADER
 */
spl_autoload_register(function ($className) {
    $file = 'core/class.' . $className . '.php';
    include_once $file;
});

date_default_timezone_set('Europe/Budapest');

$dbInstance = database::getInstance();
$db = $dbInstance->getConnection();

$error = new errorHandler();

//mivel egyenletesen lett feltöltve az adatbázis ezért nincs probléma az azonosítókkal, ellenkező esetben le kéne kérni minden azonosítót és a szerint haladni tovább
$countUsers = "SELECT COUNT(id) AS userCount FROM user_base";

//formátumok és alap értékek
$dateFormat = "Y-m-d H:i:s";
$minDate = strtotime('1 January 2000');
$maxDate = strtotime('15 January 2012');

$minState = -1;
$maxState = 1;

if ($countResult = $db->query($countUsers)) {

    $userCount = $countResult->fetch();
    $userCount = $userCount['userCount'];

    for ($i = 1; $i <= 12000; $i++) {

        $date1 = mt_rand($minDate, $maxDate);
        $date2 = mt_rand($date1, $maxDate);

        $state = mt_rand($minState, $maxState);
        $uid1 = mt_rand(1, $userCount);
        $uid2 = mt_rand(1, $userCount);

        $eventCreation = date($dateFormat, $date1);
        $eventDate = ($state == 0 ? NULL : date($dateFormat, $date2));

        //Nagy a minta, de sosem lehet tudni, hogy mikor generál olyat ahol önmagával lépne kapcsolatba a felhasználó
        if ($uid1 != $uid2) {

            //egy kapcsolat csak 1x szerepeljen az adatbázisban
            $checkQuery = "SELECT * FROM user_connection WHERE `init`='$uid1' AND `target`='$uid2'";

            if ($checkResult = $db->query($checkQuery)) {

                if ($checkResult->rowCount() == 0) {

                    $insertQuery = "INSERT INTO user_connection (`init`, `target`, `state`, `createdOn`, `eventOn`) VALUES ('$uid1','$uid2','$state','$eventCreation','$eventDate')";

                    if ($db->query($insertQuery)) {
                        echo 'OK<br />';
                    } else {
                        $error->queryError();
                        exit;
                    }

                }

            } else {
                $error->queryError();
                exit;
            }

        }


    }

} else {
    $error->queryError();
    exit;
}