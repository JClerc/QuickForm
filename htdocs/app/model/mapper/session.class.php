<?php

namespace Model\Mapper;
use Model\Base\Mapper;

/**
 * Session data management
 */
class Session extends Mapper {

    public function start() {
        session_start();
    }

    public function get($key) {
        return $_SESSION[$key] ?? null;
    }

    public function set($key, $value, $expire = 0) {
        $_SESSION[$key] = $value;
    }

    public function has($key) {
        return isset($_SESSION[$key]);
    }

    public function delete($key) {
        unset($_SESSION[$key]);
    }

}
