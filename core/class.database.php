<?php
/**
 * User: galiczmiklos
 * Date: 2013.01.04.
 * Time: 10:53
 *
 * @package oSocial
 * @subpacakage databaseHandler
 */
class database
{
    /**
     * @var null A singleton osztály öntárolója
     */
    private static $instance = null;

    /**
     * Az adatbázis kapcsolat tárolója
     *
     * @var null|PDO
     */
    private $connection = null;

    /**
     * Inicializáláskor létrehozza a kapcsolatot
     */
    function __construct()
    {

        $dbHost = 'localhost';
        $dbUser = 'socialUser';
        $dbPass = 'socialPass';
        $dbDB = 'social';


        try {
            $this->connection = new PDO("mysql:host=$dbHost;dbname=$dbDB;charset=utf8", $dbUser, $dbPass);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {

            if ($_SERVER['HTTP_HOST'] == "social.local") {
                $msg = '<pre>';
                $msg .= $e;
                $msg .= '</pre>';
            } else {
                $msg = 'HIBA AZ ADATBÁZIS KAPCSOLATBAN, A RENDSZERGAZADA ELÉRHETŐSÉGE: user@domain.com';
            }
            exit($msg);
        }
    }

    /**
     * Inicializáció, ha még nem lenne
     *
     * @return database|null
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Kapcsolat visszaadása
     *
     * @return null|PDO
     */
    public function getConnection()
    {
        return $this->connection;
    }
}
