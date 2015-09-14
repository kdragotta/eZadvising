<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 9/10/2015
 * Time: 1:36 PM
 */

$pagetitle = "Login";
require_once 'config.php';

$showForm = 1;
$errorMessage = '';

//if the formfield is submitted

if(isset($_POST['submit'])) {
    //cleanse the data entered
    $FORMFIELD['username'] = trim($_POST['username']);
    $FORMFIELD['password'] = trim($_POST['password']);


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

    //display errors
    if($errorMessage != '')
    {

       // echo 'Error: ' . $errorMessage;
        echo 'An error has occured: ' . '<br>' .  $errorMessage;
        echo '<br>';
    }
    else
    {

        $showForm = 0;

        ///this is what she has in her advising_functions.php
        $conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);

        //we do not have a database for users.. so we may have to create one. I won't put one
        // in until we decide what to call it together. I'll  just leave it blank.
        try
        {
            $sql='SELECT _______________________ FROM ________________WHERE__________';
            $login = $conn->prepare($sql);
            $login->bindParam(':userName', $FORMFIELD['username']);
            $login->execute();
            $count = $login->rowCount();
        }
        catch(PDOException $e)
        {
            echo  $e->getMessage();
            exit();
        }

        //is the username in the database?
        if($count < 1)
        {
            echo "Entered wrong userName!";
            exit();
        }

        //get username and salt from database
        //Do we have any requirements for password?




    }




}

//Test for kdragotta


if($showForm == 1){
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
<?php
}
?>