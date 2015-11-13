<?php
require_once 'app/controller/PlanController.php';
require_once 'app/controller/PlanItemController.php';
require_once 'app/controller/StudentController.php';
require_once('app/controller/Initialization.php');



if (isset($_POST['op']) == true) {
    $op = $_POST['op'];

    $deps = init();

    if (strcmp($op, 'plan') == 0) {
        $planController = new PlanController();
        $planController->handleRequest();
    }
    else if (strcmp($op, 'planitem') == 0) {
        $planItemController = new PlanItemController($deps['coursesModel'], $deps['recordsModel'], $deps['requirementsModel']);
        $planItemController->handleRequest();
    }
    else if (strcmp($op, 'student') == 0) {
        $studentController = new StudentController($deps['requirementsModel']);
        $studentController->handleRequest();
    }
}

if (empty($_GET) && empty($_POST)) {
    session_start();
    $_SESSION['username'] = "crystal";
    $_SESSION['studentId'] = 1;
    $_SESSION['token'] = "ABC";

    include __DIR__ . "/app/view/view.php";
}
?>