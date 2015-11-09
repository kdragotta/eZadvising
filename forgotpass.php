<?php
/**
 * Created by PhpStorm.
 * User: John
 * Date: 10/27/2015
 * Time: 11:27 PM
 */
session_start();
$pagetitle = "Forgot Password";
require_once 'config.php';

//show form for user to fill out
$showform = 1;
$errorMessage = '';
$FORMFIELD['email'] = $_POST['email'];
if(isset($_POST['submit'])){



    if(empty($FORMFIELD['email'])){
        $uid = FALSE;
        $errorMessage .= 'Please enter your email address';
    }

    if($errorMessage != '') {
        //echo 'in if: 25';
        //echo '<br>';
        echo 'An error has occured: ' . '<br>' . $errorMessage;
        echo '<br/>';
        //echo 'in if: line 29';
    }
    else {
        //echo 'in else:32';

        //check to see if email address is in database
        $conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
        $stmt = 'SELECT email FROM accounts WHERE email = :email';
        $s = $conn->prepare($stmt);
        $s->bindValue(':email', $_POST['email']);
        $s->execute();
        //$row = $s->fetch(PDO::FETCH_ASSOC);
        $rowcount = $s->rowCount();

        //echo "past the stmt and rowcount = " . $rowcount . '<br>';
        if ($rowcount < 1) {
            $errorMessage .= 'The email you provided is not recognized.';
            echo 'Error: ' . $errorMessage;
            echo 'in if: 47';
        } else {
            $uid = TRUE;
            //echo 'in else: 50';
        }
    }
    if ($uid) {
        //echo 'in if: 54';
        //create a new, random password
        $p = substr(md5(uniqid(rand(), 1)), 3, 10);
        //create query
        $conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
        $stmt = 'UPDATE accounts SET resetpassword = :password WHERE email = :email';
        $s = $conn->prepare($stmt);
        $s->bindValue(':password', $p);
        $s->bindValue(':email', $_POST['email']);
        $s->execute();
        $count = $s->rowCount();

        if ($count == 1) {
           // echo 'in if; send email: 67';
            //send email
            $body = 'Your password to log into eZadvising has been temporarirly changed to' . $p . '. Please log in using this password and your email. At that time you may change your password to something more familiar.';
            mail($_POST['email'], 'Your temporary password.', $body, 'From: admin@eZadvising.com');
            echo '<h3>Your password has been changed. You will receive the new, temporary password at the email address with which you provided.</h3>';
            echo '<p>After receiving your temporary password <a href="reset.php">Click Here</a> to log into your account.</p>';
            $_SESSION['email'] = $confirmedemail;
            $showform = 0;
        } else {
            //echo 'in else; send email: 76';
            $errorMessage .= 'Your password could not be changed due to a system error. We appologize for any inconvience.';
        }
    } else {
        //echo 'in else: 80';
        $errorMessage .= 'Please try again!';
    }

}
if($showform == 1){
    ?>
    <h1>Reset Your Password</h1>
    <p>Please enter your email address below and your password will be reset.</p>

    <form action="forgotpass.php" method="post">
        <fieldset>
            <legend>
                Reset Password
            </legend>
            <table>
                <tr>
                    <th><lable for="email">Email:</lable></th>
                    <td><input type="text" name="email" size="20" maxlength="40"
                               value="<?php if(isset($_POST['email'])) echo $_POST['email'];?>"/></td>
                </tr>
                <tr>
                    <th>SUBMIT:</th>
                    <td><input type="submit" name="submit" value="Reset My Password"</td>
                </tr>
            </table>
        </fieldset>
    </form>
<?php
}
?>