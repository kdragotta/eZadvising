<!DOCTYPE html>
<?php


session_start();

if(isset($_COOKIE))
{
    echo $_SESSION['token']. "<br />";
    echo $_SESSION['username'] . "<br />";
    foreach($_COOKIE as $key => $token)
    {
        if($key == $_SESSION['username'] && $token == $_SESSION['token'])
        {
        header("Location: eatouch4.php");
        }
        else{
            echo "error <br>";
            echo "Key: ". $key. " Token: ". $token. "<br/>";

        }
    }
}
else
{
    echo "error <br>";
    echo $_SESSION['token']. "<br />";
    echo $_SESSION['username'] . "<br />";
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
