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
        $this->handleUpdateActiveTab();
        $this->handleReloadPlan();
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

        if (isset($_POST ['color']))
            $color = $_POST['color'];
        else
            $color = NULL;

        if (isset($_POST ['active']))
            $active = $_POST['active'];
        else
            $active = NULL;

        if ((!$title) || (!$plan) || (!$color) || (!$active)) {
            return;
        }

        echo $this->planModel->createPlan($title, $plan, $color, $active);
    }


    public function handleReloadPlan()
    {
        if (isset($_POST['id']))
            $id = $_POST['id'];

        echo $this->planModel->reloadPlans($id);
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

    private function handleUpdateActiveTab()
    {
        if (isset($_POST['id']))
            $id = $_POST['id'];

        if (isset($_POST ['currentActive']))
            $currentActive = $_POST['currentActive'];
        else
            $currentActive = NULL;

        if ((!$currentActive)) {
            return;
        }

        echo $this->planModel->updateActiveTab($id, $currentActive);

    }
}

?>