<?php
class StudentController {
    private $studentModel = NULL;

    public function __construct() {
        $this->studentModel = new StudentModel();
    }

    public function handleRequest() {
        echo $this->studentModel->getRequirementsForStudent();
    }
}
?>
