<?php

namespace Model\Service;
use Model\Base\Service;

/**
 * Flash service
 *
 * @example $flash->success('Welcome !')
 * @example $flash->error('An error occured..')
 * @throws InternalException
 */
Class Flash extends Service {

    const SESSION_KEY = 'flash';

    private $classes = [
        'success' => 'alert alert-success',
        'info'    => 'alert alert-info',
        'warn'    => 'alert alert-warning',
        'error'   => 'alert alert-danger'
    ];

    public function build() {
        if ($this->exists()) {

            // Get class
            $class = $this->classes[ $this->getType() ];

            // Make html
            $html = '<div class="' . $class . '">' . e($this->getMessage()) . '</div>';

            // Prevent duplicate
            $this->clear();

            // And return it
            return $html;

        }
    }

    public function print() {
        echo $this->build();
    }

    public function success(string $message) {
        $this->set('success', $message);
    }

    public function error(string $message) {
        $this->set('error', $message);
    }

    public function warn(string $message) {
        $this->set('warning', $message);
    }

    public function info(string $message) {
        $this->set('info', $message);
    }

    private function set(string $type, string $message) {
        if (isset($this->classes[$type])) {
            // Store into session
            $this->session->set(self::SESSION_KEY, [
                'type' => $type,
                'message' => $message
            ]);
        } else {
            throw new \InternalException('Type is incorrect');
        }
    }

    public function getType() {
        return $this->exists() ? $this->session->get(self::SESSION_KEY)['type'] : null;
    }

    public function getMessage() {
        return $this->exists() ? $this->session->get(self::SESSION_KEY)['message'] : null;
    }

    public function get() {
        return $this->session->get(self::SESSION_KEY);
    }

    public function exists() {
        return is_array($this->get());
    }

    public function clear() {
        $this->session->delete(self::SESSION_KEY);
    }

}
