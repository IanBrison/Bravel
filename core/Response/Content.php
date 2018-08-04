<?php

namespace Core\Response;

class Content {

    protected $content;

    public function __construct($content) {
        $this->content = $content;
    }

    public function get() {
        return $this->content;
    }
}
