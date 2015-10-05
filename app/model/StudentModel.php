<?php

class StudentModel
{
    private $conn = NULL;

    public function __construct()
    {
        $this->conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
    }

    //todo: use json
    public function getRemainingCourses()
    {
        try {
            // set the PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "select * from courses";
            // $conn->exec($sql);
            //echo "Database queried successfully<br>";
            echo "Connected.<br />";


            $stmt = $this->conn->prepare($sql);
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
    }

    //todo: use json
    public function meetsPreReqs($stuId, $courseId, $semester, $year)
    {
        try {
            // set the PDO error mode to exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            /* $sql = 'SELECT p.id, p.dept, p.num FROM courses, courses as p, prereqs, prereq_detail
         WHERE courses.num="150" AND courses.id = prereqs.courseId AND
            prereqs.id = prereq_detail.prereqId AND prereq_detail.type=2
         AND prereq_detail.courseId=p.id';*/
            $sql = 'SELECT prereqs.expression FROM courses, prereqs '.
                   'WHERE courses.num="150" AND courses.id = prereqs.courseId';


            $stmt = $this->conn->prepare($sql);

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
    }

    //UPDATED DONE USED
    function getRequirementsForStudent($token, $studentId, $programId = 0, $year = 0)
    {
        //validate student and token
        if (empty($programId) || empty($year)) return 404;

        //echo "starting getRrequiements";
        //return array of requirement objects
        // each requirement has an id, category, title, numcredit hours,
        // min grade, and array of course objects
        //   each course object has an id, dept, num title, description
        $result = array();
        try {
            //no validation needed; nothing personal


            $conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
            $sql = 'SELECT program_requirements.id as "reqId", '.
                   'program_requirements.category as "category", '.
                   'program_requirements.groupId as "groupId", groups.name '.
                   'as "name", program_requirements.numCreditHours as "hours"'.
                   ', program_requirements.minGrade as "grade" '.
                   'FROM program_requirements, groups WHERE '.
                   'program_requirements.programId=:programId AND '.
                   'program_requirements.catalogYear=:year '.
                   'AND program_requirements.groupId=groups.id';


            $stmt = $conn->prepare($sql);

            $stmt->bindParam(':programId', $programId);
            $stmt->bindParam(':year', $year);
            $stmt->execute();

            $reqs = $stmt->fetchAll();

            if ($stmt->rowCount() <= 0) return $result;


            foreach ($reqs as $req) {


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
                 * description
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
                $r->plan = "0";

                //now get courses for that group
                $secondSql = 'SELECT courses.id as "id", courses.default'.
                             'CreditHours as "hours", dept, num, title, '.
                             'description FROM course_groups, courses WHERE '.
                             'course_groups.groupId=:groupId AND '.
                             'course_groups.courseId=courses.id';

                $stmt2 = $conn->prepare($secondSql);

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
                $sqlCoursesTaken =
                    'SELECT courses.id, courses.dept, courses.num, '.
                    'courses.title, courses.description, course_records.'.
                    'hours, course_records.type, course_records.semesterCode,'.
                    'course_records.year, '.
                    'course_records.plan '.
                    'FROM courses, course_records '.
                    'WHERE course_records.studentId=:stuId '.
                    'AND course_records.courseId=courses.id '.
                    'AND course_records.reqId=:reqId';

                $stmtCoursesTaken = $conn->prepare($sqlCoursesTaken);
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


                $result[] = $r;

            }//end foreach reqs as req
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

        return $jsonResult;

    }
}
?>