<?php

namespace Database;

/**
 * MySQL database class
 *
 * Allows to abstract SQL queries from MySQL database
 *
 * @see Database interface at database/Database.class.php
 * @throws InternalException
 */
class MySQL extends SQL {

    protected function escape($str) {
        if (ctype_alnum(str_replace('_', '', $str)))
            return '`' . $str . '`';
        else
            return $str;
    }

}
