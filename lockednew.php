<?php
require_once "config.php";
function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$showform = 1;
$errormsg ="";
$temppass = generateRandomString();


$temppword = trim($temppass);






try
{
    $conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
    $sql = 'SELECT * FROM accounts WHERE username = :uname';
    $result = $conn->prepare($sql);
    $result->bindParam(':uname', $FORMFIELD['username']);
    $result->execute();
    $count = $result->rowCount();
}
catch (PDOException $e)
{
    echo 'Error fetching users: ' . $e->getMessage();
    exit();
}
if($count != 1)
{
    echo "This UserName is not registered.please try again";
}
else
{
    for ($i = 0; $i < 22; $i++) {
        $char22 .= substr("./ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789", mt_rand(0, 63), 1);
    }
    $salt = '$2a$07$' . $char22;
    echo "<br>";
    $securepwd = crypt($temppword,$salt);

    try {
        $conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
        $sql = 'UPDATE accounts SET password = :pword, salt = :salt WHERE username = :uname';
        $change = $conn->prepare($sql);
        $change->bindParam(':pword', $securepwd);
        $change->bindParam(':salt', $salt);
        $change->bindParam(':uname', $FORMFIELD['username']);

        $change->execute();
    }
    catch(PDOException $e)
    {
        echo '<div class = "error"><p>Error updating the database ' . $e->getMessage(). '</p></div>';
        exit();
    }


    $row = $result->fetch();


    $to = $row['email'];
    $subject = "Here is ".$row['first'];
    $from = "vadornsei@g.coastal.edu";
    $message = "Hello ".$row['first'].", Here is your Temporary Password: ".$temppword."\n";

    if(mail($to, $subject, $message, "from:".$from))
    {
        echo "Your Password has been reset! Please check your e-mail! For your temporary password";
    }

}