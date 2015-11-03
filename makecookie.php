<?php

session_start();

setcookie($_SESSION['token'], time() + 20);
if(isset($_COOKIE[$_SESSION['token']]))
{

    header("Location: eatouch4.php");
}
else
{
    echo "error <br>";
    echo $_SESSION['token']. "<br>";
    echo $_COOKIE[time() + 20];
}





//
//    echo $_GET['token'];
//    echo"<br />";
//    //foreach ($_COOKIE as $key => $value)
//    //{
//    //    setcookie($key, 1);
//    //    echo "cookie: ". $key . "<br/>";
//    //}
//    //
//    //foreach ($_COOKIE as $key => $value)
//    //{
//    //    echo $_COOKIE[$key]. "<br/>";
//    //}
//
//    if(!isset($_COOKIE[$token]))
//    {
//        echo "yay";
//    }
//    else
//    {
//        foreach ($_COOKIE as $key => $value)
//        {
//            echo $_COOKIE[$key]. "<br/>";
//        }
//    }
