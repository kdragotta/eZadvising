<?php


define("DBUSER", "advising");
define("DBPASSWORD", "adv123");
define("DBSERVER", "localhost");
define("DBNAME", "ezadvising");

$connectionString = "mysql:host=" . DBSERVER . ";dbname=" . DBNAME;

define("DBCONNECTSTRING", $connectionString);

//Supress Errors
ini_set("display_errors", 0);
?>