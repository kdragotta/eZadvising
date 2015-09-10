<?php

require_once("config.php");
require_once("pe.php");

try {
    $conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "select * from courses";
    // $conn->exec($sql);
    //echo "Database queried successfully<br>";
    echo "Connected.<br />";


    $stmt = $conn->prepare($sql);
    //$authorSearch="cox";
    //$stmt->bindParam(':qAuthor', $authorSearch);
    // $stmt = $conn->prepare($sql); 


    $stmt->execute();

    //$result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 
    $courses = $stmt->fetchAll();
    echo count($courses);

    echo "<ul class='ulist'>";
    foreach ($courses as $c) {
        echo "<a href='displayPost.php?id=" . $c['id'] . "'>";
        echo "<li>" . $c["id"] . " " . $c["dept"] . " " . $c["num"] . "</li>";
        echo "</a>";
    }

    echo "</ul>";


} catch (PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
}

$conn = null;

?>