<?php

namespace Core\Di;

use Core\Environment\Environment;

class DiContainer {

    // all created instances go into this array
    static $singletons = [
    ];

    // alias mapper
    static $aliases = [
    ];

    public static function initialize() {
        $di_configs = Environment::getConfig('di');
        self::$singletons = array_fill_keys($di_configs['singletons'], null);
        self::$aliases = $di_configs['aliases'];
    }

    public static function get(string $singleton_name, ...$args) {
        return self::_get($singleton_name, false, ...$args);
    }

    public static function getForceSave(string $singleton_name, ...$args) {
        return self::_get($singleton_name, true, ...$args);
    }

    public static function set(string $singleton_name, $instance) {
        self::_set($singleton_name, $instance);
    }

    private static function _get(string $singleton_name, bool $forceSave, ...$args) {
        if (isset(self::$singletons[$singleton_name])) {
            return $singletons[$singleton_name];
        }

        $class_name = self::$aliases[$singleton_name] ?? $singleton_name;
        $instance = new $singleton_name(...$args);

        if (array_key_exists($singleton_name, self::$singletons) || $forceSave) {
            self::_set($singleton_name, $instance);
        }

        return $instance;
    }

    private static function _set(string $singleton_name, $instance) {
        self::$singletons[$singleton_name] = $instance;
    }
}
