<?php
require_once 'app/controller/PlanController.php';
require_once 'app/controller/PlanItemController.php';
require_once 'app/controller/PlanTitleController.php';
require_once 'app/controller/StudentController.php';

$planController = new PlanController();
$planController->handleRequest();

$planItemController = new PlanItemController();
$planItemController->handleRequest();

//$planTitleController = new planTitleController();
//$planTitleController->handleRequest();

$studentController = new StudentController();
$studentController->handleRequest();

?>