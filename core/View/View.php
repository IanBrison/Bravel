<?php

namespace Core\View;

use Core\Environment\Environment;
use Core\Di\DiContainer as Di;

class View {

    protected $base_dir;
    protected $layout_variables;

    public function __construct() {
        $this->base_dir = Environment::getDir(Environment::getConfig('view.base_path'));
        $this->layout_variables = array();
    }

    public function setLayoutVar($name, $value) {
        $this->layout_variables[$name] = $value;
    }

    public function render($_path, $_variables = array(), $_layout = false) {
        $_file = $this->base_dir . '/' . $_path . '.php';

        extract($_variables);

        ob_start();
        ob_implicit_flush(0);

        require $_file;

        $content = ob_get_clean();

        if ($_layout) {
            $content = $this->render($_layout,
                array_merge($this->layout_variables, array(
                    '_content' => $content,
                )
            ));
        }

        return $content;
    }

    public function escape($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}
