<?php

class Loader {
    private $controller;
    private $action;
    private $urlvalues;

    public function __construct($urlvalues) {
        $this->urlvalues = $urlvalues;
        if($this->urlvalues['controller'] == "" ) {
            $this->controller = "home";
        }
        else {
            $this->controller = "test";
        }

        if($this->urlvalues['action'] == "") {
            $this->action = "index";
        }
        else {
            $this->action = "test";
        }
    }

    public function createController() {
        if(class_exists($this->controller) == false) {
            return new Error("bad url", $this->urlvalues);
        }

        if(method_exists($this->controller, $this->action)) {
            return new Error("bad method", $this->action);
        }
        return new $this->controller($this->actions, $this->urlvalues);
    }
}
