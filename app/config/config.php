<?php
define("DBUSER", "advising");
define("DBPASSWORD", "adv123");
define("DBSERVER", "localhost");
define("DBNAME", "ezadvising");

$connectionString = "mysql:host=" . DBSERVER . ";dbname=" . DBNAME;

define("DBCONNECTSTRING", $connectionString);


// Used for inserting title, ** Will find different way later**
try {
    $sql = new PDO($connectionString, DBUSER, DBPASSWORD);
    $sql->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Unable to connect to the database server. <br />ERROR MESSAGE:<br />' . $e->getMessage();
    exit();
}

//show errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

//Supress Errors
//ini_set("display_errors", 0);
?>