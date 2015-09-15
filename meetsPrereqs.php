<?php

require_once("config.php");
require_once("advising_functions.php");
require_once("pe.php");

$stuId = $_GET['sid'];
$courseId = $_GET['cid'];
$semester = $_GET['sem'];
$year = $_GET['year'];

try {
    $conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    /* $sql = 'SELECT p.id, p.dept, p.num FROM courses, courses as p, prereqs, prereq_detail
 WHERE courses.num="150" AND courses.id = prereqs.courseId AND prereqs.id = prereq_detail.prereqId AND prereq_detail.type=2
 AND prereq_detail.courseId=p.id';*/
    $sql = 'SELECT prereqs.expression FROM courses, prereqs
WHERE courses.num="150" AND courses.id = prereqs.courseId';


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
    /* foreach($courses as $c) {
     echo "<a href='displayPost.php?id=".$c['id']."'>";
         echo "<li>".$c["id"]." ".$c["dept"]." ".$c["num"]."</li>";
     echo "</a>";
     }
     */
    foreach ($courses as $row) {
        echo "<li>" . $row["expression"] . "</li>";
        $theExpr = $row['expression'];
        $theArray = explode(" ", $theExpr);
        foreach ($theArray as $token) {
            echo "<p> token: " . $token . "</p>";
        }

    }
    echo "</ul>";


} catch (PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
}

$conn = null;

?>