<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 10/27/2015
 * Time: 11:59 PM
 */
$pagetitle = "Reset Password";
require_once 'config.php';

$showform = 1;
$errorMessage = '';

if(isset($_POST['submit'])){
    $FORMFIELD['email'] = strtolower(htmlspecialchars(stripslashes(trim($_POST['email']))));
    $FORMFIELD['password'] = trim($_POST['password']);

    //check for empty fields
    if(empty($FORMFIELD['email'])) {$errorMessage .= 'Email is missing.<br>';}
    if(empty($FORMFIELD['password'])){$errorMessage .= 'Password is missing.<br>';}

    //display any errors
    if($errorMessage != ''){
        echo 'An error has occurred: ' . '<br>' .  $errorMessage;
        echo '<br />';
    }
    else{
        //get the email and temporary password from the database
        try{
            $sql = 'SELECT email, reset_password FROM accounts WHERE email = :email';
            $s = $pdo->prepare($sql);
            $s->bindValue(':email', $FORMFIELD['email']);
            $s->execute();
            $count = $s->rowCount();
        }catch(PDOException $e){
            echo 'Error fetching users: ' .$e->getMessage();
            exit();
        }
        if($count < 1){
            echo 'An error has occured: ' . '<br>' . $errorMessage;
            echo '<br/>';
        }
        else{
            $row = $s->fetch();
            $confirmedemail = $row['email'];
            $temppass = $row['reset_password'];

            try{
                $sql2 = 'SELECT * FROM accounts WHERE email = :email AND reset_password = :temppass';
                $s2 = $pdo->prepare($sql2);
                $s2->bindValue(':email', $confirmedemail);
                $s2->bindValue(':temppass', $temppass);
                $s2->execute();
                $count2 = $s2->rowCount();
            }catch(PDOException $e2){
                echo 'Error fetching users 2: ' . $e2->getMessage();
                exit();
            }
            $row2 = $s2->fetch();
            if($count2 != 1){
                echo 'An error has occured' . '<br>' . $errorMessage;
                exit();
            }
            else{
                $_SESSION['username'] = $row2['username'];
                $_SESSION['email'] = $confirmedemail;
                $_SESSION['first'] = $row2['first'];
                $showform = 0;
                header("Location: resetpassword.php");
            }
        }
    }
}
if($showform == 1){
    ?>
    <p>Please enter your email and temporary password provided via email below:</p>
    <form name="resetForm" id="resetForm" method="post" action="reset.php">
        <table>
            <tr>
                <td>Email:</td>
                <td><input type="text" name="email" id="email" size="45"/></td>
            </tr>
            <tr>
                <td>Password:</td><td><input type="password" name="password" id="password" size="20"/></td>
            </tr>
            <tr>
                <td>Submit:</td>
                <td><input type="submit" name="submit" value="submit"/></td>

            </tr>
        </table>
    </form>
<?php

}
?>