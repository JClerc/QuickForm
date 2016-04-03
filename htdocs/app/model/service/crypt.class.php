<?php

namespace Model\Service;
use Model\Base\Service;

/**
 * Crypt service
 */
class Crypt extends Service {

    public function createHash($data) {
        return password_hash($data, PASSWORD_DEFAULT);
    }

    public function compareHash($data, $hash) {
        return password_verify($data, $hash);
    }
    
    public function equals($a, $b) {
        if (!is_string($a) or !is_string($b))
            return false;

        $diff = strlen($a) ^ strlen($b);
        for ($i = 0; $i < strlen($a) && $i < strlen($b); $i++)
            $diff |= ord($a[$i]) ^ ord($b[$i]);

        return $diff === 0;
    }

}
