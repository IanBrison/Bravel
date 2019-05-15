<?php

namespace Core\Presenter;

use Exception;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader as Twig_FilesystemLoader;
use Twig\Environment as Twig_Environment;
use Core\Di\DiContainer as Di;
use Core\Environment\Environment;
use Core\Response\Response;

class View extends Presenter {

    protected $twig; // the twig instance itself
    protected $extension; // the extension of the template files

    /**
     * View constructor.
     * @throws Exception
     */
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

    /**
     * @param string $template
     * @param array  $variables
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render(string $template, array $variables = array()) {
        return $this->twig->render($template . $this->extension, $variables);
    }

    public function present(ViewPresenter $vp) {
	    Di::set(Response::class, Di::get(Response::class)->setContent($vp->presentView()));
    }

    /**
     * @param string $template
     * @param array  $variables
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function presentWithNoVP(string $template, array $variables = array()) {
        $content = $this->render($template, $variables);
	    Di::set(Response::class, Di::get(Response::class)->setContent($content));
    }
}
