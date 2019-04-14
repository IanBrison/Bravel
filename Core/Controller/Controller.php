<?php

namespace Core\Controller;

use Core\Di\DiContainer as Di;
use Core\Presenter\Url;
use Core\Presenter\UrlPresenter;
use Core\Presenter\View;
use Core\Presenter\Json;
use Core\Presenter\ViewPresenter;
use Core\Presenter\JsonPresenter;
use Core\Response\Response;
use Core\Response\StatusCode;
use Core\Response\HttpHeader;
use Core\Response\HttpHeaders;
use Core\Request\Request;
use App\System\Exception\HttpNotFoundException;

abstract class Controller {

    // controller name of the class extending this
    protected $controllerName;

    public function __construct() {
        $this->controllerName = get_class($this);
    }

    public function run(string $method, array $params = array()) {
        if (!method_exists($this, $method)) {
            throw new HttpNotFoundException('Forwarded 404 page from ' . $this->controllerName . '/' . $method);
        }

        call_user_func_array(array($this, $method), $params);

    }

    protected function render(string $template, array $variables = array()) {
        Di::get(View::class)->render($template, $variables);
    }

    protected function transform(string $template, array $variables = array()) {
        Di::get(Json::class)->transform($template, $variables);
	}

    protected function redirect(string $url) {
        Di::get(Url::class)->redirect($url);
    }

    protected function view(ViewPresenter $vp) {
        $vp->presentView();
    }

    protected function json(JsonPresenter $jp) {
        $jp->presentJson();
    }

    protected function url(UrlPresenter $up) {
        $up->presentUrl();
    }
}
