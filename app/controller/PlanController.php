<?php
require(__DIR__.'/../model/PlanModel.php');

class PlanController {

    private $planModel = NULL;

    public function __construct() {
        $this->planModel = new PlanModel();
    }
    public function handleRequest() {
        $this->movePlanItem();
    }

    public function movePlanItem() {
        if(isset($_POST ['courseId']))
            $courseId = $_POST['courseId'];
        else
            $courseId = NULL;
        if(isset($_POST['fromSem']))
            $fromSem = $_POST['fromSem'];
        else
            $fromSem = NULL;
        if(isset($_POST['fromYear']))
            $fromYear = $_POST['fromYear'];
        else
            $fromYear = NULL;
        if(isset($_POST['reqId']))
            $reqId = $_POST['reqId'];
        else
            $reqId = NULL;
        if(isset($_POST['toSem']))
            $toSem = $_POST['toSem'];
        else
            $toSem = NULL;
        if(isset($_POST['toYear']))
            $toYear = $_POST['toYear'];
        else
            $toYear = NULL;
        if(isset($_POST['studentId']))
            $studentId = $_POST['studentId'];
        else
            $studentId = NULL;

        if(!$courseId || !$fromSem || !$fromYear || !$reqId ||
            !$toSem || !$toYear || !$studentId)
            echo 'something null';

        echo ($this->planModel->movePlanItem("ABC", $studentId, $courseId, $fromSem,
                                       $fromYear, $toSem, $toYear, $reqId));
    }
}
?>