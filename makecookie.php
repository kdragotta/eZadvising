<!DOCTYPE html>
<?php
session_start();
header("Location: eatouch4.php");
//if(isset($_COOKIE))
//{
//    foreach($_COOKIE as $key => $token)
//    {
//        //this will go through all the cookies
//        if($key == $_SESSION['username'] && $token == $_SESSION['token'])
//        {
//            //if the username and the token are equal to the session username and token, then it should go to eatouch4
//        header("Location: eatouch4.php");
//        }
//        else{
//
//            //prints all the cookies. if mulitple are set correctlye. it should read. Username: ___ Token:   ___
//           // echo "Key: ". $key. " Token: ". $token. "<br/>";
//
//        }
//    }
//}
//else
//{
//    echo "error <br>";
//    echo $_SESSION['token']. "<br />";
//    echo $_SESSION['username'] . "<br />";
//}
