<?php
/**
 * @package ${PACKAGE}
 * @copyright 2017
 * @author
 */


namespace Copernicus\Core\Database;

use PDO;
use Copernicus\Core\Helpers\GetConfigFile as Config;
use phpDocumentor\Reflection\Types\Array_;
use phpDocumentor\Reflection\Types\Integer;

class MysqlStorage implements I_DBStorage
{
    private static $instance = null;

    private $servername;
    private $dbName;
    private $username;
    private $password;
    private $charset = "UTF8";

    private $configFile;

    const CHARSET = "UTF8";

    private $connector = null;

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new MysqlStorage();
        }

        return self::$instance;
    }

    protected function __construct()
    {
        $this->config = new Config('db');
        $fileContent = $this->config->getFileContent();
        $this->$servername = $fileContent['servername'];
        $this->$dbName = $fileContent['dbname'];
        $this->$username = $fileContent['username'];
        $this->$password = $fileContent['password'];
        $this->$charset = $fileContent['charset'];
        $this->getConnector();
    }

    private function __clone()
    {
    }

    private function __wakeup()
    {
    }

    private function __destruct()
    {
        $this->closeConnector();
    }

    /**
     * @return mixed
     */
    public function getServername()
    {
        return $this->servername;
    }

    /**
     * @param mixed $servername
     */
    protected function setServername($servername)
    {
        $this->servername = $servername;
    }

    /**
     * @return mixed
     */
    public function getDbName()
    {
        return $this->dbName;
    }

    /**
     * @param mixed $dbName
     */
    protected function setDbName($dbName)
    {
        $this->dbName = $dbName;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    protected function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    protected function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getCharset()
    {
        return $this->charset;
    }

    /**
     * @param string $charset
     */
    public function setCharset($charset)
    {
        $this->charset = $charset;
    }

    private function getConnector()
    {
        return $this->connector;
    }

    private function setConnector($conn)
    {
        $dsn = "mysql:host=".$this->servername.";dbname=".$this->$dbName.";charset=".$this->$charset;
        $opt = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        try {
            $this->connector = new PDO($dsn, $this->$username, $this->$password, $opt);
        } catch (PDOException $e) {
            echo "Connection failed: ".$e->getMessage();
        }
    }

    private function closeConnector()
    {
        $this->connector = null;
    }

    public function findAll(String $table, Array $markers = array())
    {
        $result = false;
        if (empty($markers)) {
            $sql = sprintf("SELECT * FROM `%s`", $table);
        } else {
            $tmp = $this->getValues($markers);
            $sql = sprintf("SELECT * FROM `%s` WHERE %s", $table, $tmp[0]);
            $values = $tmp[1];
        }
        try {
            $stmt = $this->connector->prepare($sql);
            !empty($values) ? $stmt->execute($values) : $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC, $table);
        } catch (Exception $e) {
            throw $e;
        }

        return $result;
    }

    public function findById(String $table, Integer $id)
    {
        $result = false;
        try {
            if (is_int($id)) {
                $stmt = $this->connector->prepare(sprintf("SELECT * FROM `%s` WHERE ID=%d", $table, $id));
            }

            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC, $table);
        } catch (Exception $e) {
            throw $e;
        }

        return $result;
    }

    public function findByAlias(String $table, Array $markers = array())
    {
        $result = false;
        if (empty($markers)) {
            $sql = sprintf("SELECT * FROM `%s`", $table);
        } else {
            $tmp = $this->getValues($markers);
            $sql = sprintf("SELECT * FROM `%s` WHERE %s", $table, $tmp[0]);
            $values = $tmp[1];
        }

        try {
            $stmt = $this->connector->prepare($sql);
            !empty($values) ? $stmt->execute($values) : $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC, $table);
        } catch (Exception $e) {
            throw $e;
        }

        return $result;
    }

    public function insert(String $table, Array $data)
    {
        try {
            $this->connector->beginTransaction();
            $tmp = $this->getValues($data);
            $stmt = $this->connector->prepare(sprintf("INSERT INTO `%s` VALUES %s", $table, $tmp[0]));
            $stmt->execute($tmp[1]);
            $this->connector->commit();
        } catch (Exception $e) {
            $this->connector->rollback();
            throw $e;
        }

        return true;
    }

    public function countAll(String $table, Array $markers = array(), Array $group = array())
    {
        $result = false;
        if (empty($markers)) {
            $sql = sprintf("SELECT COUNT(*) FROM `%s`", $table);
        } else {
            $tmp = $this->getValues($markers);
            $sql = sprintf("SELECT COUNT(*) FROM `%s` WHERE %s", $table, $tmp[0]);
            $values = $tmp[1];
        }

        if (!empty($group)) {
            $tmpGroup = implode(',', $group);
            $sql .= " GROUP BY ".$tmpGroup;
        }

        try {
            $stmt = $this->connector->prepare($sql);
            !empty($values) ? $stmt->execute($values) : $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC, $table);
        } catch (Exception $e) {
            throw $e;
        }

        return $result;
    }

    public function count(String $table, Array $markers = array(), Array $columns = array(), Array $group = array())
    {
        $result = false;
        if (empty($columns)) {
            return $this->countAll($table, $markers, $group);
        } else {
            $columnsToCount = $this->getCountColumns($columns);
        }

        if (empty($markers)) {
            $sql = sprintf("SELECT `%s` FROM `%s`", $columnsToCount, $table);
        } else {
            $tmp = $this->getValues($markers);
            $sql = sprintf("SELECT `%s` FROM `%s` WHERE %s", $columnsToCount, $table, $tmp[0]);
            $values = $tmp[1];
        }

        if (!empty($group)) {
            $tmpGroup = implode(',', $group);
            $sql .= " GROUP BY ".$tmpGroup;
        }

        try {
            $stmt = $this->connector->prepare($sql);
            !empty($values) ? $stmt->execute($values) : $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC, $table);
        } catch (Exception $e) {
            throw $e;
        }


        return $result;
    }

    private function getValues(Array $markers)
    {
        $where = '';
        foreach ($markers as $key => $value) {
            if (count($markers) == $key + 1) {
                $where .= "`".$value[0]."`".$value[1].":$value[2]";
            } else {
                $where .= "`".$value[0]."`".$value[1].":$value[2] AND ";
            }
            $values[] = $value;
        }

        return [$where, $values];
    }

    private function getCountColumns($columns)
    {
        if (!empty($columns)) {
            $sql = '';
            $count = count($value);
            foreach ($columns as $key => $value) {
                $tmpVar = $value."_cnt";
                if ($ket == $count) {
                    $sql .= "COUNT(".$value.") as $tmpVar";
                } else {
                    $sql .= "COUNT(".$value.") as ".$tmpVar.", ";
                }

            }
        } else {
            $sql = 'COUNT(*)';
        }

        return $sql;
    }
}