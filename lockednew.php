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
    $lockedout = 1;
    try {
        $conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
        $sql = 'UPDATE accounts SET password = :pword, salt = :salt, lockedout = :lockedout, resetpassword = :resetpass WHERE username = :uname';
        $change = $conn->prepare($sql);
        $change->bindParam(':pword', $securepwd);
        $change->bindParam(':salt', $salt);
        $change->bindParam(':uname', $FORMFIELD['username']);
        $change->bindParam(':lockedout', $lockedout);
        $change->bindParam(':resetpass', $temppword);
        $change->execute();
    }
    catch(PDOException $e)
    {
        echo '<div class = "error"><p>Error updating the database ' . $e->getMessage(). '</p></div>';
        exit();
    }


    $row = $result->fetch();


//    $to = $row['email'];
//    $subject = "Here is ".$row['first'];
//    $from = "vadornsei@g.coastal.edu";
//    $message = "Hello ".$row['first'].", Here is your Temporary Password: ".$temppword."\n";
//
//    if(mail($to, $subject, $message, "from:".$from))
//    {
//        echo "Your Password has been reset! Please check your e-mail! For your temporary password";
//    }
//    else{
//        echo "Your Password has been reset! Please check your e-mail! For your temporary password";
//
//    }
include("class.phpmailer.php");
include("class.smtp.php");

    date_default_timezone_set('America/New_York');
    $mail = new PHPMailer();
    $body = "Hello ".$row['first'].", Here is your Temporary Password: ".$temppword."\n";
    $mail->IsSMTP();                            // telling the class to use SMTP
    $mail->Host = "smtp.gmail.com";       // SMTP server
    $mail->SMTPAuth   = true;                   // enable SMTP authentication
    $mail->SMTPSecure = 'tls';                  // Supported
    $mail->Host       = "smtp.gmail.com";       // sets the SMTP server
    $mail->Port       = 587;                    // set the SMTP port for the GMAIL server
    $mail->Username   = "ferretqueen1313@gmail.com";         // SMTP account username (how you login at gmail)
    $mail->Password   = "ferrets1313";      // SMTP account password (how you login at gmail)
    $mail->setFrom('noreply@gmail.com', 'ezadvising');

    $mail->addReplyTo('noreply@gmail.com', "ezadvising");

    $mail->Subject    = "Temporary Password";

    $mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

    $mail->msgHTML($body);

    $address = $row['email'];
    $mail->addAddress($address, $row['first']);
    // if you have attachments
    // $mail->addAttachment("phpmailer.gif");      // attachment
    //$mail->addAttachment("phpmailer_mini.gif"); // attachment

    if(!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
        echo "Message sent! this is the";
    }
}
// Test the connection

?>

