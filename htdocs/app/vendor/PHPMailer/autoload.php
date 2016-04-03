<?php

spl_autoload_register(function ($classname) {
    $file = __DIR__ . DIRECTORY_SEPARATOR . 'class.' . strtolower($classname) . '.php';
    if (is_file($file)) {
        require_once $file;
    }
});
