<?php

namespace UtilityCli\Helper;

class DBConnection
{
    private $pdo;

    /**
     * DBConnection constructor.
     *
     * @param string|null $dbname Can be set from constant DB_NAME
     * @param string|null $host Can be set from constant DB_HOST
     * @param string|null $username Can be set from constant DB_USERNAME
     * @param string|null $password Can be set from constant DB_PASSWORD
     *
     * @throws \Exception
     */
    public function __construct($dbname = null, $host = null, $username = null, $password = null)
    {
        $dbname = isset($dbname) ? $dbname : (defined('DB_NAME') ? DB_NAME : null);
        $host = isset($host) ? $host : (defined('DB_HOST') ? DB_HOST : null);
        $username = isset($username) ? $username : (defined('DB_USERNAME') ? DB_USERNAME : null);
        $password = isset($password) ? $password : (defined('DB_PASSWORD') ? DB_PASSWORD : null);
        if (!isset($dbname) || !isset($host) || !isset($username) || !isset($password)) {
            throw new \Exception('Incomplete database info');
        }
        $this->pdo = new \PDO("mysql:host={$host};port=3309;dbname={$dbname};charset=UTF8", $username, $password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Select records from the database.
     *
     * @param string $sql SQL query.
     *
     * @return array The results returned in associative array.
     */
    public function select($sql)
    {
        $stm = $this->pdo->query($sql);
        $results = $stm->fetchAll(\PDO::FETCH_ASSOC);
        return $results;
    }

    /**
     * Get the number of records from a table.
     *
     * @param string $tblName
     *
     * @return int|null
     */
    public function getTableRecordsCount($tblName)
    {
        $stm = $this->pdo->query("SELECT COUNT(*) AS `count` FROM {$tblName}");
        $results = $stm->fetchAll(\PDO::FETCH_ASSOC);
        if (!empty($results) && count($results) > 0) {
            return $results[0]['count'];
        }
        return null;
    }

    /**
     * Create a mapping array from a SQL query.
     *
     * @param string $sql
     * @param string $keyName The column name used to be the map key.
     * @param string $valueName The column name used to be the map value.
     *
     * @return array An associative array where the keys are from the column values
     *   specified as key column, and the values are from the column values specified
     *   as the value column.
     */
    public function createMap($sql, $keyName, $valueName)
    {
        $map = [];
        $results = $this->select($sql);
        foreach ($results as $result) {
            $key = $result[$keyName];
            $value = $result[$valueName];
            if (!empty($key)) {
                $map[$key] = $value;
            }
        }
        return $map;
    }

    /**
     * Create a mapping array from a table.
     *
     * @param string $tableName
     * @param string $keyName The column name used to be the map key.
     * @param string $valueName The column name used to be the map value.
     *
     * @return array
     */
    public function createMapFromTable($tableName, $keyName, $valueName)
    {
        $sql = "Select * FROM $tableName";
        return $this->createMap($sql, $keyName, $valueName);
    }

    /**
     * Check whether a database table exists.
     *
     * @param string $tableName
     *
     * @return bool
     */
    public function tableExists($tableName)
    {
        $result = $this->select("SHOW TABLES LIKE '{$tableName}'");
        return (count($result) > 0);
    }

    /**
     * Get the PDO instance of the database.
     *
     * @return \PDO
     */
    public function getPDO()
    {
        return $this->pdo;
    }
}
