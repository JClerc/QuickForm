<?php

namespace Database;
use PDO;

/**
 * Database interface
 *
 * Allows to abstract SQL queries from database type (MySQL, SQLite, ...)
 *
 * @example $db->select('id')->from('users')->where('username', $user)->andWhere('password', $pwd)->get();
 * @example $db->insert(['username' => $user, 'password' => $pwd])->into('users');
 * @example $db->update(['username' => $user])->where('id', $id)->into('users');
 * @example $db->delete('users')->where('id', '>', $id)->confirm();
 */
Interface Database {

    /*
     * --------------------------------
     *          Initialisation
     * --------------------------------
     *
     */

    public function __construct(array $access);


    /*
     * --------------------------------
     *           PDO Methods
     * --------------------------------
     *
     */

    // Return PDO object
    public function getPdo(): PDO;

    // Set PDO object
    public function setPdo(PDO $pdo);

    // Set attribute
    public function setAttribute($key, $value);

    // Close connection
    public function close();

    // Get last inserted ID
    public function lastInsertId($name = null): int;

    // Bind an arg to query
    public function bind($value);


    /*
     * --------------------------------
     *           Debugging
     * --------------------------------
     *
     */

    public function debug($mode = true);


    /*
     * --------------------------------
     *         Main function
     * --------------------------------
     *
     */

    // Execute $sql with $params
    public function query($sql, $params = null);

    // Fetch all rows
    public function all($mode = null);

    // Fetch one row
    public function get($mode = null);

    // Count rows selected/modified
    public function count($table = null);


    /*
     * --------------------------------
     *           SQL methods
     * --------------------------------
     *
     */

    public function select($columns = '*');

    public function from($table);

    public function insert(array $data);

    public function update(array $data);

    public function delete($table = null);

    public function confirm();

    public function into($table);

    public function in($table);


    /*
     * --------------------------------
     *           SQL filters
     * --------------------------------
     *
     */

    public function where($column = null, $operator = null, $value = null);

    public function andWhere($column, $operator, $value = null);

    public function orWhere($column, $operator, $value = null);

    public function whereNull($column, $not = false);

    public function orWhereNull($column, $not = false);

    public function andWhereNull($column, $not = false);

    public function whereNotNull($column);

    public function orWhereNotNull($column);

    public function andWhereNotNull($column);

    public function orderBy($column = null, $order = 'ASC');

    public function groupBy($column = null);

    public function skip($offset = null);

    public function take($count = null);

}
