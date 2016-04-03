<?php

namespace Model\Mapper;
use Model\Base\Mapper;

/**
 * Cookie data management
 */
class Cookie extends Mapper {

    const EXPIRE_1_DAY = 86400;
    const EXPIRE_1_WEEK = 86400 * 7;
    const EXPIRE_1_MONTH = 86400 * 30;

    public function get($key) {
        return $_COOKIE[$key] ?? null;
    }

    public function set($key, $value, $expire = self::EXPIRE_1_WEEK) {
        setcookie($key, $value, time() + $expire, '/');
    }

    public function has($key) {
        return isset($_COOKIE[$key]);
    }

    public function delete($key) {
        setcookie($key, '', time() - 1);
    }

}
