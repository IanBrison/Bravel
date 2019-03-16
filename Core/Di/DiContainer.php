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

    // prevent from initializing more then once
    static $hasInitialized = false;

    public static function initialize() {
        if (self::$hasInitialized) return;

        self::$singletons = array_fill_keys(Environment::getConfig('di.singletons'), null);
        self::$aliases = Environment::getConfig('di.aliases');
        self::$hasInitialized = true;
    }

    public static function get(string $dependencyName, ...$args) {
        return self::_get($dependencyName, false, ...$args);
    }

    public static function getForceSave(string $dependencyName, ...$args) {
        return self::_get($dependencyName, true, ...$args);
    }

    public static function set(string $dependencyName, $instance) {
        self::_set($dependencyName, $instance);
    }

    private static function _get(string $dependencyName, bool $forceSave, ...$args) {
        if (isset(self::$singletons[$dependencyName])) {
            return self::$singletons[$dependencyName];
        }

        $className = self::$aliases[$dependencyName] ?? $dependencyName;
        $instance = new $className(...$args);

        if (array_key_exists($dependencyName, self::$singletons) || $forceSave) {
            self::_set($dependencyName, $instance);
        }

        return $instance;
    }

    private static function _set(string $dependencyName, $instance) {
        self::$singletons[$dependencyName] = $instance;
    }
}
