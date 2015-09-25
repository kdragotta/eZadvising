<?php
//require 'app/classes/loader.php';
require 'app/controller/PlanController.php';
/*
$loader = new Loader($_GET);
$controller = $loader->CreateController();
$controller->ExecuteAction();
*/

$planController = new PlanController();
$planController->handleRequest();


?>
