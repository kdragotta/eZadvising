<?php
session_start();
setcookie($_SESSION['username'], $_SESSION['token'], time() + 60);

header("Location: makecookie.php");
/**
 * Created by PhpStorm.
 * User: ferretqueen1313
 * Date: 11/9/15
 * Time: 2:53 PM
 */ 