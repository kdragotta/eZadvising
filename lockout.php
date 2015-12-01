<?php
require_once "config.php";

$FORMFIELD['username'] = strtolower(htmlspecialchars(stripslashes(trim($_POST['username']))));

try
{



    $sql='SELECT * FROM accounts WHERE username = :userName';
    $login = $conn->prepare($sql);
    $login->bindParam(':userName', $FORMFIELD['username']);
    $login->execute();
    $count = $login->rowCount();



    $conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
    $sql = "SELECT usna FROM lockedout WHERE usna = :uname";
    $s = $conn->prepare($sql);
    $s->bindParam(':uname',  $FORMFIELD['username']);
    $s->execute();
    $count = $s->rowCount();
}
catch (PDOException $e)
{
    echo '<div class = "error"><p>Error fetching users: ' . $e->getMessage(). '</p></div>';
    exit();
}

if ($count == 0)
{
    try
    {
        $conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
        $sql = "Insert into lockedout (`usna`) values(:uname)";
        $try = $conn ->prepare($sql);
        $try -> bindParam(':uname', $FORMFIELD['username']);
        $try->execute();
    }
    catch(PDOException $e)
    {
        echo 'Error fetching users: ' . $e->getMessage();
        exit();
    }

}
else
{
    try
    {
        $conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
        $sql = "SELECT * FROM lockedout WHERE usna = :uname";
        $ss = $conn->prepare($sql);
        $ss->bindParam(':uname', $FORMFIELD['username']);
        $ss->execute();
        $count = $ss->rowCount();
    }
    catch (PDOException $e)
    {
        echo '<div class = "error"><p>Error fetching users: ' . $e->getMessage(). '</p></div>';
        exit();
    }
    $rows = $ss -> fetch();
    $t = $rows['try'];


    if($t < 4 && $t >=0)
    {
        $t = $t + 1;
        try
        {
            $conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
            $sql = 'update lockedout set try = :try WHERE usna = :uname';
            $try2 = $conn->prepare($sql);
            $try2 -> bindParam(':try', $t);
            $try2 -> bindParam(':uname', $FORMFIELD['username']);
            $try2->execute();

        }
        catch(PDOException $e)
        {
            echo 'Error fetching users: ' . $e->getMessage();
            exit();
        }

        $random = $try2->fetch();
        echo $random['try']."<br/>";
    }
    elseif ($t >=4 || $t == -1)
    {
        $t = 0;
        require "lockednew.php";
        try
        {
            $conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
            $sql = 'update lockedout set try = :try WHERE usna = :uname';
            $try2 = $conn->prepare($sql);
            $try2 -> bindParam(':try', $t);
            $try2 -> bindParam(':uname', $FORMFIELD['username']);
            $try2->execute();

        }
        catch(PDOException $e)
        {
            echo 'Error fetching users: ' . $e->getMessage();
            exit();
        }
        echo "You are Locked Out!";

    }

}


