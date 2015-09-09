<?php require_once "advising_functions.php";
$courseId = $_POST['courseId'];
$semesterCode = $_POST['semesterCode'];
$planYear = $_POST['planYear'];
$reqId = $_POST['reqId'];
$proposedReqId = $_POST['proposedReqId'];
$hours = $_POST['hours'];
$programId = $_POST['programId'];
$progYear = $_POST['progYear'];
echo addPlanItem("ABC", 1, $courseId, $hours, $semesterCode, $planYear, $progYear, $programId, $reqId, $proposedReqId); ?>

