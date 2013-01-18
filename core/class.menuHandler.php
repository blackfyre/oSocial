<?php
/**
 * Created by Galicz Miklós.
 * BlackFyre Studio
 * http://blackworks.org
 * galicz.miklos@blackworks.org
 *
 * Date: 2013.01.15.
 * Time: 21:56
 *
 * @package oSocial
 * @subpacakage menuHandler
 */
class menuHandler
{
    private $db = null;
    private $core = null;
    private $error = null;
    private $var = null;

    function __construct()
    {

        $dbInstance = database::getInstance();
        $this->db = $dbInstance->getConnection();

        $this->core = new coreFunctions();
        $this->error = new errorHandler();
        $this->var = new varGetter();
    }

    private function getMainMenu()
    {

        $lang = $this->var->getSiteLang();

        $accessLevel = $this->var->getUserAccessLevel();

        $query = "SELECT `id`,`parent`, `text_$lang`, `href_$lang`, `title_$lang`, `target` FROM menu_main WHERE `access_level`<='$accessLevel'";

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

    function renderMainMenu()
    {
        $data = $this->getMainMenu();

        if (is_array($data)) {

            $r = null;

            $lang = $this->var->getSiteLang();

            $text = 'text_' . $lang;
            $href = 'href_' . $lang;
            $title = 'title_' . $lang;

            foreach ($data as $value) {
                $tempArray['text'] = $value[$text];
                $tempArray['href'] = $value[$href];
                $tempArray['title'] = $value[$title];
                $tempArray['target'] = $value['target'];

                $r[] = $tempArray;
            }

            return $r;

        } else {
            $this->error->isNotArrayError();
            return false;
        }
    }
}
