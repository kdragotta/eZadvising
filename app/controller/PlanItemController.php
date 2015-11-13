<?php
require_once(__DIR__ . '/../model/PlanItemModel.php');

class PlanItemController {


    public function __construct($courseModel, $recordModel, $requirementModel) {
        $this->recordModel = $recordModel;
        $this->courseModel = $courseModel;
        $this->requirementModel = $requirementModel;
    }

    public function handleRequest() {
        if (isset($_POST['record'])) {
            $jrecord = json_decode($_POST['record']);
        } else {
            return;
        }
        $jrecord->course = $this->courseModel->getCourseById($jrecord->course['id']);
        $record = \obj\Record::fromJsonObject($jrecord);
        $this->recordModel->addUpdateRecord($record);
        $req = $this->requirementModel->getRequirementById($record->getReqId());

        echo json_encode($req);
    }


}
?>