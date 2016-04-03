<?php

namespace DI;

/**
 * Dependencies Injection Container
 */
class Container {

    private $paths = [];
    private $dependencies = [];

    // Load set of paths
    public function load($classes) {
        $this->paths = $classes;
    }

    // Create from class name
    public function create($class) {
        return new $class($this);
    }

    // Share same object
    public function share($key, $object) {
        $key = strtolower($key);
        if (is_object($object)) {
            $this->dependencies[$key] = $object;
        } else {
            $this->share($key, $this->create($object));
        }
    }
    
    // Add unique object that will be created when called
    public function add($key, $class) {
        $key = strtolower($key);
        $this->dependencies[$key] = function () use ($class) {
            return $this->create($class);
        };
    }

    // Get dependency    
    public function get($key) {
        $key = strtolower($key);
        if (isset($this->dependencies[$key])) {
            $dependency = $this->dependencies[$key];
            return is_callable($dependency) ? $dependency() : $dependency;
        } else if (isset($this->paths[$key])) {
            $path = $this->paths[$key];
            $dependency = $this->create($path);
            $this->dependencies[$key] = $dependency;
            return $dependency;
        }
        return null;
    }

    // Check if it exists
    public function has($key) {
        $key = strtolower($key);
        return isset($this->dependencies[$key]) or isset($this->paths[$key]);
    }
    
}
