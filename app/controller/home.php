<?php

class Home extends BaseController {
    protected function Index() {
        $viewmodel = new HomeModel();
        $this->ReturnView($viewmodel->Index(), TRUE);
    }
}
