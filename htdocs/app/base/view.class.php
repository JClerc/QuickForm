<?php

namespace Base;
use DI\Injectable;

/**
 * Base view
 *
 * @throws InternalException
 */
abstract class View extends Injectable {

    private $data = [];

    public function use(array $data) {
        $this->data = $data;
    }

    protected function set(string $key, $value) {
        $this->data[$key] = $value;
    }

    protected function get(string $key) {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    protected function render(array $files) {
        extract($this->data, EXTR_SKIP);
        $css = $this->asset->getAllCSS();
        $js  = $this->asset->getAllJS();
        foreach ($files as $file) {
            $path = TEMPLATE . $file . '.php';
            if (is_file($path)) {
                require $path;
            } else {
                throw new \InternalException('Missing template file: ' . $path);
            }
        }
        exit;
    }

}
