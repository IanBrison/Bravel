<?php

namespace Core\View;

use Twig\Loader\FilesystemLoader as Twig_FilesystemLoader;
use Twig\Environment as Twig_Environment;
use Core\Di\DiContainer as Di;
use Core\Environment\Environment;
use Core\Session\Session;
use Core\View\BuiltIns\Models\CsrfToken;

class View {

    const BRAVEL_CORE_TEMLATE_DIRECTORY = '/core/View/BuiltIns/Views';

    protected $twig;
    protected $extension;

    public function __construct() {
        $baseDir = Environment::getDir(Environment::getConfig('view.base_path'));
        $bravelDir = Environment::getDir(self::BRAVEL_CORE_TEMLATE_DIRECTORY);
        $templateDirectories = array(
            $baseDir,
            $bravelDir,
        );
        $loader = new Twig_FilesystemLoader($templateDirectories);
        $this->twig = new Twig_Environment($loader);

        $this->extension = Environment::getConfig('view.extension');
    }

    public function render(string $template, array $variables = array()) {
        return $this->twig->render($template . $this->extension, $variables);
    }

    public function generateCsrfTokenViewModel(): CsrfToken {
        $token = Di::get(Session::class)->generateCsrfToken();
        return new CsrfToken($token);
    }
}
