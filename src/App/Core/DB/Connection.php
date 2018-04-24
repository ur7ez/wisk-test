<?php
/**
 * Created by PhpStorm.
 * User: Mike
 */

namespace App\Core\DB;

use \PDO;

class Connection implements IConnection
{
    /** @var PDO */
    protected $conn = null;

    /**
     * Connection constructor.
     * @param $host
     * @param $user
     * @param $pass
     * @param $dbName
     */
    public function __construct($host, $user, $pass, $dbName) {

        $dsn = "mysql:host=$host;dbname=$dbName";
        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
        ];
        $this->conn = new PDO($dsn, $user, $pass, $opt);
    }

    /**
     * @param $sql
     * @param array $data
     * @return array|bool
     */
    public function query($sql, $data = [])
    {
        if (!empty($data)) {
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute($data);
        } else {
            $result = $this->conn->query($sql);
        }
        if (is_bool($result) || !$result->columnCount()) {
            return $result;
        }

        return $result->fetchAll();
    }

    /**
     * @param $data
     * @return string
     */
    public function escape($data)
    {
        return $this->conn->quote($data);
    }
}