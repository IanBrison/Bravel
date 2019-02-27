<?php

namespace Core\Request;

use Core\Storage\File;

class Request {
    public function isPost() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return true;
        }

        return false;
    }

    public function getGet($name, $default = null) {
        if (isset($_GET[$name])) {
            return $_GET[$name];
        }

        return $default;
    }

    public function getPost($name, $default = null) {
        if (isset($_POST[$name])) {
            return $_POST[$name];
        }

        return $default;
    }

    public function getFile($name, $default = null): ?File {
        if (isset($_FILES[$name])) {
            return File::constructFromRequest($_FILES[$name]);
        }

        return $default;
    }

    public function getHost(): string {
        if (!empty($_SERVER['HTTP_HOST'])) {
            return $_SERVER['HTTP_HOST'];
        }

        return $_SERVER['SERVER_NAME'];
    }

    public function isSsl(): bool {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            return true;
        }

        return false;
    }

    public function getRequestUri() {
        return $_SERVER['REQUEST_URI'];
    }

    public function getBaseUrl() {
        $scriptName = $_SERVER['SCRIPT_NAME'];

        $requestUri = $this->getRequestUri();

        if (0 === strpos($requestUri, $scriptName)) {
            return $scriptName;
        } else if (0 === strpos($requestUri, dirname($scriptName))) {
            return rtrim(dirname($scriptName), '/');
        }

        return '';
    }

    public function getPathInfo() {
        $baseUrl = $this->getBaseUrl();
        $requestUri = $this->getRequestUri();

        if (false !== ($pos = strpos($requestUri, '?'))) {
            $requestUri = substr($requestUri, 0, $pos);
        }

        $pathInfo = (string)substr($requestUri, strlen($baseUrl));

        return $pathInfo;
    }
}
