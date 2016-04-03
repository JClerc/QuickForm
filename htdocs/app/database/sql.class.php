<?php

namespace Database;
use PDO, InternalException;

/**
 * SQL database class
 *
 * Allows to abstract SQL queries from database
 *
 * @see Database interface at database/Database.class.php
 * @throws InternalException
 */
abstract class SQL implements Database {

    /*
     * --------------------------------
     *            Variables
     * --------------------------------
     *
     */

    protected $db;
    protected $debug = false;

    protected $lastQuery;

    protected $columns = '*';
    protected $table;
    protected $where = '';
    protected $orderBy = '';
    protected $groupBy = '';
    protected $limit;
    protected $offset;
    protected $values;
    protected $args;


    /*
     * --------------------------------
     *          Initialisation
     * --------------------------------
     *
     */

    public function __construct(array $access) {
        $dsn = 'mysql:host=' . $access[0] . ';dbname=' . $access[1] . ';charset=utf8;';
        $options = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES "UTF8";',
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $this->db = new PDO($dsn, $access[2], $access[3], $options);
        } catch (Exception $e) {
            throw new InternalException('Cant connect to database: ' . $e->getMessage());
        }
    }


    /*
     * --------------------------------
     *           PDO Methods
     * --------------------------------
     *
     */

    public function getPdo(): PDO {
        if (!isset($this->db))
            throw new InternalException('Database wasn\'t created');
        return $this->db;
    }

    public function setPdo(PDO $pdo) {
        return $this->db = $pdo;
    }

    public function setAttribute($key, $value) {
        $this->db->setAttribute($key, $value);
        return $this;
    }

    public function close() {
        $this->beginQuery();
        $this->db = null;
        return $this;
    }

    public function lastInsertId($name = null): int {
        return intval($this->db->lastInsertId($name));
    }

    public function bind($value) {
        if (is_array($value)) {
            foreach ($value as $v) {
                $this->args[] = $v;
            }
        } else {
            $this->args[] = $value;
        }
        return $this;
    }


    /*
     * --------------------------------
     *           Debugging
     * --------------------------------
     *
     */

    public function debug($mode = true) {
        $this->debug = (bool) $mode;
        return $this;
    }


    /*
     * --------------------------------
     *         Main function
     * --------------------------------
     *
     */

    public function query($sql, $params = null) {
        if ($this->debug) {
            $this->debugQuery($sql, $params);
        }
        if (empty($params)) {
            $stmt = $this->db->query($sql);
        } else {
            if (!is_array($params))
                $params = array($params);
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
        }
        $this->lastQuery = $stmt;
        return $stmt;
    }

    public function all($mode = null) {
        if ($this->action !== 'SELECT')
            throw new InternalException('Invalid request: ::all() called, but not in a select query');
        return $this->buildSelect()->fetchAll($mode);
    }

    public function get($mode = null) {
        if ($this->action !== 'SELECT')
            throw new InternalException('Invalid request: ::get() called, but not in a select query');
        if (!isset($this->lastQuery))
            $this->lastQuery = $this->buildSelect();
        return $this->lastQuery->fetch($mode);
    }

    public function count($table = null) {
        if (isset($table)) {
            return (int) $this->select('COUNT(*) AS number')->from($table)->get()->number;
        } else if ($this->action === 'SELECT') {
            
            $columns = $this->columns;
            $lastQuery = $this->lastQuery;
            
            $this->columns = 'COUNT(*) AS number';
            $return = (int) $this->get()->number;
            
            $this->lastQuery = $lastQuery;
            $this->columns = $columns;
            return $return;

        } else if (isset($this->lastQuery) and in_array($this->action, array('INSERT', 'UPDATE', 'DELETE'))) {
            return $this->lastQuery->rowCount();
        }
        throw new InternalException('Invalid request: Count called in an unexpected way');
    }


    /*
     * --------------------------------
     *           SQL methods
     * --------------------------------
     *
     */

    public function select($columns = '*') {
        $this->beginQuery('SELECT');
        if (is_array($columns)) {
            $escaped = [];
            foreach ($columns as $c) $escaped[] = $this->escape($c);
            $this->columns = implode(', ', $escaped);
        } else {
            $this->columns = $columns;
        }
        return $this;
    }

    public function from($table) {
        $this->table = $table;
        return $this;
    }

    public function insert(array $data) {
        $this->beginQuery('INSERT');
        $this->with($data);
        return $this;
    }

    public function update(array $data) {
        $this->beginQuery('UPDATE');
        $this->with($data);
        return $this;
    }

    public function delete($table = null) {
        $this->beginQuery('DELETE');
        if (!is_null($table))
            $this->table = $table;
        return $this;
    }

    public function confirm() {
        if ($this->action === 'DELETE') {
            return $this->buildDelete();
        } else {
            throw new InternalException('Cant call ::confirm() unless for DELETE');
        }
    }

    public function into($table) {
        $this->table = $table;
        return $this->build();
    }

    public function in($table) {
        $this->table = $table;
        return $this->build();
    }


    /*
     * --------------------------------
     *           SQL filters
     * --------------------------------
     *
     */

    public function where($column = null, $operator = null, $value = null) {
        $this->where = '';
        if (is_null($column)) {
            return $this;
        } else if (is_null($operator)) {
            $this->where = $column;
            return $this;
        } else if (is_array($operator)) {
            $this->where = $column;
            return $this->bind($operator);
        } else {
            return $this->andWhere($column, $operator, $value);
        }
    }

    public function andWhere($column, $operator, $value = null) {
        if (is_null($value)) {
            $value = $operator;
            $operator = '=';
        }
        return $this->buildWhere('AND', $column, $operator, $value);
    }

    public function orWhere($column, $operator, $value = null) {
        if (is_null($value)) {
            $value = $operator;
            $operator = '=';
        }
        return $this->buildWhere('OR', $column, $operator, $value);
    }

    public function whereNull($column, $not = false) {
        $this->where = '';
        $is = ($not ? 'IS NOT' : 'IS');
        return $this->buildWhere('AND', $column, $is, NULL);
    }

    public function orWhereNull($column, $not = false) {
        $is = ($not ? 'IS NOT' : 'IS');
        return $this->buildWhere('OR', $column, $is, NULL);
    }

    public function andWhereNull($column, $not = false) {
        $is = ($not ? 'IS NOT' : 'IS');
        return $this->buildWhere('AND', $column, $is, NULL);
    }

    public function whereNotNull($column) {
        return $this->whereNull($column, true);
    }

    public function orWhereNotNull($column) {
        return $this->orWhereNull($column, true);
    }

    public function andWhereNotNull($column) {
        return $this->andWhereNull($column, true);
    }

    public function orderBy($column = null, $order = 'ASC') {
        $this->lastQuery = null;
        if (is_null($column)) {
            $this->orderBy = '';
        } else {
            if (!empty($this->orderBy))
                $this->orderBy .= ', ';
            $this->orderBy .= $this->escape($column) . ' ' . $order;
        }
        return $this;
    }

    public function groupBy($column = null) {
        $this->lastQuery = null;
        if (is_null($column)) {
            $this->groupBy = '';
        } else if (is_array($column)) {
            foreach ($column as $c) {
                if (!empty($this->groupBy))
                    $this->groupBy .= ', ';
                $this->groupBy .= $this->escape($c) . ' ' . $order;
            }
        } else {
            $this->groupBy = $this->escape($column);
        }
        return $this;
    }

    public function skip($offset = null) {
        $this->lastQuery = null;
        if (is_null($offset))
            $this->offset = null;
        else
            $this->offset = (int) $offset;
        return $this;
    }

    public function take($count = null) {
        $this->lastQuery = null;
        if (is_null($count))
            $this->limit = null;
        else
            $this->limit = (int) $count;
        return $this;
    }


    /*
     * --------------------------------
     *         protected methods
     * --------------------------------
     *
     */

    protected function beginQuery($action = null) {
        $this->columns = '*';
        $this->where = '';
        $this->orderBy = '';
        $this->groupBy = '';
        $this->action = $action;
        $this->limit = null;
        $this->offset = null;
        $this->values = null;
        $this->args = null;
        $this->lastQuery = null;
    }

    protected function buildWhere($logical, $column, $operator, $value) {
        $column = $this->escape($column);
        $this->lastQuery = null;
        if (!empty($this->where))
            $this->where .= ' ' . $logical . ' ';
        if (is_array($value)) {
            $this->where .= $column . ' ' . $operator . ' ';
            if (stripos($operator, 'between') !== false) {
                $i = 0;
                foreach ($value as $v) {
                    if ($i++)
                        $this->where .= ' AND ';
                    $this->args[] = $v;
                    $this->where .= '?';
                }
            } else {
                $this->where .= '(';
                $i = 0;
                foreach ($value as $v) {
                    if ($i++)
                        $this->where .= ', ';
                    $this->args[] = $v;
                    $this->where .= '?';
                }
                $this->where .= ')';
            }
        } else if (is_null($value)) {
            $this->where .= $column . ' ' . $operator . ' NULL';
        } else if ($value === true) {
            $this->where .= $column . ' ' . $operator . ' 1';
        } else if ($value === false) {
            $this->where .= $column . ' ' . $operator . ' 0';
        } else {
            $this->where .= $column . ' ' . $operator . ' ?';
            $this->args[] = $value;
        }
        return $this;
    }

    protected function buildSelect() {
        if (empty($this->table))
            throw new InternalException('Uncomplete request: Missing table name');
        if (empty($this->columns))
            $this->columns = '*';
        $sql = 'SELECT ' . $this->escape($this->columns) . ' FROM ' . $this->escape($this->table);
        if (empty($this->where))
            $sql .= ' WHERE 1';
        else
            $sql .= ' WHERE ' . $this->where;
        if (!empty($this->groupBy))
            $sql .= ' GROUP BY ' . $this->groupBy;
        if (!empty($this->orderBy))
            $sql .= ' ORDER BY ' . $this->orderBy;
        if (!empty($this->limit))
            $sql .= ' LIMIT ' . $this->limit;
        if (!empty($this->offset))
            $sql .= ' OFFSET ' . $this->offset;
        return $this->query($sql, $this->args);
    }

    protected function buildInsert() {
        if (empty($this->table))
            throw new InternalException('Uncomplete request: Missing table name');
        if (empty($this->args))
            throw new InternalException('Uncomplete request: Missing values');
        if (empty($this->values) or count($this->values) < count($this->args)) {
            foreach ($this->args as $value) {
                $this->values[] = '?';
            }
        }
        $sql = 'INSERT INTO ' . $this->escape($this->table);
        if (!empty($this->columns)) {
            $sql .= ' (';
            foreach ($this->columns as $column) {
                $sql .= $this->escape($column) . ',';
            }
            $sql = rtrim($sql, ',');
            $sql .= ')';
        }
        $sql .= ' VALUES (' . implode(', ', $this->values) . ')';
        return $this->query($sql, $this->args);
    }

    protected function buildUpdate() {
        if (empty($this->table) or empty($this->args) or empty($this->columns))
            throw new InternalException('Uncomplete request');
        if (count($this->columns) > count($this->args))
            throw new InternalException('Invalid request: There are more columns to update than values');
        $sql = 'UPDATE ' . $this->escape($this->table) . ' SET ';
        foreach ($this->columns as $column) {
            $updateValues[] = $this->escape($column) . ' = ?';
        }
        $sql .= implode(', ', $updateValues);
        if (empty($this->where))
            $sql .= ' WHERE 1';
        else
            $sql .= ' WHERE ' . $this->where;
        return $this->query($sql, $this->args);
    }

    protected function buildDelete() {
        if (empty($this->table))
            throw new InternalException('Uncomplete request: Missing table name');
        $sql = 'DELETE FROM ' . $this->escape($this->table);
        if (empty($this->where))
            $sql .= ' WHERE 1';
        else
            $sql .= ' WHERE ' . $this->where;
        return $this->query($sql, $this->args);
    }

    protected function with(array $data) {
        $this->args = null;
        $this->lastQuery = null;
        $this->columns = array();
        $this->values = array();
        $this->args = array();
        foreach ($data as $key => $value) {
            $this->columns[] = $key;
            $this->values[] = '?';
            $this->args[] = $value;
        }
        if (array_values($data) === $data) {
            $this->columns = null;
        }
        return $this;
    }

    protected function build() {
        switch ($this->action) {
            case 'INSERT': return $this->buildInsert();
            case 'UPDATE': return $this->buildUpdate();
            case 'DELETE': return $this->buildDelete();
            default: throw new InternalException('Error Processing Request');
        }
    }

    protected function debugQuery($sql, $params) {
        echo PHP_EOL . '<b>Debugging:</b><pre>';
        echo 'SQL: "' . $sql . '"' . PHP_EOL;
        if (is_null($params)) {
            echo 'No args given.';
        } else if (!is_array($params)) {
            echo 'Args: ';
            echo PHP_EOL . '- [';
            echo str_replace('double', 'float', gettype($params));
            echo '] => ' . $params;
        } else {
            echo 'Args: ';
            foreach ($params as $value) {
                echo PHP_EOL . '- [';
                echo str_replace('double', 'float', gettype($value));
                echo '] => ' . $value;
            }
        }
        echo PHP_EOL . PHP_EOL . '</pre>';
        return $this;
    }

    protected function escape($str) {
        return $str;
    }

}
