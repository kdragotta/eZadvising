<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

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
