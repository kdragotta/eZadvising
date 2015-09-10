<?php
require_once("config.php");

function addPlanItem($token, $studentId, $courseId, $semester, $year, $reqId = null, $proposedReqId = null)
{
    echo "in addPlanItem";
    try {
        //  if(!validateToken($token, $studentId)) {return 403;}

        //  if(empty($studentId)) return 404;

        $conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
        $sql = 'INSERT INTO course_records (id, studentId, courseId, grade, semesterCode, year, reqId, type, proposedReqId) ';
        $sql = $sql . ' VALUES (null, :studentId, :courseId, null, :semester, :year, :reqId, 2, :proposedReqId)';
        $stmt = $conn->prepare($sql);

        echo "courseid: " . $courseId;

        $stmt->bindParam(':studentId', $studentId);
        $stmt->bindParam(':semester', $semester);
        $stmt->bindParam(':year', $year);
        $stmt->bindParam(':courseId', $courseId);
        $stmt->bindParam(':reqId', $reqId);
        $stmt->bindParam(':proposedReqId', $proposedReqId);
        $success = $stmt->execute();
        $inserted = $success ? "yes" : "no";
        $result = "<h4>success:" . $inserted . "</h4>";
        echo $result;


    }//end try
    catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
        return 500;
    }

    $conn = null;
    return $result;

}

echo "calling addPlanItem";
addPlanItem("ABC", 1, 29, 3, 2017);
echo "after calling addPlanItem";
?>