<?php
require_once(__DIR__ . '/../model/PlanItemModel.php');

class PlanItemController {

    private $planItemModel = NULL;

    public function __construct() {
        $this->planItemModel = new PlanItemModel();
    }

    public function handleRequest() {
        $this->handleAddPlanItem();
        $this->handleMovePlanItem();
    }

    public function handleAddPlanItem() {
        if(isset($_POST ['courseId']))
            $courseId = $_POST['courseId'];
        else
            $courseId = NULL;

        if(isset($_POST['semesterCode']))
            $semesterCode = $_POST['semesterCode'];
        else
            $semesterCode = NULL;

        if(isset($_POST['planYear']))
            $planYear = $_POST['planYear'];
        else
            $planYear = NULL;

        if(isset($_POST['reqId']))
            $reqId = $_POST['reqId'];
        else
            $reqId = NULL;

        if(isset($_POST['proposedReqId']))
            $proposedReqId = $_POST['proposedReqId'];
        else
            $proposedReqId = NULL;

        if(isset($_POST['hours']))
            $hours = $_POST['hours'];
        else
            $hours = NULL;

        if(isset($_POST['programId']))
            $programId = $_POST['programId'];
        else
            $programId = NULL;

        if(isset($_POST['progYear']))
            $progYear = $_POST['progYear'];
        else
            $progYear = NULL;

        if(!$courseId || !$semesterCode || !$planYear || !$reqId ||
            !$proposedReqId || !$hours || !$programId)
            echo 'something null';

        echo $this->planModel->addPlanItem("ABC", 1, $courseId, $hours,
                                             $semesterCode, $planYear,
                                             $progYear, $programId,
                                             $reqId, $proposedReqId);
    }

    public function handleMovePlanItem() {
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

        echo $this->planItemModel->movePlanItem("ABC", $studentId, $courseId,
                                              $fromSem, $fromYear, $toSem,
                                              $toYear, $reqId);
    }
}
?>