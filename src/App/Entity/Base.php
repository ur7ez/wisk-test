<?php
/**
 * Created by PhpStorm.
 * User: Mike Nykytenko
 */

namespace App\Entity;

use App\Core\DB\IConnection;

abstract class Base
{

    /** @var \App\Core\DB\IConnection */
    protected $conn;

    abstract function getTableName($choose = 0);

    abstract function checkFields($data);

    abstract function getFields($choose = 0);

    /**
     * Base constructor
     * @param \App\Core\DB\IConnection $connection
     */
    public function __construct(IConnection $connection) {
        $this->conn = $connection;
    }

    /**
     * @param array $filter
     * @param int $table_id
     * @param null $limit
     * @param int $offset
     * @param array $order
     * @param int $get_total
     * @return mixed
     */
    public function list($filter = [], $table_id = 0, $limit = null, $offset = 0, $order = [], &$get_total = null) {
        $fields = $this->getFields($table_id);
        $where = [];
        $strWhere = '';
        foreach ($filter as $fieldName => $value) {
            if (!in_array($fieldName, $fields)) {
                continue;
            }
            //$fieldName = $this->conn->escape($fieldName);
            $value = $this->conn->escape($value);
            $where[] = "$fieldName = $value";
        }
        if (!empty($where)) {
            $strWhere = ' AND ' . implode(' AND ', $where);
        }
        $order_clause = (!empty($order)) ?
            ' ORDER BY ' . str_replace(['=', '-1', '1'], [' ', 'DESC', 'ASC'], http_build_query($order, null, ', ')) : '';
        $limit_clause = ($limit) ? ' LIMIT ' . $offset . ', ' . $limit : '';

        $sql = 'SELECT * FROM ' . $this->getTableName($table_id) . ' WHERE 1 ' . $strWhere;
        $result = $this->conn->query($sql . $order_clause . $limit_clause);
        if (isset($get_total)) {
            $get_total = ($limit_clause === '') ? count($result) : count($this->conn->query($sql));
        }
        return $result;
    }

    /**
     * @param $id
     * @param int $limit
     *          if $limit is set to 0 then no LIMIT is applied in SQL query
     * @param int $table_id
     * @return null
     */
    public function getById($id, $limit = 1, $table_id = 0) {
        $limit_clause = (empty($limit)) ? '' : ' LIMIT ' . $limit;
        $sql = 'SELECT * FROM ' . $this->getTableName($table_id)
            . ' WHERE id = ' . $this->conn->escape($id) . $limit_clause;
        $result = $this->conn->query($sql);

        return isset($result[0]) ? (($limit !== 1) ? $result : $result[0]) : null;
    }


    /**
     * @param array $data - an assoc. array 'field => value'
     * @param null $id - if $id is set - will update existing record with `id` = $id
     * @param int $table_id - source table id from App\Entity->getTableName()
     * @return mixed
     */
    public function save($data, $id = null, $table_id = 0) {
        $this->checkFields($data);

        $fields = $this->getFields($table_id);

        $values = [];
        $id = (int)$id;

        foreach ($data as $key => $val) {
            if (!in_array($key, $fields)) {
                unset($data[$key]);
                continue;
            }
            $this->conn->escape($val);
            if ($id > 0) {
                $values[] = "$key = ?";
            } else {
                $values[] = trim($val);
            }
        }
        $cols = '`' . implode('`, `', array_keys($data)) . '`';

        if ($id > 0) {   // update existing record with id = $id
            $values = implode(',', $values);
            $data[] = $id;
            $sql = "UPDATE " . $this->getTableName($table_id) . " SET $values WHERE id = ?";
        } else {        // add a new record
            $vals = rtrim(str_repeat('?,', count($data)), ',');
            $sql = "INSERT INTO " . $this->getTableName($table_id) . " ($cols) VALUES ($vals)";
        }

        return $this->conn->query($sql, array_values($data));
    }

    /**
     * @param $id
     * @param $table_id
     * @return mixed
     */
    public function delete($id, $table_id = 0) {
        $id = $this->conn->escape(intval($id));
        $sql = 'DELETE FROM ' . $this->getTableName($table_id)
            . ' WHERE id = ' . $id;
        return $this->conn->query($sql);
    }

    /**
     * Filters data array by a given id
     * @param array $array
     * @param string $field - a field in array to be inspected by a given ID value
     * @param $ID
     * @return array
     */
    static function filterArrayFromID($array, $field = '', $ID) {
        return array_values(
            array_filter($array, function ($arrayValue) use ($field, $ID) {
                return $arrayValue[$field] == $ID;
            }));
    }
}