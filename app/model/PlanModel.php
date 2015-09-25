<?php

require_once(__DIR__."/../config/config.php");

class PlanModel {
    private $conn = NULL;

    public function __construct() {
        $this->conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
    }

    //UPDATED DONE USED
    public function movePlanItem($token, $studentId, $courseId, $semester,
                                 $year, $toSemester, $toYear, $reqId = null)
    {
        try {
           // if (!validateToken($token, $studentId)) {
           //     return 403;
           // }

            //  if(empty($studentId)) return 404;

            $sql = 'UPDATE course_records SET semesterCode=:toSemester, year=:toYear WHERE studentId=:studentId AND courseId= :courseId AND semesterCode=:semester AND year=:year AND type=2';
            //$sql = $sql. ' VALUES (null, :studentId, :courseId, null, :semester, :year, :reqId, 2, :proposedReqId)';
            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':studentId', $studentId);
            $stmt->bindParam(':semester', $semester);
            $stmt->bindParam(':year', $year);
            $stmt->bindParam(':courseId', $courseId);
            $stmt->bindParam(':toSemester', $toSemester);
            $stmt->bindParam(':toYear', $toYear);

            $success = $stmt->execute();
            $inserted = $success ? "yes" : "no";
            echo "<h4>success:".$inserted."</h4>";


        }//end try
        catch (PDOException $e) {
            return $sql . "<br>" . $e->getMessage();
            //return 500;
        }

        $conn = null;
        return $inserted;
    }

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

}
?>