<?php

namespace Base;
use DI\Injectable;

/**
 * Base controller
 *
 * @throws InternalException
 */
abstract class Controller extends Injectable {

    private $data = [];

    /**
     * Load view
     * 
     * @example view('command') will use current Controller as resource
     * @example view('Controller', 'command')
     */
    public function view(string $command, string $other = null) {

        if (isset($other)) {
            $resource = $command;
            $command = $other;
        } else {
            $resource = get_class_name($this);
        }

        try {

            // Create View object
            $view = $this->di->create('View\\' . $resource);

            // Get avaliables (public and not inherited) methods
            $methods = get_class_methods($view);
            $base = get_class_methods('Base\View');
            $avaliable = array_diff($methods, $base);

            if (in_array($command, $avaliable)) {

                // Pass data
                $view->use($this->data);
                
                // Execute command
                $view->$command();

                // Everything is OK
                return;
            }

        } catch (\Exception $e) {}

        throw new \InternalException('Error with view: "' . $resource . '" and method: "' . $command . '"');

    }

    // Set data variable
    protected function set(string $key, $value) {
        $this->data[$key] = $value;
        return $this;
    }

    // Get data variable
    protected function get(string $key) {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

}
