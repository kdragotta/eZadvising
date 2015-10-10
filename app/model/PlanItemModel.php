<?php

require_once(__DIR__."/../config/config.php");

class PlanItemModel {
    private $conn = NULL;

    public function __construct() {
        $this->conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
    }


    public function movePlanItem($token, $studentId, $groupId, $semester,
                                 $year, $toSemester, $toYear, $plan)
    {
        try {
           // if (!validateToken($token, $studentId)) {
           //     return 403;
           // }

            //  if(empty($studentId)) return 404;

            $sql = 'UPDATE course_records '.
                   'SET semesterCode = :toSemester, year = :toYear '.
                   'WHERE studentId = :studentId '.
                   'AND groupId = :groupId '.
                   'AND semesterCode = :semester '.
                   'AND year = :year '.
                   'AND plan = :plan';

            //$sql = $sql. ' VALUES (null, :studentId, :courseId, null, :semester,
            // :year, :reqId, 2, :proposedReqId)';
            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':toSemester', $toSemester);
            $stmt->bindParam(':toYear', $toYear);

            $stmt->bindParam(':studentId', $studentId);
            $stmt->bindParam(':groupId', $groupId);
            $stmt->bindParam(':semester', $semester);
            $stmt->bindParam(':plan', $plan);
            $stmt->bindParam(':year', $year);

            $success = $stmt->execute();

        }//end try
        catch (PDOException $e) {
            return $sql . "<br>" . $e->getMessage();
            //return 500;
        }

