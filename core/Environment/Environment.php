<?php

namespace core\Environment;

use \Exception;
use Dotenv\Dotenv;

class Environment {

    private static $root_dir;
    private static $config_dir;

    private static $cached_configs;

    public static function getConfig(string $config_str) {
        $config_str_array = explode('.', $config_str);

        $value = self::setConfigCache($config_str_array[0]);

        foreach ($config_str_array as $config_str_index) {
            if (!array_key_exists($config_str_index, $value)) {
                throw new Exception("No such config value as {$config_str} as the index '{$config_str_index}' didn`t have a value");
            }
            $value = $value[$config_str_index];
        }

        return $value;
    }

    private static function setConfigCache(string $config_file_name): array {
        if (array_key_exists($config_file_name, self::$cached_configs)) {
            return self::$cached_configs;
        }

        $file = self::$config_dir . '/' . $config_file_name . '.php';
        if (!is_readable($file)) {
            $config_dir = self::$config_dir;
            throw new Exception("No such config file as '{$config_file_name}' found in {$config_dir}");
        }

        self::$cached_configs[$config_file_name] = require $file;
        return self::$cached_configs;
    }

    public static function getDir(string $path): string {
        return self::$root_dir . $path;
    }

    public static function initialize(string $root_dir, string $config_path) {
        self::$root_dir = $root_dir;
        self::$config_dir = self::getDir($config_path);
        self::$cached_configs = array();
        Dotenv::create($root_dir)->load();
    }
}
