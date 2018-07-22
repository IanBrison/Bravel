<?php

namespace core\Environment;

class Environment {

    private static $config_path;

    public static function getConfig(string $config_name): array {
        if (is_null(self::$config_path)) {
            // throw an exception because it's done without initializing
        }

        $file = self::$config_path . '/' . $config_name . '.php';

        if (is_readable($file)) {
            return require $file;
        }

        return [];
    }

    public static function setConfigPath(string $config_path) {
        if (empty($config_path)) {
            // abort and throw exception?
        }

        $this->config_path = $config_path;
    }
}
