<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 10/28/2015
 * Time: 12:18 AM
 */
session_start();
require_once 'config.php';
$errormessage = '';
$showform = 1;
?>
<h2><?php $pagetitle = 'Reset Password';?></h2>

<?php
if(isset($_POST['submit'])){
    $FORMFIELD['password'] = (trim($_POST['password']));
    $FORMFIELD['password2'] = (trim($_POST['password2']));


    //check for empty fields

    if(empty($FORMFIELD['password'])){$errormessage .= 'Password is missing.<br>';}
    if(empty($FORMFIELD['password2'])){$errormessage .= 'Confirm your new password. <br>';}

    //check to see if password is valid

    if(!preg_match('/^(?=.*\d)(?=.*[A-Z]).{8,}$/', $FORMFIELD['password'])){
        $errormsg .= "<p>Password is not valid.</p>";
    }

    //check to see if passwords match
    if($FORMFIELD['password'] != $FORMFIELD['password2']){
        $errormessage .= 'Passwords do not match!';
    }

    //display any errors
    if($errormessage != ''){
        echo 'An error has occured: ' . $errormessage . '<br>';
    }
    else{
        //If there are no errors than hash the password
        for ($i = 0; $i < 22; $i++) {
            $char22 .= substr("./ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789", mt_rand(0, 63), 1);
        }
        //salt makes it more secure
        $salt = '$2a$07$' . $char22;
        echo "<br>";
        //combine the salt with the hashed password
        $hashedPassword = crypt($FORMFIELD['password'],$salt);

        try{
           // echo 'in try: 50';
            $conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
            $sql = 'UPDATE accounts SET password = :password, salt = :salt WHERE email = :email';
            $sqlprep = $conn->prepare($sql);
            $sqlprep->bindValue(':password', $hashedPassword);
            $sqlprep->bindValue(':salt', $salt);
            $sqlprep->bindValue(':email', $_SESSION['email']);
            $sqlprep->execute();
            //echo 'after execute stmt: 58';
        }catch (PDOException $e){
            echo 'Error inserting into registration' . $e->getMessage();
            exit();
        }
        //inform the user that their information was updated
        echo '<p>Your information has been updated! <a href="login.php">Login Page</a></p>';
        $showform = 0;
    }
}
if($showform == 1){
    ?>
<p>Now that you have successfully logged in using the provided temporary password, please take this time to enter a new password for your account. </p>
<hr>
<form name="resetpassword" id="resetpassword" action="resetpassword.php" method="post">
    <fieldset>
        <legend>PASSWORD UPDATE</legend>
        <table>
            <tr>
                <th><label for="password">Password:</label></th><td><input type="password" name="password" id="password" value="<?php if(isset($FORMFIELD['password'])){echo $FORMFIELD['password'];}?>" required/>*Must include at least 1 capital letter and 1 number and must be at least 8 characters! </td>
            </tr>
            <tr>
                <th><label for="password2">Confirm Password:</label></th><td><input type="password" name="password2" id="password2" value="<?php if(isset($FORMFIELD['password2'])) {echo $FORMFIELD['password2'];}?>" required/></td>
            </tr>
            <tr><th>SUBMIT: </th><td><input type="submit" name="submit" value="submit"></td></tr>
        </table>
    </fieldset>
</form>
<?php
}
?>