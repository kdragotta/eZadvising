<?php
require_once 'app/controller/PlanItemController.php';
require_once 'app/controller/PlanController.php';
require_once 'app/controller/StudentController.php';

$planItemController = new PlanItemController();
$planItemController->handleRequest();

$planController = new PlanController();
$planController->handleRequests();

$studentController = new StudentController();
$studentController->handleRequest();

?>