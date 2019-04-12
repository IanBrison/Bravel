<?php

namespace Core\Presenter;

use core\Environment\Environment;

trait BasicJsonModel {

    public function jsonTemplate(): string {
        if (!empty($this->template)) {
            return $this->template;
        }
        if (empty($this->jsonTemplate)) {
            throw new \Exception('No json template specified');
        }
        return $this->jsonTemplate;
    }

    public function emit() {
        $jm = $this;
        return require Environment::getDir('/app/Presentation/Jsons/' . $this->jsonTemplate() . ".php");
    }
}