<?php
/**
 * User: galiczmiklos
 * Date: 2013.01.18.
 * Time: 13:09
 */
class messenger
{
    private $db = null;
    private $core = null;
    private $error = null;
    private $var = null;
    private $avatar = null;

    function __construct()
    {
        $dbInstance = database::getInstance();
        $this->db = $dbInstance->getConnection();
        $this->core = new coreFunctions();
        $this->error = new errorHandler();
        $this->var = new varGetter();
        $this->avatar = new profilePicture();
    }


}
