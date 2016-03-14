<?php
$pagetitle = 'Update User Information';
require_once 'config.php';
require_once 'advising_functions.php';
session_start();
//require_once "connect.php";




$errormsg = "";
$showform = 1;



if (isset($_POST['submit']))
{

    $_GET['x'] = $_POST['x'];

    //sanitize the rest of this data
    $formfield['firstName'] = stripslashes(trim($_POST['firstName']));
    $formfield['lastName'] = stripslashes(trim($_POST['lastName']));
    $formfield['middleName'] = stripslashes(trim($_POST['middleName']));
    $formfield['username'] = stripslashes(trim($_POST['username']));
    $formfield['major'] = stripslashes(trim($_POST['major']));
    $formfield['minor'] = stripslashes(trim($_POST['minor']));
    $formfield['minCredit'] = stripslashes(trim($_POST['maxCredit']));
    $formfield['maxCredit'] = stripslashes(trim($_POST['maxCredit']));
    $formfield['email'] = stripslashes(trim($_POST['email']));

echo $formfield['firstName'];
    //finish the rest of the error checking
    if(empty($formfield['firstName']))

    {$errormsg .= "<p>First name is empty</p>";}

    if(empty($formfield['middleName']))

    {$errormsg .= "<p>Middle name is empty</p>";}

    if(empty($formfield['lastName']))
    {$errormsg .= "<p>Last name is empty</p>";}

    if(empty($formfield['username']))
    {$errormsg .= "<p>Username is empty</p>";}

    if(empty($formfield['major']))
    {$errormsg .= "<p>Major is empty</p>";}

    if(empty($formfield['minor']))
    {$errormsg .= "<p>Minor is empty</p>";}

    if(empty($formfield['minCredit']))
    {$errormsg .= "<p>Minimum Credits are empty</p>";}

    if(empty($formfield['maxCredit']))
    {$errormsg .= "<p>Maximum Credits are empty</p>";}

    if(empty($formfield['email']))
    {$errormsg .= "<p>Email is empty</p>";}




    //validate email
    //CHECK TO SEE IF EMAIL IS VALID - we called this field mytextbox, but we would normally call it email
    if(!preg_match('/^[\w\.\-]+@([\w\-]+\.)+[a-z]+$/i', $formfield['email']))
    {
        $errormsg .= "Email is not valid.<br>";
    }


    //CHECK TO SEE IF THIS EMAIL (mytextbox) HAS ALREADY BEEN USED
    if($formfield['username'] != $_POST['origeuname'])
    {


        try
        {
            $conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
            $sql = 'SELECT username FROM accounts WHERE username = :uname';
            $checkUser = $conn->prepare($sql);
            $checkUser->bindValue(':uname', $formfield['username']);
            $checkUser->execute();
            $count = $checkUser->rowCount();
        }
        catch (PDOException $e)
        {
            echo 'Error fetching users: ' . $e->getMessage();
            exit();
        }
        if ($count > 0)
        {
            $errormsg .= "THIS USERNAME IS ALREADY TAKEN.";
        }

    }

    if($formfield['email'] != $_POST['origeemail'])
    {


        try
        {
            $conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
            $sql = 'SELECT username FROM accounts WHERE email = :email';
            $checkEmail = $conn->prepare($sql);
            $checkEmail->bindValue(':email', $formfield['email']);
            $checkEmail->execute();
            $count = $checkEmail->rowCount();
        }
        catch (PDOException $e)
        {
            echo 'Error fetching email: ' . $e->getMessage();
            exit();
        }
        if ($count > 0)
        {
            $errormsg .= "THIS EMAIL IS ALREADY TAKEN.";
        }
    }

    //END OF ERROR CHECKING--------------------------------------------------------------
    if($errormsg != "")
    {
        echo "<p class ='error'>THERE ARE ERRORS!!!!!!!!!!!</p>";
        echo "<div class ='error'> $errormsg </div>";
    }
    else
    {
        try {
            $conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
            $sql = 'UPDATE accounts SET
                username = :username,
                email= :email,
                first = :first,
                middle= :middle,
                last= :lastName,
                major = :major,
                minor = :minor,
                minCredit = :minCredit,
                maxCredit = :maxCredit
                WHERE id = :ID';
            $updateUsers = $conn->prepare($sql);
            $updateUsers->bindValue(':first', $formfield['firstName']); // using data from form
            $updateUsers->bindValue(':email', $formfield['email']); // using data from form
            $updateUsers->bindValue(':username', $formfield['username']);
            $updateUsers->bindValue(':lastName', $formfield['lastName']);
            $updateUsers->bindValue(':minor', $formfield['minor']);
            $updateUsers->bindValue(':minCredit', $formfield['minCredit']);
            $updateUsers->bindValue(':maxCredit', $formfield['maxCredit']);
            $updateUsers->bindValue(':major', $formfield['major']);
            $updateUsers->bindValue(':middle', $formfield['middleName']);
            $updateUsers->bindValue(':ID', $_SESSION['studentid']); // using data from form
            $updateUsers->execute();
        }
        catch(PDOException $e) {
            echo 'Error updating the database' . $e->getMessage();
            exit();
        }
        echo $_POST['x'];
        echo '<p>Thank you for updating your information!</p>';
        echo '<a href="eatouch4.php">Back to eZadvising</a>';
        $showform=0;
    }
}
if($showform == 1)
{

    try
    {
        $conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
        $sql = 'SELECT * FROM accounts WHERE ID = :ID';
        $retrieveUser = $conn->prepare($sql);
        $retrieveUser->bindValue(':ID', $_GET['x']);
        $retrieveUser->execute();
    }
    catch (PDOException $e)
    {
        echo 'Error fetching users: ' . $e->getMessage();
        exit();
    }


    $row = $retrieveUser->fetch();
    if($_SESSION['studentid'] != $row['id'])
    {
        echo "<p class='error'>You are not authorized to view this page.</p>";

        exit();
    }
    ?>
    <h1>Update User Information</h1>
    <form name="userupdate" id="userupdate" method="post" action="update.php">
        <table>
            <tr><td>First Name</td><td><input type="text" name="firstName" id="firstName" size="20" required
                                              value="<?php if(isset($row['first'])){echo $row['first'];} ?>"/>
                </td></tr>
            <tr><td>Middle Initial</td><td><input type="text" name="middleName" id="middleName" size="3" maxlength="1"
                                                  value="<?php if(isset($row['middle'])){echo $row['middle'];} ?>"/>
                </td></tr>
            <tr><td>Last Name</td><td><input type="text" name="lastName" id="lastName" size="20" required
                                             value="<?php if(isset($row['last'])){echo $row['last'];} ?>"/>
                </td></tr>
            <tr><td>Major</td><td><input type="text" name="major" id="major" size="20" required
                                             value="<?php if(isset($row['major'])){echo $row['major'];} ?>"/>
                </td></tr>
            <tr><td>Minor</td><td><input type="text" name="minor" id="minor" size="20" required
                                             value="<?php if(isset($row['minor'])){echo $row['minor'];} ?>"/>
                </td></tr>
            <tr><td>Min Credit</td><td><input type="text" name="minCredit" id="minCredit" size="20" required
                                             value="<?php if(isset($row['minCredit'])){echo $row['minCredit'];} ?>"/>
                </td></tr>
            <tr><td>Max Credit</td><td><input type="text" name="maxCredit" id="maxCredit" size="20" required
                                             value="<?php if(isset($row['maxCredit'])){echo $row['maxCredit'];} ?>"/>
                </td></tr>
            <tr><td>Username</td><td><input type="text" name="username" id="username" size="20" required
                                             value="<?php if(isset($row['username'])){echo $row['username'];} ?>"/>
                </td></tr>
            <tr><td>Email</td><td><input type="text" name="email" id="email" size="20" required
                                             value="<?php if(isset($row['email'])){echo $row['email'];} ?>"/>
                </td></tr>
<!--            finish this table-->


            <input type="hidden" name="origeuname" id="origeuname" value="<?php echo $row['username'];?>">
            <input type="hidden" name="origeemail" id="origeemail" value="<?php echo $row['email'];?>">
            <input type="hidden" name="x" id="x" value="<?php echo $row['id'];?>">
            <tr><td>Submit</td><td><input type="submit" name="submit" value="submit"></td>
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

}//showform
?>