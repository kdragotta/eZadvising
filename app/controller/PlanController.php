<?php

require_once(__DIR__ . '/../model/PlanModel.php');

class PlanController {
    private $planModel = NULL;

    public function __construct() {
        $planModel = new PlanModel();
    }

    public function handleRequests() {
        $this->handleCreatePlan();
    }

    public function handleCreatePlan()
    {
        if(isset($_POST['submit']))
        {
            $this->planModel->createPlan();
        }
    }

}
?>