<?php
/**
 * User: galiczmiklos
 * Date: 2013.01.04.
 * Time: 12:07
 */

/**
 * Hiba kezelő osztály
 */
class errorHandler
{

    /**
     * Adatbázis kapcsolat az adatbázis hibák megjelenítéséhez
     *
     * @var null|PDO
     */
    private $db = null;

    /**
     * Az alapfunkciók, mert a rendszerben sem bízunk
     *
     * @var coreFunctions|null
     */
    private $core = null;

    /**
     * Szükséges osztályok betöltése
     */
    function __construct()
    {
        if (is_null($this->db)) {
            $dbInstance = database::getInstance();
            $this->db = $dbInstance->getConnection();
        }

        if (is_null($this->core)) {
            $this->core = new coreFunctions();
        }
    }

    /**
     * Created and shared by Paul Gobée at http://stackoverflow.com/a/12463381/1012431
     *
     * Gets the caller of the function where this function is called from
     * @param string what to return? (Leave empty to get all, or specify: "class", "function", "line", "class", etc.) - options see: http://php.net/manual/en/function.debug-backtrace.php
     * @return mixed
     */
    private function getCaller($what = NULL)
    {
        $trace = debug_backtrace();
        $previousCall = $trace[2]; // 0 is this call, 1 is call in previous function, 2 is caller of that function

        if (isset($what)) {
            return $previousCall[$what];
        } else {
            return $previousCall;
        }
    }

    /**
     * Adatbázis hiba megjelenítés!
     * Az errorMSG() több infóval szolgál a megjelenítés miatt
     */
    public function queryError()
    {
        $caller = $this->getCaller();
        $errorInfo = $this->db->errorInfo();
        $msg = gettext('ERROR_QUERY_FAILED');

        if (isset($caller['class'])) {
            $msg .= '->' . $caller['class'];
        }

        if (isset($caller['function'])) {
            $msg .= '->' . $caller['function'];
        }

        $msg .= ': ' . $errorInfo[2];
        $this->errorMSG($msg);
    }

    /**
     * Az adatbázis hibához hasonlóan specifikus hibát jelenít meg
     */
    public function isNullError()
    {
        $caller = $this->getCaller();
        $errorInfo = $this->db->errorInfo();
        $msg = gettext('ERROR_REQUIRED_IS_NULL') . 'NULL hiba->' . $caller['class'] . '->' . $caller['function'];
        $this->errorMSG($msg);
    }

    /**
     * Az adatbázis hibához hasonlóan specifikus hibát jelenít meg
     */
    public function isNotArrayError()
    {
        $caller = $this->getCaller();
        $errorInfo = $this->db->errorInfo();
        $msg = gettext('ERROR_REQUIRED_NOT_ARRAY') . '->' . $caller['class'] . '->' . $caller['function'];
        $this->errorMSG($msg);
    }

    /**
     * Az adatbázis hibához hasonlóan specifikus hibát jelenít meg
     */
    public function isNotRequiredVariableType()
    {
        $caller = $this->getCaller();
        $errorInfo = $this->db->errorInfo();
        $msg = gettext('ERROR_NOT_EXPECTED_VAR_TYPE') . '->' . $caller['class'] . '->' . $caller['function'];
        $this->errorMSG($msg);
    }

    /**
     * Az adatbázis hibához hasonlóan specifikus hibát jelenít meg
     */
    public function queryMultipleResults()
    {
        $caller = $this->getCaller();
        $errorInfo = $this->db->errorInfo();
        $msg = gettext('ERROR_MULTIPLE_QUERY_RESULTS') . '->' . $caller['class'] . '->' . $caller['function'];
        $this->errorMSG($msg);
    }

    /**
     * @param null $string A HIBA ÜZENET
     */
    public function errorMSG($string = null)
    {
        $string = $this->core->cleanVar($string);
        ?>
    <div class="msg error">
        <p><strong><?php echo gettext('ERROR') ?>: </strong> <?php echo $string ?></p>
    </div>
    <?php
    }

    /**
     * @param string $string A HIBA ÜZENET
     */
    public function successMSG($string = null)
    {
        $string = $this->core->clean_var($string);
        ?>
    <div class="msg success">
        <p><strong><?php echo gettext('SUCCESS') ?>: </strong> <?php echo $string ?></p>
    </div>
    <?php
    }
}
