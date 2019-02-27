<?php

namespace Core\Response;

class HttpHeader {

    protected $name;
    protected $value;

    public function __construct() {
        $this->name = '';
        $this->value = '';
    }

    public function setName(String $name): HttpHeader {
        $this->name = $name;
        return $this;
    }

    public function setValue(String $value): HttpHeader {
        $this->value = $value;
        return $this;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getValue(): string {
        return $this->value;
    }
}
