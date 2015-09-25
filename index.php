<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

require 'app/classes/loader.php';

$loader = new Loader($_GET);
$controller = $loader->CreateController();
$controller->ExecuteAction();

?>