        return $success;
    }

    public function addPlanItem($token, $studentId, $courseId, $hours,
                                $semester, $planYear, $progYear, $programId,
                                $reqId = null, $proposedReqId = null, $plan)
    {
    try {
        //if (!validateToken($token, $studentId)) {
        //    return 403;
        //}

        //  if(empty($studentId)) return 404;

        $sql = 'INSERT INTO course_records '.
               '(id, plan, studentId, courseId, grade, hours, semesterCode, '.
               'year, groupId, type, proposedReqId) '.
               ' VALUES (null, :plan, :studentId, :courseId, null, :hours, '.
               ':semester, :year, :groupId, 2, :proposedReqId)';
        $stmt = $this->conn->prepare($sql);


        if ($proposedReqId == '') {$proposedReqId = null;}  //Fixes mysql failure when proposedReqID is an empty string

        $stmt->bindParam(':studentId', $studentId);
        $stmt->bindParam(':plan', $plan);
        $stmt->bindParam(':semester', $semester);
        $stmt->bindParam(':year', $planYear);
        $stmt->bindParam(':courseId', $courseId);
        $stmt->bindParam(':groupId', $reqId);
        $stmt->bindParam(':proposedReqId', $proposedReqId);
        $stmt->bindParam(':hours', $hours);
        $success = $stmt->execute();
        $inserted = $success ? "yes" : "no";
        //echo "<h4>success:".$inserted."</h4>";
        $result = $this->getUpdatedRequirementForStudent($token, $studentId, $reqId, $programId, $progYear);
        //echo $result;


    }//end try
    catch (PDOException $e) {
        //echo $sql . "<br>" . $e->getMessage();
        return 500;
    }

    return $result;

}
    //NOT UPDATED DONE USED
    public function removePlanItem($token, $studentId, $courseId, $semester, $year, $reqId = null)
    {
        try {
            //if (!validateToken($token, $studentId)) {
            //    return 403;
            //}

            //  if(empty($studentId)) return 404;

            $sql = 'DELETE FROM course_records WHERE studentId=:studentId '.
                   'AND courseId= :courseId AND semester=:semester '.
                   'AND year=:year AND type=2';
            //$sql = $sql. ' VALUES (null, :studentId, :courseId, null, :semester,
            // :year, :reqId, 2, :proposedReqId)';
            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':studentId', $studentId);
            $stmt->bindParam(':semester', $semester);
            $stmt->bindParam(':year', $year);
            $stmt->bindParam(':courseId', $courseId);

            $success = $stmt->execute();
            $inserted = $success ? "yes" : "no";
            //echo "<h4>success:" . $inserted . "</h4>";


        }//end try
        catch (PDOException $e) {
            //echo $sql . "<br>" . $e->getMessage();
            return 500;
        }
    }

    private function getUpdatedRequirementForStudent($token, $studentId, $reqId, $programId, $year)
    {

        //validate student and token
        if (empty($programId) || empty($year)) return 404;

        //echo "starting getRrequiements";
        //return array of requirement objects
        // each requirement has an id, category, title, numcredit hours, min grade, and array of course objects
        //   each course object has an id, dept, num title, description
        $result = null;
        try {


            // echo "req: ".$reqId.'end';

            $sql = 'SELECT program_requirements.id as "reqId", program_requirements.category as "category", program_requirements.groupId as "groupId", title as "name", program_requirements.numCreditHours as "hours", program_requirements.minGrade as "grade" ';
            //$sql = 'select * ';
            $sql = $sql . ' FROM program_requirements WHERE ';
            $sql = $sql . ' program_requirements.programId=:programId AND program_requirements.catalogYear=:year';
            $sql = $sql . ' AND program_requirements.id=:reqId';

            //echo $programId.",".$year.",".$reqId.",";

            //$sql="select * from program_requirements where  program_requirements.id=:reqId AND program_requirements.catalogYear=:year AND program_requirements.programId=:programId";
            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':programId', $programId);
            $stmt->bindParam(':year', $year);
            $stmt->bindParam(':reqId', $reqId);
            $stmt->execute();

            $req = $stmt->fetch();
            // echo "prog query: ".$stmt->rowCount();
            if ($stmt->rowCount() <= 0) return $result;


            //  foreach($reqs as $req) {
            //echo "<p>json:";
            //echo print_r($req);
            //echo "</p>";

            /*** Each req object has:
             * id - requirementId
             * category - core, foundation, major, etc.
             * groupId - group of courses that satisfy requirement
             * groupName - description of courses
             * grade - min grade required for credits to count toward this requirement
             * hours - total hours required for this requirement
             *
             * hoursCounting - total number of hours that are completed twoard this requirement
             * hoursCountingPlanned - total number of hours that are PLANNED toward this requirement
             * complete - compares hours to hoursCounting (true or false)
             * completePlanned - compares hours to hoursCountingPlanned PLUS hoursCounting (true or false)
             *
             * courseOptions - array of course objects that satisfy the requirement (from the groupId)
             * Each course object has
             * id - courseId
             * dept
             * num
             * title
             * description
             *
             * coursesCounting - array of  courseRecord objects that are currently counting towrd
             * requirement
             * Each courseRecord object has
             * id - courseId
             * dept
             * num
             * title
             * desription
             * hours - hours taken or planned for this course record
             * type - 1 is complete, 2 is planned
             *
             * coursesCountingPlanned - array of courseRecord objects that are PLANNED but not
             * completed, and will count toward requirement
             * Each courseRecord object has : SAME AS ABOVE
             ***/
            $r = new stdClass();
            $r->id = $req['reqId'];
            $r->category = $req['category'];
            $r->groupId = $req['groupId'];
            $r->groupName = $req['name'];
            $r->grade = $req['grade'];
            $r->hours = $req['hours'];

            //now get courses for that group
            $secondSql = 'SELECT courses.id as "id", courses.defaultCreditHours as "hours", dept, num, title, description FROM course_groups, courses WHERE course_groups.groupId=:groupId AND course_groups.courseId=courses.id';

            $stmt2 = $this->conn->prepare($secondSql);

            $stmt2->bindParam(':groupId', $r->groupId);

            $stmt2->execute();

            $courses = $stmt2->fetchAll();
            //if($stmt2->rowCount() <= 0 ) return $result;
            //echo "<p>course count: ".$stmt2->rowCount()."</p>";
            $courseOptions = array();
            foreach ($courses as $course) {
                //build course, then add to array
                $c = new stdClass();
                $c->id = $course['id'];
                $c->dept = $course['dept'];
                $c->num = $course['num'];
                $c->title = $course['title'];
                $c->description = $course['description'];
                $c->hours = $course['hours'];

                $courseOptions[] = $c;


            }//end foreach courses as c
            $r->courseOptions = $courseOptions;

            //now get whether the requirement is met for the student
            $sqlCoursesTaken = 'SELECT courses.id, courses.dept, courses.num, '.
                               'courses.title, courses.description, '.
                               'course_records.hours, course_records.type,'.
                               'course_records.semesterCode, '.
                               'course_records.year, course_records.plan '.
                               'FROM courses, course_records '.
                               'WHERE course_records.studentId=:stuId '.
                               'AND course_records.courseId=courses.id '.
                               'AND course_records.reqId=:reqId';

            $stmtCoursesTaken = $this->conn->prepare($sqlCoursesTaken);
            $stmtCoursesTaken->bindParam(':stuId', $studentId);
            $stmtCoursesTaken->bindParam(':reqId', $r->id);
            $stmtCoursesTaken->execute();
            $coursesTaken = $stmtCoursesTaken->fetchAll();
            //echo "<p>course Taken count: ".$stmtCoursesTaken->rowCount()."</p>";

            //there is a record that meets this requirement (fully or partially)
            //TODO
            $coursesCounting = array();
            $coursesCountingPlanned = array();

            $hoursCounting = 0;
            $hoursCountingPlanned = 0;
            $somePlanned = true;
            if ($stmtCoursesTaken->rowCount() >= 1) {
                foreach ($coursesTaken as $course) {
                    $c = new stdClass();
                    $c->id = $course['id'];
                    $c->dept = $course['dept'];
                    $c->num = $course['num'];
                    $c->title = $course['title'];
                    $c->description = $course['description'];
                    $c->hours = $course['hours'];
                    $c->type = $course['type'];
                    $c->semester = $course['semesterCode'];
                    $c->year = $course['year'];
                    $c->plan = $course['plan'];
                    $c->dirty = false;

                    if ($c->type == 1) //complete
                    {
                        $hoursCounting = $hoursCounting + $c->hours;
                        $coursesCounting[] = $c;
                    } elseif ($c->type == 2) //planned
                    {
                        $somePlanned = true;
                        $hoursCountingPlanned = $hoursCountingPlanned + $c->hours;
                        $coursesCountingPlanned[] = $c;
                    }

                    $r->plan = $c->plan;

                }//end foreach
            }//end if
            $r->coursesCounting = $coursesCounting;
            $r->coursesCountingPlanned = $coursesCountingPlanned;

            $r->hoursCounting = $hoursCounting;
            $r->hoursCountingPlanned = $hoursCountingPlanned;
            $r->somePlanned = $somePlanned;

            if ($r->hours <= $hoursCounting) //req complete
            {
                $r->complete = true;
                $r->completePlanned = true;
            } elseif ($r->hours <= ($hoursCounting + $hoursCountingPlanned)) {
                $r->complete = false;
                $r->completePlanned = true;
            } else {
                $r->complete = false;
                $r->completePlanned = false;
            }

            $r->dirty = false;


            $result = $r;

            // }//end foreach reqs as req
            $jsonResult = json_encode($result);
            /*echo "<p>json:";
                   echo $jsonResult;
            echo "</p>";
            */
            //json_encode


        }//end try
        catch (PDOException $e) {
            //echo $sql . "<br>" . $e->getMessage();
            return 500;
        }

        //echo $jsonResult;
        return $jsonResult;
    }
}
?>