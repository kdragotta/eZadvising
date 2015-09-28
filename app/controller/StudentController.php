<?php
require_once(__DIR__ . '/../model/StudentModel.php');

class StudentController {
    private $studentModel = NULL;

    public function __construct() {
        $this->studentModel = new StudentModel();
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
        if(isset($_POST ['token']))
            $token = $_POST['token'];
        else
            $token = NULL;

        if(isset($_POST['studentId']))
            $studentId = $_POST['studentId'];
        else
            $studentId = NULL;

        if(isset($_POST['programId']))
            $programId = $_POST['programId'];
        else
            $programId = NULL;

        if(isset($_POST['year']))
            $year = $_POST['year'];
        else
            $year = NULL;

        if(!$token || !$studentId || !$programId || !$year) {
            return;
            //echo 'something null';
        }

        echo $this->studentModel->getRequirementsForStudent($token, $studentId,
                                                            $programId, $year);
    }
}
?>
