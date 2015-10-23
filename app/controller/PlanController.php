<?php
require_once(__DIR__ . '/../model/PlanModel.php');

class PlanController
{
    private $planModel = NULL;

    public function __construct()
    {
        $this->planModel = new PlanModel();
    }

    public function handleRequest()
    {
        $this->handleCreatePlan();
    }

    private function handleCreatePlan() {
        if(isset($_POST ['title']))
            $title = $_POST['title'];
        else
            $title = NULL;

        if(!$title) {
            return;
        }

        echo $this->planModel->createPlan($title);
    }
}
?>