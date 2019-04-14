<?php

namespace Core\Presenter;

use Twig\Loader\FilesystemLoader as Twig_FilesystemLoader;
use Twig\Environment as Twig_Environment;
use Core\Di\DiContainer as Di;
use Core\Environment\Environment;
use Core\Response\Response;

class View extends Presenter {

    protected $twig; // the twig instance itself
    protected $extension; // the extension of the template files

    public function __construct() {
        $baseTemplateDirectory = Environment::getDir(Environment::getConfig('view.base_path'));
        $bravelTemplateDirectory = Environment::getDir($this->bravelCoreTemplateDirectory('/Views'));
        $templateDirectories = array(
            $baseTemplateDirectory,
            $bravelTemplateDirectory,
        );
        $loader = new Twig_FilesystemLoader($templateDirectories);
        $this->twig = new Twig_Environment($loader, ['strict_variables' => true]);

        $this->extension = Environment::getConfig('view.extension');
    }

    public function render(string $template, array $variables = array()) {
        $content = $this->twig->render($template . $this->extension, $variables);
	    Di::set(Response::class, Di::get(Response::class)->setContent($content));
    }
}
