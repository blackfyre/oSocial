<?php
/**
 * User: galiczmiklos
 * Date: 2013.01.17.
 * Time: 14:51
 *
 * Ideiglenes kijelentkezés fájl
 *
 * @deprecated
 */

session_start();

if (isset($_SESSION['user'])) {
    $_SESSION['user'] = null;
}

if (isset($_SESSION['pass'])) {
    $_SESSION['pass'] = null;
}

session_unset(); //session kiürítése