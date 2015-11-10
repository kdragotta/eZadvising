<?php
session_start();

setcookie('username',$_SESSION['username'] , time() + 60*60);
//setcookie('password', $_SESSION['password'], time() + 60);



//foreach($_COOKIE as $key => $t)
//{
//    echo $key ."     =     ". $t;
//}
header("Location: makecookie2.php");
/**
 * Created by PhpStorm.
 * User: ferretqueen1313
 * Date: 11/9/15
 * Time: 2:56 PM
 */ 