<?php

namespace Core\View;

use Twig\Loader\FilesystemLoader as Twig_FilesystemLoader;
use Twig\Environment as Twig_Environment;

use Core\Environment\Environment;
use Core\Di\DiContainer as Di;

class View {

    protected $twig;

    public function __construct() {
        $base_dir = Environment::getDir(Environment::getConfig('view.base_path'));
        $loader = new Twig_FilesystemLoader($base_dir);
        $this->twig = new Twig_Environment($loader);
    }

    public function render(string $template, array $variables = array()) {
        return $this->twig->render($template, $variables);
    }
}
