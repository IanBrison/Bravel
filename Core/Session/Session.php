<?php

namespace Core\Session;

class Session {

    const AUTHENTICATION_SESSION_KEY = '_authenticated';
    const CSRF_TOKENS_SESSION_KEY = 'csrf_tokens';
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

    public function set(string $name, $value) {
        $_SESSION[$name] = $value;
        return $this;
    }

    public function get(string $name, $default = null) {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        }

        return $default;
    }

    public function clear(): self {
        $_SESSION = array();
        return $this;
    }

    public function regenerate(bool $destroy = true) {
        if (!self::$sessionIdRegenerated) {
            session_regenerate_id($destroy);

            self::$sessionIdRegenerated = true;
        }
        return $this;
    }

    public function setAuthenticated(bool $bool) {
        $this->set(self::AUTHENTICATION_SESSION_KEY, $bool);

        $this->regenerate();
        return $this;
    }

    public function isAuthenticated(): bool {
        return $this->get(self::AUTHENTICATION_SESSION_KEY, false);
    }

    public function oneTimeSet(string $name, $value) {
        $this->generatedOneTimeKeys[] = $name;

        return $this->set($name, $value);
    }

    public function generateCsrfToken() {
        $tokens = $this->get(self::CSRF_TOKENS_SESSION_KEY, array());
        if (count($tokens) >= 10) {
            array_shift($tokens);
        }

        $token = sha1(session_id() . microtime());
        $tokens[] = $token;

        $this->set(self::CSRF_TOKENS_SESSION_KEY, $tokens);

        return $token;
    }

    public function checkCsrfToken(string $token): bool {
        $tokens = $this->get(self::CSRF_TOKENS_SESSION_KEY, array());

        if (false !== ($pos = array_search($token, $tokens, true))) {
            unset($tokens[$pos]);
            $this->set(self::CSRF_TOKENS_SESSION_KEY, $tokens);

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
