<?php
require 'app/controller/PlanItemController.php';

$planItemController = new PlanItemController();
$planItemController->handleRequest();

$planController = new PlanController();
$planController->handleRequests();

$studentController = new StudentController();
$studentController->handleRequest();

?>