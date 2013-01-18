<?php
/**
 * User: galiczmiklos
 * Date: 2013.01.11.
 * Time: 14:34
 */

require_once 'core/control.overseer.php';
require_once 'smarty/Smarty.class.php';

/**
 * AUTOLOADER
 */
spl_autoload_register(function ($className) {
    $file = 'core/class.' . $className . '.php';
    include_once $file;
});

session_start();

$control = new overseer();
$control->invoke();