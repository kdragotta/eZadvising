<?php
require_once(__DIR__ . '/../model/PlanTitleModel.php');

class PlanTitleController
{
    private $conn = NULL;

    public function __construct()
    {
        $this->conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
    }

    public function handleRequests()
    {
        $this->handlePlanTitle();
    }

    private function handlePlanTitle()
    {
        if (isset($_POST ['planTitle']))
            $planTitle = $_POST['planTitle'];
        else
            $planTitle = NULL;

        echo $this->planTitle->addTitle("ABC", $planTitle);
    }
}
