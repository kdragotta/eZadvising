<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

require 'app/classes/loader.php';
require 'app/classes/basecontroller.php';
require 'app/classes/basemodel.php';

require 'app/models/home.php';

$loader = new Loader($_GET);
$controller = $loader->CreateController();
$controller->ExecuteAction();

?>
