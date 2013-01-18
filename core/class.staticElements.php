<?php
/**
 * User: galiczmiklos
 * Date: 2013.01.11.
 * Time: 14:39
 */
class staticElements
{
    function pageHeader()
    {
        $r = null;
        $r .= '<!DOCTYPE html>';
        $r .= '<html>';
        $r .= '<head>';
        $r .= '<link rel="stylesheet" media="all" href="/style/style.css">';
        $r .= '		<title>Volvo Raktárkészlet</title>';
        $r .= '</head>';
        $r .= '<body>';

        return $r;
    }

    function pageFooter()
    {
        $r = null;
        $r .= '</body>';
        $r .= '</html>';

        return $r;
    }

    function menu()
    {
        $r = '
        <ul class="menu">
            <li><a href="/index.php/mode=admin">Készlet</a></li>
            <li><a href="/index.php?mode=logout" hreflang="hu">Kijelentkezés</a></li>
        </ul>
        ';

        return $r;
    }
}
