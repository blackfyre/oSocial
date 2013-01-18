<?php
/**
 * Created by Galicz Miklós.
 * BlackFyre Studio
 * http://blackworks.org
 * galicz.miklos@blackworks.org
 *
 * Date: 2013.01.17.
 * Time: 18:30
 *
 * @package oSocial
 * @subpackage gravatarGetter
 * @todo Cahce-elni a már letöltött képeket!
 */
class profilePicture
{

    /**
     * @param string $email The email address
     * @param int $s Size in pixels, defaults to 80px [ 1 - 2048 ]
     * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
     * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
     * @param bool $img True to return a complete IMG tag False for just the URL
     * @param array $attributes Optional, additional key/value attributes to include in the IMG tag
     * @return string containing either just a URL or a complete image tag
     * @source http://gravatar.com/site/implement/images/php/
     */
    private function getGravatar($email, $s = 80, $d = 'mm', $r = 'g', $img = false, $attributes = array())
    {
        $url = 'http://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$s&d=$d&r=$r";
        if ($img) {
            $url = '<img src="' . $url . '"';
            foreach ($attributes as $key => $val)
                $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }
        return $url;
    }

    function getSmallProfilePic($email)
    {
        return $this->getGravatar($email);
    }

    function getBigProfilePic($email)
    {
        return $this->getGravatar($email, 250);
    }
}
