<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 9/10/2015
 * Time: 1:36 PM
 */

$pagetitle = "Login";
require_once 'config.php';

if(isset($_POST['submit'])) {
    //cleanse the data entered
    $FORMFIELD['username'] = trim($_POST['username']);
    $FORMFIELD['password'] = trim($_POST['password']);

    //check for empty fields
    if(!isset($FORMFIELD['username']))
    {
        echo 'enter username!!!!';
    }

    //display errors

    //get username and salt from database
}



?>

<p>Please log in to access registration.</p>

<form name="loginForm" id="loginForm" method="post" action="login.php">
    <table>
        <tr>
            <td>Username:</td>
            <td><input type="text" name="username" id="username" size="20"/></td>
        </tr>
        <tr>
            <td>Password:</td>
            <td><input type="password" name="password" id="password" size="20"/></td>
        </tr>
        <tr>
            <td>Submit:</td>
            <td><input type="submit" name="submit" value="Submit"/></td>
        </tr>
    </table>


</form>
