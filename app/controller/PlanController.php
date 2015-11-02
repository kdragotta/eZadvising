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
        $this->handleChangePlanTitle();
        //$this->handleReloadPlan();
    }

    private function handleCreatePlan()
    {
        if (isset($_POST ['title']))
            $title = $_POST['title'];
        else
            $title = NULL;

        if (isset($_POST ['plan']))
            $plan = $_POST['plan'];
        else
            $plan = NULL;

        if ((!$title) || (!$plan)) {
            return;
        }

        echo $this->planModel->createPlan($title, $plan);
    }


    public function handleReloadPlan()
    {
        if (isset($_GET ['title']))
            $title = $_GET['title'];
        else
            $title = NULL;

        if (isset($_GET ['plan']))
            $plan = $_GET('plan');
        else
            $plan = NULL;

        echo $this->planModel->reloadPlans($title, $plan);
    }

    private function handleChangePlanTitle()
    {
        if (isset($_POST['id']))
            $id = $_POST['id'];
        else
            return;

        if (isset($_POST['newTitle']))
            $newTitle = $_POST['newTitle'];
        else
            $newTitle = NULL;

        if (!$newTitle) {
            return;
        }

        echo $this->planModel->updatePlanTitle($id, $newTitle);
    }
}

?>