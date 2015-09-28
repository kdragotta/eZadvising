<?php
require_once(__DIR__ . '/../model/StudentModel.php');

class StudentController {
    private $studentModel = NULL;

    public function __construct() {
        $this->studentModel = new StudentModel();
    }

//$token, $studentId, $programId = 0, $year = 0
    public function handleRequest() {
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
