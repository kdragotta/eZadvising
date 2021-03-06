<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 9/10/2015
 * Time: 1:36 PM
 */

$pagetitle = "Login";
require_once 'config.php';
require_once 'advising_functions.php';

$showForm = 1;
$errorMessage = '';

//if the formfield is submitted

if(isset($_POST['submit'])) {
    //cleanse the data entered
    $FORMFIELD['username'] = trim($_POST['username']);
    $FORMFIELD['password'] = trim($_POST['password']);
    $FORMFIELD['confirmPassword'] = trim($_POST['confirmPassword']);
    $FORMFIELD['firstName'] = trim($_POST['firstName']);
    $FORMFIELD['middleName'] = trim($_POST['middleName']);
    $FORMFIELD['lastName'] = trim($_POST['lastName']);
    $FORMFIELD['type'] = trim($_POST['type']);


    //check for empty fields
    //checks if the username is entered
    if(empty($FORMFIELD['username']))
    {
        $errorMessage .= 'Please enter your Username' . '<br>';
    }
    //Checks if the password is entered
    if(empty($FORMFIELD['password']))
    {
        $errorMessage .= 'Please enter your Password' . '<br>';
    }
    //Checks if the confirm password is entered
    if(empty($FORMFIELD['confirmPassword']))
    {
        $errorMessage .= 'Please enter your confirm Password' . '<br>';
    }
    //Checks if the first name is entered
    if(empty($FORMFIELD['firstName']))
    {
        $errorMessage .= 'Please enter your first name' . '<br>';
    }

    //Checks if the middle name is entered.. don't know if this is required but for testing purposes is will be
    if(empty($FORMFIELD['middleName']))
    {
        $errorMessage .= 'Please enter your middle name' . '<br>';
    }
    //Checks if the last name is entered
    if(empty($FORMFIELD['lastName']))
    {
        $errorMessage .= 'Please enter your last name' . '<br>';
    }
    //Checks if the type is entered
    if(empty($FORMFIELD['type']) && $FORMFIELD != 0)
    {
        $errorMessage .= 'Please enter the user type' . '<br>';
    }

    //checks to see if the username the user selected already exists
    //prevents duplicate username
    try
    {
        $conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
        $sql='SELECT username FROM accounts WHERE username = :userName';
        $duplicate = $conn->prepare($sql);
        $duplicate->bindParam(':userName', $FORMFIELD['username']);
        $duplicate->execute();
        $count = $duplicate->rowCount();
    }
    catch(PDOException $e)
    {
        echo  $e->getMessage();
        exit();
    }

    //is the username in the database?
    if($count > 0)
    {
        $errorMessage .= "Entered an already existing username!";
    }

    //do the two passwords match?
    if($FORMFIELD['password'] != $FORMFIELD['confirmPassword'])
    {
        $errorMessage .= 'Confirm password and password do not match';
    }

    //display errors
    if($errorMessage != '')
    {

        // echo 'Error: ' . $errorMessage;
        echo 'An error has occurred: ' . '<br>' .  $errorMessage;
        echo '<br />';
    }
    else
    {
        //hash the password
        for ($i = 0; $i < 22; $i++) {
            $char22 .= substr("./ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789", mt_rand(0, 63), 1);
        }
        //salt makes it more secure
        $salt = '$2a$07$' . $char22;
        echo "<br>";
        //combine the salt with the hashed password
        $hashedPassword = crypt($FORMFIELD['password'],$salt);

        ///this is what she has in her advising_functions.php
        //try to insert the new user into the database





        try
        {
            $conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
            $sql = "INSERT INTO accounts (`username`, `password`, `first`, `middle`, `last`, `salt`, `admin`)
                      values (:username, :password, :firstName, :middleName, :lastName, :salt, :admin)";
            $create = $conn->prepare($sql);
            $create->bindValue(':username', $FORMFIELD['username']);
            $create->bindValue(':password', $hashedPassword);
            $create->bindValue(':firstName', $FORMFIELD['firstName']);
            $create->bindValue(':middleName', $FORMFIELD['middleName']);
            $create->bindValue(':lastName', $FORMFIELD['lastName']);
            $create->bindValue(':salt', $salt);
            $create->bindValue(':admin', $FORMFIELD['type']);
            $create->execute();
        }
        catch(PDOException $e)
        {
            echo  $e->getMessage();
            exit();
        }

            try{
                $conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
                $sql='SELECT username FROM accounts WHERE username = :userName AND password = :password';
                $confirmLogin = $conn->prepare($sql);
                $confirmLogin->bindParam(':userName', $FORMFIELD['username']);
                $confirmLogin->bindParam(':password', $FORMFIELD['password']);
                $confirmLogin->execute();
                $confirm = $confirmLogin->rowCount();
            }
            catch(PDOException $e){
                echo  $e->getMessage();
                exit();
            }

            echo "You successfully created a new User!";

                echo "<br>";
                echo "Redirecting to Login...";

            $showForm = 0;
            header("refresh:5; url=login.php");


        }




}

//Test for kdragotta


if($showForm == 1){
?>


    <h1>Register</h1>

<form name="register" id="register" method="post" action="register.php">
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
            <td>Confirm Password:</td>
            <td><input type="password" name="confirmPassword" id="confirmPassword" size="20"/></td>
        </tr>
        <tr>
            <td>First Name:</td>
            <td><input type="text" name="firstName" id="firstName" size="20"/></td>
        </tr>
        <tr>
            <td>Middle Name:</td>
            <td><input type="text" name="middleName" id="middleName" size="20"/></td>
        </tr>
        <tr>
            <td>Last Name:</td>
            <td><input type="text" name="lastName" id="lastName" size="20"/></td>
        </tr>
        <tr>
            <td>Type</td>
            <td><input type="number" name="type" id="type" size="1" min="0" max="1"/></td>
        </tr>
        <tr>
            <td>Submit:</td>
            <td><input type="submit" name="submit" value="Submit"/></td>
        </tr>
    </table>


</form>
    <style>
        html, body {
            font-family: arial;
            background: #f3f2f2;
        }
        form {
            padding: 20px 20% 20px 20%;
            margin: 5% 20% 5% 20%;
            border: 1px solid;
            border-radius: 25px;
        }
        h1 {
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #008080;
            font-size: 3rem;
            position: relative;
            z-index: 10;
            height: 50px;;
        }
        p {
            margin-left: 20%;
        }

        .textbox  {
            width: 200px;
        }

    </style>
<?php

}
