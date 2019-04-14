<?php

namespace Core\Presenter;

use Core\Di\DiContainer as Di;
use Core\Environment\Environment;
use Core\Response\Response;

class Json extends Presenter {

	protected $basePath; // the base path that is going to be used first
	protected $bravelPath; // the path that is going to be used when failed with basePath
	protected $extension; // the extension of the json file

	public function __construct() {
		$this->basePath = Environment::getDir('/App/Presentation/Jsons/');
		$this->bravelPath = $this->bravelCoreTemplateDirectory('/Jsons');
		$this->extension = '.json.php';
	}

	public function transform(string $template, array $variables = array()) {
		extract($variables);
        $content = json_encode(require $this->getAppropriateFile($template));
		Di::set(Response::class, Di::get(Response::class)->setContent($content));
	}

	private function getAppropriateFile(string $template): string {
		$defaultFile = $this->basePath . $template . $this->extension;
		if (file_exists($defaultFile)) {
			return $defaultFile;
		}
		$bravelFile = $this->bravelPath . $template . $this->extension;
		if (file_exists($bravelFile)) {
			return $bravelFile;
		}
		throw new \Exception("No Json Template File '$template' Found");
	}
}