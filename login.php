<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 9/10/2015
 * Time: 1:36 PM
 */

$pagetitle = "Login";
require_once 'config.php';

//if the formfield is submitted
if(isset($_POST['submit'])) {
    //cleanse the data entered
    $FORMFIELD['username'] = trim($_POST['username']);
    $FORMFIELD['password'] = trim($_POST['password']);

    $errorMessage = '';
    //check for empty fields
    //checks if the username is entered
    if(!isset($FORMFIELD['username']))
    {
        $errorMessage .= 'Please enter your UserName';
    }
    //Checks if the password is entered
    if(!isset($FORMFIELD['password']))
    {
        $errorMessage .= 'Please enter your Password';
    }

    //display errors
    if($errorMessage != '')
    {
        echo $errorMessage;
    }
    else
    {

        //get username and salt from database
        //Do we have any requirements for password?




    }




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
