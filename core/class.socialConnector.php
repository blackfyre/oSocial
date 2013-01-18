<?php
/**
 * Created by Galicz Miklós.
 * BlackFyre Studio
 * http://blackworks.org
 * galicz.miklos@blackworks.org
 *
 * Date: 2013.01.17.
 * Time: 23:24
 *
 * @package oSocial
 * @subpackage socialConnector
 */
class socialConnector
{
    private $db = null;
    private $core = null;
    private $error = null;
    private $var = null;

    function __construct()
    {


        if (is_null($this->db)) {
            $dbInstance = database::getInstance();
            $this->db = $dbInstance->getConnection();
        }

        if (is_null($this->core)) {
            $this->core = new coreFunctions();
        }

        if (is_null($this->error)) {
            $this->error = new errorHandler();
        }

        if (is_null($this->var)) {
            $this->var = new varGetter();
        }
    }

    /**
     * @param null $target
     * @param int $type
     * @return bool
     */
    function setRelationTo($target = null, $type = 0)
    {
        if (is_numeric($target) AND is_numeric($type)) {
            $uid = $this->var->getUserID();

            $checkQuery = "SELECT * FROM user_connection WHERE (init='$uid' AND target='$target') OR (init='$target' AND target='$uid')";

            if ($checkResult = $this->db->query($checkQuery)) {

                if ($checkResult->rowCount() == 0) {
                    $query = "INSERT INTO user_connection (init, target, state, createdOn) VALUES ('$uid','$target',0,NOW())";
                } else {
                    $checkResult = $checkResult->fetch();

                    if ($checkResult['init'] == $uid) {
                        $query = "UPDATE user_connection SET state='$type' WHERE init='$uid' AND target='$target'";
                    } else {
                        $query = "UPDATE user_connection SET state='$type' WHERE target='$uid' AND init='$target'";
                    }
                }

                if ($this->db->query($query)) {
                    return true;
                } else {
                    return false;
                }

            } else {
                $this->error->queryError();
                return false;
            }

        } else {
            $this->error->isNotRequiredVariableType();
            return false;
        }
    }
}
