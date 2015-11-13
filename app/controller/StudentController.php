<?php
require_once(__DIR__ . '/../model/StudentModel.php');

class StudentController {
    private $studentModel = NULL;
    private $requirementsModel;

    public function __construct($requirementsModel) {
        $this->studentModel = new StudentModel();
        $this->requirementsModel = $requirementsModel;
    }

    public function handleRequest() {
        $this->handleGetRequirementsForStudent();
        $this->handleMeetsPreReqs();

    }

    private function handleMeetsPreReqs() {
        if(isset($_GET ['stuId']))
            $stuId = $_GET['stuId'];
        else
            $stuId = NULL;

        if(isset($_GET ['courseId']))
            $courseId = $_GET['courseId'];
        else
            $courseId = NULL;

        if(isset($_GET ['semester']))
            $semester = $_GET['semester'];
        else
            $semester = NULL;

        if(isset($_GET ['year']))
            $year = $_GET['year'];
        else
            $year = NULL;

        if(!$stuId || !$courseId || !$semester || !$year) {
            return;
            //echo 'something null';
        }
        echo $this->studentModel->meetsPreReqs($stuId, $courseId, $semester, $year);

    }

    private function handleGetRequirementsForStudent() {
        if (!isset($_POST['requirements'])) {
            return;
        }

        $reqs = $this->requirementsModel->getRequirements();

        echo json_encode($reqs);
    }
}
?>
