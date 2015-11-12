<?php
require_once('CourseObject.php');
/**
 * Created by PhpStorm.
 * User: phillip
 * Date: 9/16/15
 * Time: 12:43 PM
 */
class CourseDBList
{
    private $con;

    /**
     * CourseDBList constructor.
     * @param $conn - A database connection
     */
    public function __construct(PDO $conn)
    {
        $this->con = $conn;
    }

    /**
     * @param $id - the id of the course
     * @return Course The corresponding course object
     */
    public function getCourseById($id)
    {
        $sql = "Select * from courses where id=:id";

        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();

        $result = $stmt->fetch();
        return Course::courseFromRow($result);

    }

    /**
     * @param $gid - The id of the group you want to fetch
     * @return array - An array of course objects
     */
    public function getCoursesByGroup($gid) {
        $sql = "Select courses.* from courses, course_groups where groupId = :gid and courseId = courses.id";

        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(":gid", $gid);
        $stmt->execute();

        $results = $stmt->fetchAll();

        $ret = array();

        foreach ($results as $row) {
            $ret[] = Course::courseFromRow($row);
        }
        return $ret;
    }
}