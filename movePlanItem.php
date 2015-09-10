<?php require_once "advising_functions.php";
$courseId = $_POST['courseId'];
$fromSem = $_POST['fromSem'];
$fromYear = $_POST['fromYear'];
$reqId = $_POST['reqId'];
$toSem = $_POST['toSem'];
$toYear = $_POST['toYear'];
$studentId = $_POST['studentId'];
/*function movePlanItem($token, $studentId, $courseId, $semester, $year, $toSemester, $toYear,$reqId=null)*/
echo movePlanItem("ABC", $studentId, $courseId, $fromSem, $fromYear, $toSem, $toYear, $reqId); ?>

