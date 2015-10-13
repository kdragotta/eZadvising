<?php
require_once(__DIR__ . '/../model/PlanItemModel.php');

class PlanTitleController {

    private $planTitleModel = NULL;

    public function __construct() {
        $this->planTitleModel = new PlanTitleModel();
    }

    public function handleRequest() {
        $this->handleAddPlanTitle();
    }

    private function handleAddPlanTitle() {
        if(isset($_POST ['title']))
            $title = $_POST['title'];
        else
            $title = NULL;

        echo $this->planTitleModel->addPlanTitle($title);
    }
}
?>