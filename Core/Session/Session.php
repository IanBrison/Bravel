<?php

namespace Core\Session;

class Session {

    const ONE_TIME_KEYS_SESSION_KEY = '_onetimes';

    protected static $sessionStarted = false;
    protected static $sessionIdRegenerated = false;

    private $generatedOneTimeKeys = array();

    public function __construct() {
        if (!self::$sessionStarted) {
            session_start();

            self::$sessionStarted = true;
        }
    }

    public function set($name, $value) {
        $_SESSION[$name] = $value;
        return $this;
    }

    public function get($name, $default = null) {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        }

        return $default;
    }

    public function clear(): self {
        $_SESSION = array();
        return $this;
    }

    public function regenerate($destroy = true) {
        if (!self::$sessionIdRegenerated) {
            session_regenerate_id($destroy);

            self::$sessionIdRegenerated = true;
        }
        return $this;
    }

    public function setAuthenticated($bool) {
        $this->set('_authenticated', (bool)$bool);

        $this->regenerate();
        return $this;
    }

    public function isAuthenticated(): bool {
        return $this->get('_authenticated', false);
    }

    public function oneTimeSet($name, $value) {
        $this->generatedOneTimeKeys[] = $name;

        return $this->set($name, $value);
    }

    public function generateCsrfToken() {
        $key = 'csrf_tokens';
        $tokens = $this->get($key, array());
        if (count($tokens) >= 10) {
            array_shift($tokens);
        }

        $token = sha1(session_id() . microtime());
        $tokens[] = $token;

        $this->set($key, $tokens);

        return $token;
    }

    public function checkCsrfToken(string $token): bool {
        $key = 'csrf_tokens';
        $tokens = $this->get($key, array());

        if (false !== ($pos = array_search($token, $tokens, true))) {
            unset($tokens[$pos]);
            $this->set($key, $tokens);

            return true;
        }

        return false;
    }

    public function __destruct() {
        foreach ($this->get(self::ONE_TIME_KEYS_SESSION_KEY, []) as $key) {
            unset($_SESSION[$key]);
        }

        $oneTimeKeys = array();
        foreach ($this->generatedOneTimeKeys as $key) {
            $oneTimeKeys[] = $key;
        }
        $this->set(self::ONE_TIME_KEYS_SESSION_KEY, $oneTimeKeys);
    }
}
