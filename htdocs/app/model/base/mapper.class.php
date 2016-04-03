<?php

namespace Model\Base;
use Base\Model;

/**
 * Base data mapper class
 */
abstract class Mapper extends Model {
    
    abstract public function get($key);

    abstract public function set($key, $value, $expire = 0);

    abstract public function has($key);

    abstract public function delete($key);

}
