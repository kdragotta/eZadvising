<?php
require_once "connect.php";

$formfield['uname'] = strtolower(htmlspecialchars(stripslashes(trim($_POST['uname']))));

try
{
    $sql = "SELECT usna FROM lockedout WHERE usna = :uname";
    $s = $pdo->prepare($sql);
    $s->bindValue(':uname', $formfield['uname']);
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
        $sql = "Insert into lockedout (`usna`) values(:uname)";
        $try = $pdo ->prepare($sql);
        $try -> bindvalue(':uname', $formfield['uname']);
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
        $sql = "SELECT * FROM lockedout WHERE usna = :uname";
        $ss = $pdo->prepare($sql);
        $ss->bindValue(':uname', $formfield['uname']);
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


    if($t < 4)
    {
        $t = $t + 1;
        try
        {
            $sql = 'update lockedout set try = :try WHERE usna = :uname';
            $try2 = $pdo->prepare($sql);
            $try2 -> bindValue(':try', $t);
            $try2 -> bindValue(':uname', $formfield['uname']);
            $try2->execute();

        }
        catch(PDOException $e)
        {
            echo 'Error fetching users: ' . $e->getMessage();
            exit();
        }
    }
    else
    {
        $t = 0;
        require "lockednew.php";
        try
        {
            $sql = 'update lockedout set try = :try WHERE usna = :uname';
            $try2 = $pdo->prepare($sql);
            $try2 -> bindValue(':try', $t);
            $try2 -> bindValue(':uname', $formfield['uname']);
            $try2->execute();

        }
        catch(PDOException $e)
        {
            echo 'Error fetching users: ' . $e->getMessage();
            exit();
        }

    }

}


