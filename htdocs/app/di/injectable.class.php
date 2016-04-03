<?php

namespace DI;

/**
 * Base injectable class
 *
 * @throws InternalException
 */
abstract class Injectable {
    
    protected $DIContainer;

    public function __construct($container) {
        if ($container instanceof Container)
            $this->DIContainer = $container;
        else if ($container instanceof Injectable)
            $this->DIContainer = $container->getDI();
        else
            throw new \InternalException('Argument passed to DI\Injectable::__construct() must be either an instance of DI\Container or DI\Injectable');
    }

    public function getDI(): Container {
        return $this->DIContainer;
    }

    public function setDI(Container $container) {
        $this->DIContainer = $container;
    }

    public function __get($property) {

        $container = $this->DIContainer;

        if (!isset($container)) {
            throw new \InternalException(get_class($this) . '::$DIContainer is not set');
        }

        if ($property === 'di') {
            return $this->DIContainer;
        }

        if ($container->has($property)) {
            $dependency = $container->get($property);
            $this->$property = $dependency;
            return $dependency;
        }

        trigger_error('Access to undefined property ' . get_class($this) . '-> ' . $property, E_USER_WARNING);
        return null;
    }

    public function new() {
        return $this->di->create(get_class($this));
    }

}
