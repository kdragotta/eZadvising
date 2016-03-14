<?php
$pagetitle = 'Update Password';
require_once 'config.php';
require_once 'advising_functions.php';
session_start();
/**
 * Created by PhpStorm.
 * User: ferretqueen1313
 * Date: 3/14/16
 * Time: 3:32 PM
 */


$errormsg;
$showform = 1;
if (isset($_POST['submit']))
{
    $_GET['x'] = $_POST['x'];

    //sanitize the rest of this data
    $formfield['password'] = stripslashes(trim($_POST['password']));
    $formfield['confirmPass'] = stripslashes(trim($_POST['confirmPass']));


    if(empty($formfield['password']))

    {$errormsg .= "<p>Password is empty</p>";}

    if(empty($formfield['confirmPass']))

    {$errormsg .= "<p>Confirm Password is empty</p>";}

    if($formfield['password'] != $formfield['confirmPass'])

    {$errormsg .= "<p>Your passwords don't match</p>";}

    if(isset($errormsg)) {
        echo $errormsg;
    }
}



if($showform == 1) {

    try
    {
        $conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
        $sql = 'SELECT * FROM accounts WHERE ID = :ID';
        $retrieveUser = $conn->prepare($sql);
        $retrieveUser->bindValue(':ID', $_GET['x']);
        $retrieveUser->execute();
    }
    catch (PDOException $e) {
        echo 'Error fetching users: ' . $e->getMessage();
        exit();
    }


    $row = $retrieveUser->fetch();
    if ($_SESSION['studentid'] != $row['id']) {
        echo "<p class='error'>You are not authorized to view this page.</p>";

        exit();
    }
    ?>

    <h1>Change Password</h1>
    <form name="newPass" id="newPass" method="post" action="newPassword.php">
        <table>
            <tr>
                <td>Password</td>
                <td><input type="password" name="password" id="password" size="20"
                           value="<?php if (isset($formField['password'])) {
                               echo $formField['password'];
                           } ?>"/></td>
            </tr>
            <tr>
                <td>Confirm Password</td>
                <td><input type="password" name="confirmPass" id="confirmPass" size="20"
                           value="<?php if (isset($formField['confirmPass'])) {
                               echo $formField['confirmPass'];
                           } ?>"/></td>
            </tr>
            <tr>
                <td>
                    <input type="hidden" name="x" id="x" value="<?php echo $row['id']; ?>">

                </td>
            </tr>
            <tr><td>Submit</td><td><input type="submit" name="submit" value="Submit"></td> </tr>
        </table>
    </form>


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
        height: 50px;;
    }
    p {
        margin-left: 20%;
    }

    .textbox  {
        width: 200px;
    }

</style>
