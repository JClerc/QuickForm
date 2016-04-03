<?php

/**
 * User exception
 *
 * It can be printed to user
 */
class UserException extends Exception {

    public function __construct($message = null, $code = 0, Exception $previous = null) {

        // Call parent constructor
        parent::__construct($message, $code, $previous);

        // Add a default message
        if (empty($message)) $this->message = 'Une erreur est survenue';
        
    }

}
