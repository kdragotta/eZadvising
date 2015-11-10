<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 9/10/2015
 * Time: 1:36 PM
 */
session_start();
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
    $FORMFIELD['rememberme'] = trim($_POST['rememberme']);
    //echo $FORMFIELD['rememberme'];

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
        echo 'An error has occurred: ' . '<br>' .  $errorMessage;
        echo '<br />';
    }
    else
    {

        ///this is what she has in her advising_functions.php


        //we do not have a database for users.. so we may have to create one. I won't put one
        // in until we decide what to call it together. I'll  just leave it blank.
        try
        {
            $conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
            $sql='SELECT * FROM accounts WHERE username = :userName';
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
        }
        else{
            //fetch the salt
            $secure = $login->fetch();
            $confirmSalt = $secure['salt'];
            //cypt the password to the hashed so it should match the one in the database
            $hashPassword = crypt($FORMFIELD['password'], $confirmSalt);

            try{
                $conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
                $sql='SELECT username FROM accounts WHERE username = :userName AND password = :password';
                $confirmLogin = $conn->prepare($sql);
                $confirmLogin->bindParam(':userName', $FORMFIELD['username']);
                $confirmLogin->bindParam(':password', $hashPassword);
                $confirmLogin->execute();
                $confirm = $confirmLogin->rowCount();

                $row2 = $confirmLogin->fetch();
            }
            catch(PDOException $e){
                echo  $e->getMessage();
                exit();
            }

            if($confirm<1){
                //echo "Entered wrong Password or hash is wrong!<br/>";
                echo "The username or password you entered is not recognized.";
                echo "<br>";
                echo"Please try again.";

            }
            else {
                try {
                    $conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
                    $sql = 'SELECT * FROM accounts WHERE username = :username';
                    $welcome = $conn->prepare($sql);
                    $welcome->bindValue(':username', $FORMFIELD['username']);
                    $welcome->execute();
                } catch (PDOException $e) {
                    echo $e->getMessage();
                    exit();
                }
                $row = $welcome->fetch();

                $_SESSION['username'] = $row['username'];
                $_SESSION['first'] = $row['first'];
                $_SESSION['token'] = getToken(10);
                $_SESSION['token2'] = getToken(20);
                //echo $_SESSION['token'];

                //echo $_SESSION['first'];
                $showForm = 0;
                if($FORMFIELD['rememberme'] == "yes")
                {
                    $_SESSION['password'] = $FORMFIELD['password'];

                    header("Location: makecookie3.php");
                }
                else
                {
                    header("Location: makecookie2.php");
                }
            }




        }
        //get username and salt from database
        //Do we have any requirements for password?




    }




}

//Test for kdragotta


if($showForm == 1){

    ?>
    <h1>Login</h1>
    <p>Please log in to access registration.</p>

    <form name="loginForm" id="loginForm" method="post" action="login.php">
        <table>
            <tr>
                <td>Username:</td>
                <td><input type="text" name="username" id="username" size="20"
                        value="<?php if(isset($_COOKIE['username'])){echo $_COOKIE['username'];} ?>"/></td>
            </tr>
            <tr>
                <td>Password:</td>
                <td><input type="password" name="password" id="password" size="20"
                        value="<?php if(isset( $_COOKIE['password'])){echo  $_COOKIE['password'];} ?>"/><a href="forgotpass.php" class="pass"> Forgot Password?</a></td>
            </tr><br><br>
            <tr>
                <td></td>
                <td><input type="submit" name="submit" value="Submit"/></td>
            </tr>
            <tr>
                <td><br></td>
                <td><input type="checkbox" name="rememberme" value="yes"/>Remember me!</td>
            </tr>
        </table>
    </form>
    <p>Don't have an account? <a href="register.php">Click Here!</a></p>
<?php
}
?>
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
        height: 50px;
    }
    p {
        margin-left: 20%;
    }

    .pass {

        font-size: 12px;
    }

    .textbox  {
        width: 200px;
    }



</style>


