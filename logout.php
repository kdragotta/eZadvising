<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 9/15/2015
 * Time: 1:23 PM
 */
$pagetitle = "Logged Out";
session_start();
session_unset();
session_destroy();
header("Location: login.php");

?>

