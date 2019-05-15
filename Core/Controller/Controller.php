<?php

namespace Core\Controller;

use Core\Di\DiContainer as Di;
use Core\Presenter\Url;
use Core\Presenter\UrlPresenter;
use Core\Presenter\View;
use Core\Presenter\Json;
use Core\Presenter\ViewPresenter;
use Core\Presenter\JsonPresenter;
use App\System\Exception\HttpNotFoundException;

abstract class Controller {

    // controller name of the class extending this
    protected $controllerName;

    public function __construct() {
        $this->controllerName = get_class($this);
    }

    /**
     * @param string $method
     * @param array  $params
     * @throws HttpNotFoundException
     */
    public function run(string $method, array $params = array()) {
        if (!method_exists($this, $method)) {
            throw new HttpNotFoundException('Forwarded 404 page from ' . $this->controllerName . '/' . $method);
        }

        call_user_func_array(array($this, $method), $params);
    }

    protected function render(string $template, array $variables = array()) {
        Di::get(View::class)->presentWithNoVP($template, $variables);
    }

    protected function transform(string $template, array $variables = array()) {
        Di::get(Json::class)->presentWithNoJP($template, $variables);
	}

    protected function redirect(string $url) {
        Di::get(Url::class)->presentWithNoUP($url);
    }

    protected function view(ViewPresenter $vp) {
        Di::get(View::class)->present($vp);
    }

    protected function json(JsonPresenter $jp) {
        Di::get(Json::class)->present($jp);
    }

    protected function url(UrlPresenter $up) {
        Di::get(Url::class)->present($up);
    }
}
