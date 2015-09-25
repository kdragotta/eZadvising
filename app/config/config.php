<?php
define("DBUSER", "advising");
define("DBPASSWORD", "adv123");
define("DBSERVER", "localhost");
define("DBNAME", "ezadvising");

$connectionString = "mysql:host=" . DBSERVER . ";dbname=" . DBNAME;

define("DBCONNECTSTRING", $connectionString);

//show errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

//Supress Errors
//ini_set("display_errors", 0);
?>