<?php

namespace Model\Service;
use Model\Base\Service;

/**
 * Request service
 */
class Request extends Service {

    private $resource = null;
    private $command = null;
    private $args = [];

    public function parse(string $uri) {

        // Remove leading and trailing slash
        $uri = trim($uri, '/');

        // If app is in folder, remove from uri
        if (strlen(ROOT) and strpos($uri, ROOT) === 0) {
            $uri = substr($uri, strlen(ROOT));
        }

        // Get route parts of URL
        $parts = empty($uri) ? [] : explode('/', $uri);

        // Save uri to object variable
        $this->setRoute($parts);

    }

    public function setRoute(array $parts) {

        if (isset($parts[0]) and !ctype_alnum($parts[0]) or isset($parts[1]) and !ctype_alnum($parts[1])) {
            // If parts is not alnum, go to error
            $parts = ['error'];
        }

        // First part is not set
        if (!isset($parts[0]) or empty($parts[0])) {
            $parts[0] = 'index';
        }
        // Second part is not set
        if (!isset($parts[1]) or empty($parts[1])) {
            $parts[1] = 'index';
        }

        // Resource is first part
        $this->resource = strtolower(array_shift($parts));

        // Command is second one
        $this->command = strtolower(array_shift($parts));
        
        // Every other parts is args
        $this->args = $parts;

    }

    public function setPost($post) {
        $this->post = $post;
    }

    public function getRoute() {
        return [$this->getResource(), $this->getCommand(), $this->getArgs()];
    }

    public function getResource() {
        return $this->resource;
    }

    public function getCommand() {
        return $this->command;
    }

    public function getArgs() {
        return $this->args;
    }

    public function getArg(int $index) {
        return $this->args[$index] ?? null;
    }

    public function hasArg(int $index) {
        return isset($this->args[$index]);
    }

    public function isPost() {
        return !empty($this->post) and is_array($this->post);
    }

    public function getPost() {
        return $this->post;
    }

    public function isGet() {
        return !$this->isPost();
    }

    public function go($path = null) {
        $this->redirect($path);
    }

    public function redirect($path = null) {

        // Header location
        $location = 'Location: ' . HTTP_ROOT;

        // $path is parts of route
        if (is_array($path)) $path = implode('/', $path);

        // Add path to location
        if (!empty($path)) $location .= trim($path, '/') . '/';

        // And redirect
        header($location);

        // Stop current script
        exit;

    }

}
