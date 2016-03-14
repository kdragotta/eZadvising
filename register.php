<!DOCTYPE>

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
    $formField['username'] = trim($_POST['username']);
    $formField['email'] = trim($_POST['email']);
    $formField['password'] = trim($_POST['password']);
    $formField['confirmPassword'] = trim($_POST['confirmPassword']);
    $formField['firstName'] = trim($_POST['firstName']);
    $formField['middleName'] = trim($_POST['middleName']);
    $formField['lastName'] = trim($_POST['lastName']);
    $formField['type'] = trim($_POST['type']);
    $formField['MaxCredit'] = trim($_POST['MaxCredit']);
    $formField['MinCredit'] = trim($_POST['MinCredit']);
    $formField['Major'] = trim($_POST['Major']);
    $formField['Minor'] = trim($_POST['Minor']);
    $formField['year'] = trim($_POST['year']);

    echo $formField['MaxCredit'];

    //check for empty fields
    //checks if the username is entered
    if(empty($formField['username']))
    {
        $errorMessage .= 'Please enter your Username' . '<br>';
    }
    //check if the email is entered
    if(empty($formField['email'])){
        $errorMessage .= 'Please enter your Email' . '<br>';
    }
    //Checks if the password is entered
    if(empty($formField['password']))
    {
        $errorMessage .= 'Please enter your Password' . '<br>';
    }
    //Checks if the confirm password is entered
    if(empty($formField['confirmPassword']))
    {
        $errorMessage .= 'Please enter your confirm Password' . '<br>';
    }
    //Checks if the first name is entered
    if(empty($formField['firstName']))
    {
        $errorMessage .= 'Please enter your first name' . '<br>';
    }

    //Checks if the middle name is entered.. don't know if this is required but for testing purposes is will be
    if(empty($formField['middleName']))
    {
        $errorMessage .= 'Please enter your middle name' . '<br>';
    }
    //Checks if the last name is entered
    if(empty($formField['lastName']))
    {
        $errorMessage .= 'Please enter your last name' . '<br>';
    }
    //Checks if the type is entered
    if(empty($formField['type']) && $formField <= 0)
    {
        $errorMessage .= 'Please enter the user type' . '<br>';
    }

    if(empty($formField['MaxCredit']))
    {
        $errorMessage .= 'Please enter maximum number of credits' . '<br>';
    }
    if(empty($formField['MinCredit']))
    {
        $errorMessage .= 'Please enter minimum number of credits' . '<br>';
    }
    if(empty($formField['Major']))
    {
        $errorMessage .= 'Please enter your major' . '<br>';
    }

    //checks to see if the username the user selected already exists
    //prevents duplicate username
    try
    {
        $conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
        $sql='SELECT username FROM accounts WHERE username = :userName';
        $duplicate = $conn->prepare($sql);
        $duplicate->bindParam(':userName', $formField['username']);
        $duplicate->execute();
        $count = $duplicate->rowCount();
    }
    catch(PDOException $e)
    {
        echo  $e->getMessage();
        exit();
    }
    //chceck to see if the email already exists
    try{
        $conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
        $sql='SELECT email FROM accounts WHERE email = :email';
        $duplicate2 = $conn->prepare($sql);
        $duplicate2->bindParam(':email', $formField['email']);
        $duplicate2->execute();
        $count2 = $duplicate2->rowCount();
    }
    catch(PDOException $e){
        echo $e->getMessage();
        exit();
    }

    //is the username in the database?
    if($count > 0)
    {
        $errorMessage .= "Entered an already existing username!" . "<br>";
    }
    //is email already in the database?
    if($count2 > 0){
        $errorMessage .= "Email already exists." . "<br> <br>";
        $errorMessage .= "*Note: You can go back to the login page and click" . "<br>";
        $errorMessage .= "the Forgotten Password link to reset your password.";
    }

    //do the two passwords match?
    if($formField['password'] != $formField['confirmPassword'])
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
        $hashedPassword = crypt($formField['password'],$salt);

        ///this is what she has in her advising_functions.php
        //try to insert the new user into the database





        try {
            $conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
            $sql = "INSERT INTO accounts (`username`, `email`, `password`, `first`, `middle`, `last`, `salt`, `admin`, `major`, `minor`, `minCredit`,`maxCredit`)
                      values (:username, :email, :password, :firstName, :middleName, :lastName, :salt, :admin, :major, :minor, :minCredit, :maxCredit)";
            $create = $conn->prepare($sql);
            $create->bindValue(':username', $formField['username']);
            $create->bindValue(':email', $formField['email']);
            $create->bindValue(':password', $hashedPassword);
            $create->bindValue(':firstName', $formField['firstName']);
            $create->bindValue(':middleName', $formField['middleName']);
            $create->bindValue(':lastName', $formField['lastName']);
            $create->bindValue(':salt', $salt);
            $create->bindValue(':admin', $formField['type']);
            $create->bindValue(':major', $formField['Major']);
            $create->bindValue(':minor', $formField['Minor']);
            $create->bindValue(':maxCredit', $formField['MaxCredit']);
            $create->bindValue(':minCredit', $formField['MinCredit']);
            $create->execute();
        }
        catch(PDOException $e) {
            echo $e->getMessage();
            exit();
        }


            try{
                $conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
                $sql='SELECT username FROM accounts WHERE username = :userName AND password = :password';
                $confirmLogin = $conn->prepare($sql);
                $confirmLogin->bindParam(':userName', $formField['username']);
                $confirmLogin->bindParam(':password', $formField['password']);
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
        header("refresh:3; url=login.php");


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
            <td><input type="text" name="username" id="username" size="20"
                       value="<?php if(isset($formField['username'])){echo $formField['username'];} ?>"/></td>

        </tr>
        <tr>
            <td>Email:</td>
            <td><input type="email" name="email" id="email" size="20"
            value="<?php if(isset($formField['email'])){echo $formField ['email'];} ?>"/></td>
        </tr>
        <tr>
            <td>Password:</td>
            <td><input type="password" name="password" id="password" size="20"
            value="<?php if(isset($formField['password'])){echo $formField ['password'];} ?>"/></td>
        </tr>
        <tr>
            <td>Confirm Password:</td>
            <td><input type="password" name="confirmPassword" id="confirmPassword" size="20"
            value="<?php if(isset($formField['confirmPassword'])){echo $formField ['confirmPassword'];} ?>"/></td>
        </tr>
        <tr>
            <td>First Name:</td>
            <td><input type="text" name="firstName" id="firstName" size="20"
            value="<?php if(isset($formField['firstName'])){echo $formField ['firstName'];} ?>"/></td>
        </tr>
        <tr>
            <td>Middle Name:</td>
            <td><input type="text" name="middleName" id="middleName" size="20"
            value="<?php if(isset($formField['middleName'])){echo $formField ['middleName'];} ?>"/></td>
        </tr>
        <tr>
            <td>Last Name:</td>
            <td><input type="text" name="lastName" id="lastName" size="20"
            value="<?php if(isset($formField['lastName'])){echo $formField ['lastName'];} ?>"/></td>
        </tr>
        <tr>
            <td>Min Credit:</td>
            <td><input type="text" name="MinCredit" id="MinCredit" size="20"
            value="<?php if(isset($formField['MinCredit'])){echo $formField ['MinCredit'];} ?>"/></td>
        </tr>
        <tr>
            <td>Max Credit:</td>
            <td><input type="text" name="MaxCredit" id="MaxCredit" size="20"
            value="<?php if(isset($formField['MaxCredit'])){echo $formField ['MaxCredit'];} ?>"/></td>
        </tr>
        <tr>
            <td>Major:</td>
            <td><input type="text" name="Major" id="Major" size="20"
            value="<?php if(isset($formField['Major'])){echo $formField ['Major'];} ?>"/></td>
        </tr>
        <tr>
            <td>Minor:</td>
            <td><input type="text" name="Minor" id="Minor" size="20"
            value="<?php if(isset($formField['Minor'])){echo $formField ['Minor'];} ?>"/></td>
        </tr>
        <tr>
            <td>Type</td>
            <td><select name = "type">
                <option value="0">Student</option>
                <option value="1">Admin</option>
            </select></td>
<!--            <td><input type="number" name="type" id="type" size="1" min="0" max="1"/></td>-->


        </tr>
        <tr><td>What is Your Catalogue Year?</td>
            <td><select name = "year" id="year"
                        value="<?php if(isset($formField['year'])){echo $formField['year'];} ?>">
                    <option value="none" selected="selected"></option>
                    <option value="2011" <?php if($_POST['year'] == '2011') { ?> selected <?php };?>>2011</option>
                    <option value="2012"<?php if($_POST['year'] == '2012') { ?> selected <?php };?>>2012</option>>2012</option>
                    <option value="2013"<?php if($_POST['year'] == '2013') { ?> selected <?php };?>>2013</option>>2013</option>
                    <option value="2014"<?php if($_POST['year'] == '2014') { ?> selected <?php };?>>2014</option>>2014</option>
                    <option value="2015"<?php if($_POST['year'] == '2015') { ?> selected <?php };?>>2015</option>>2015</option>
                    <option value="2016"<?php if($_POST['year'] == '2016') { ?> selected <?php };?>>2016</option>>2016</option>
                </select></td>
        </tr>
        <tr>
<!---->
        
<!--        <tr><td>What is Your Major?</td><td><input type="text" name="major" id="major" size="20"></td></tr>-->
<!--        <tr><td>What is Your Minor?</td><td><input type="text" name="minor" id="minor" size="20"></td></tr>-->

<!--        <tr><td>What are the Min - Max credit hours?</td>-->
<!--            <td><select name="min">-->
<!--                    option value="0">0</option>-->
<!--                    <option value="0">3</option>-->
<!--                    <option value="0">6</option>-->
<!--                    <option value="0">9</option>-->
<!--                    <option value="0">12</option>-->
<!--                    <option value="0">15</option>-->
<!--                    <option value="0">18</option>-->
<!--        </select>-->
<!--        <select name="max">-->
<!--        </select></tr>-->
<!--        <tr>-->
            <td>Submit:</td>
            <td><input type="submit" name="submit" value="Submit"/></td>
        </tr>
    </table>
</form>
    <p>Already have an account? <a href="login.php">Login!</a></p>


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