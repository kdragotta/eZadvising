<?php
//handles request for the plan controller
require_once(__DIR__ . '/../model/PlanModel.php');

class PlanController
{
    private $planModel = NULL;

    public function __construct()
    {
        $planModel = new PlanModel();
    }

    public function handleRequest()
    {
        $this->handleCreatePlan();
    }

    private function handleCreatePlan()
    {
        if (isset($_POST['submit'])) {
            $this->planModel->createPlan();
        }
    }

}
?>