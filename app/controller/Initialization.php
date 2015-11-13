<?php
/**
 * Created by PhpStorm.
 * User: phillip
 * Date: 11/12/15
 * Time: 6:47 PM
 */

require_once('../config/config.php');

function init() {
    $con=  new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
    $sql = "Select programId, catalogYear from student_programs where studentId=:sid";
    $stmt = $con->prepare($sql);

    $stmt->bindValue(':sid', $_SESSION['studentId']);
    $stmt->execute();

    $student = $stmt->fetch();

    $courses = new CourseDBList($con);
    $records = new RecordsDBList($con, $courses, $_SESSION['studentId']);
    $requirements = new RequirementsDBList($con, $courses, $records, $student['programId'], $student['catalogYear']);

    $ret['connection'] = $con;
    $ret['courseModel'] = $courses;
    $ret['recordModel'] = $records;
    $ret['requirementModel'] = $requirements;

    return $ret;
}