<?php

namespace core\Environment;

use \Exception;
use Dotenv\Dotenv;

class Environment {

    private static $rootDir;
    private static $configDir;

    private static $cachedConfigs;

    public static function getConfig(string $configStr) {
        $configStrArray = explode('.', $configStr);

        $value = self::setConfigCache($configStrArray[0]);

        foreach ($configStrArray as $configStrIndex) {
            if (!array_key_exists($configStrIndex, $value)) {
                throw new Exception("No such config value as {$configStr} as the index '{$configStrIndex}' didn`t have a value");
            }
            $value = $value[$configStrIndex];
        }

        return $value;
    }

    private static function setConfigCache(string $configFileName): array {
        if (array_key_exists($configFileName, self::$cachedConfigs)) {
            return self::$cachedConfigs;
        }

        $file = self::$configDir . '/' . $configFileName . '.php';
        if (!is_readable($file)) {
            $configDir = self::$configDir;
            throw new Exception("No such config file as '{$configFileName}' found in {$configDir}");
        }

        self::$cachedConfigs[$configFileName] = require $file;
        return self::$cachedConfigs;
    }

    public static function getDir(string $path): string {
        return self::$rootDir . $path;
    }

    public static function initialize(string $rootDir, string $configPath) {
        self::$rootDir = $rootDir;
        self::$configDir = self::getDir($configPath);
        self::$cachedConfigs = array();
        Dotenv::create($rootDir)->load();
    }
}
