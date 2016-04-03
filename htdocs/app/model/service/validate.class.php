<?php

namespace Model\Service;
use Model\Base\Service;
use UserException, InternalException;

/**
 * Validate service
 *
 * @throws UserException
 * @throws InternalException
 */
class Validate extends Service {

    const REGEX_EMAIL = '/^[0-9a-zA-Z-_\.]{3,32}\@[0-9a-zA-Z\-\.]{3,32}\.[a-zA-Z]{2,32}$/';

    public function true($variable, $message = null) {
        if (!$variable) throw new UserException($message);
        return $this;
    }

    public function false($variable, $message = null) {
        if ($variable) throw new UserException($message);
        return $this;
    }

    public function notNull($variable, $message = null) {
        if (is_null($variable)) throw new UserException($message);
        return $this;
    }

    public function notEmpty($variable, $message = null) {
        if (empty($variable)) throw new UserException($message);
        return $this;
    }

    public function isEmpty($variable, $message = null) {
        if (!empty($variable)) throw new UserException($message);
        return $this;
    }

    public function isScalar($variable, $message = null) {
        if (!is_scalar($variable)) throw new UserException($message);
        return $this;
    }

    public function isString($variable, $message = null) {
        if (!is_string($variable)) throw new UserException($message);
        return $this;
    }

    public function isEmail($variable, $message = null) {
        return $this->regex($variable, self::REGEX_EMAIL, $message);
    }

    public function isInt($variable, $message = null) {
        return $this->isInteger($variable, $message);
    }

    public function isInteger($variable, $message = null) {
        if (!is_int($variable) and !ctype_digit($variable)) throw new UserException($message);
        return $this;
    }

    public function min($variable, $length, $message = null) {
        if ((is_string($variable) and strlen($variable) < $length)
            or (is_numeric($variable) and $variable < $length)) throw new UserException($message);
        return $this;
    }

    public function max($variable, $length, $message = null) {
        if ((is_string($variable) and strlen($variable) > $length)
            or (is_numeric($variable) and $variable > $length)) throw new UserException($message);
        return $this;
    }

    public function between($variable, $length, $message = null) {
        $size = 0;
        if (is_string($variable)) $size = strlen($variable);
        else if (is_numeric($variable)) $size = floatval($variable);
        else throw new InternalException('Validate::between() called but $variable is neither string nor number');
        if ($size < $length[0] or $size > $length[1]) throw new UserException($message);
        return $this;
    }

    public function exists($variable, $message = null) {
        if (!is_object($variable) or !is_callable([$variable, 'exists']) or !$variable->exists()) throw new UserException($message);
        return $this;
    }

    public function isArray($variable, $message = null) {
        if (!is_array($variable)) throw new UserException($message);
        return $this;
    }

    public function regex($variable, $regex, $message = null) {
        if (!is_string($variable) or !preg_match($regex, $variable)) throw new UserException($message);
        return $this;
    }

}
