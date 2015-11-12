<?php
require_once('RecordObject.php');
/**
 * Created by PhpStorm.
 * User: phillip
 * Date: 9/16/15
 * Time: 1:10 PM
 */
class RecordsDBList
{
    private $con;
    private $clist;
    private $studentId;

    /**
     * RecordsDBList constructor.
     */
    public function __construct(PDO $con, $clist, $studentId)
    {
        $this->con = $con;
        $this->clist = $clist;
        $this->studentId = $studentId;
    }

    public function addUpdateRecord(\obj\Record $record) {
        $stmt = null;
        if ($record->getId() == null) {
            $sql = "Insert into course_records (studentId, courseId, grade, year, reqId, type, proposedReqId, hours, semesterCode) VALUES " .
                "(:studentId, :courseId, :grade, :year, :reqId, :type, :proposedReqId, :hours, :semesterCode)";
            $stmt = $this->con->prepare($sql);
        } else {
            $sql = "Update course_records set studentId=:studentId, courseId=:courseId, grade=:grade, year=:year, ".
                "reqId=:reqId, type=:type, proposedReqId=:proposedReqId, hours=:hours, semesterCode=:semesterCode ".
                "Where id=:id";
            $stmt = $this->con->prepare($sql);
            $stmt->bindValue(":id", $record->getId());
        }
        $stmt->bindValue(":studentId", $record->getStudentId());
        $stmt->bindValue(":courseId", $record->getCourse()->getId());
        $stmt->bindValue(":grade", $record->getGrade());
        $stmt->bindValue(":year", $record->getYear());
        $stmt->bindValue(":reqId", $record->getReqId());
        $stmt->bindValue(":type", $record->getType());
        $stmt->bindValue(":proposedReqId", $record->getProposedReqId());
        $stmt->bindValue(":hours", $record->getHours());
        $stmt->bindValue(":semesterCode", $record->getSemester());

        $stmt->execute();
    }

    public function getRecordById($id){
        $sql = "Select * from course_records where id=:id and studentId=:sid";

        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->bindValue(":sid", $this->studentId);
        $stmt->execute();
        $result = $stmt->fetch();

        $course = $this->clist->getCourseById($result['courseId']);
        return new \obj\Record($result['id'], $result['studentId'], $course, $result['grade'], $result['year'], $result['reqId'],
            $result['type'], $result['proposedReqId'], $result['semesterCode']);
    }
    
    public function getRecordsByCourseId($id) {
        $sql = "Select * from course_records where courseId=:id and studentId=:sid";
        
        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->bindValue(":sid", $this->studentId);
        $stmt->execute();
        $results = $stmt->fetchAll();

        $ret = array();
        foreach ($results as $row) {
            $course = $this->clist->getCourseById($row['courseId']);
            $ret[]=new \obj\Record($row['id'], $row['studentId'], $course, $row['grade'], $row['year'], $row['reqId'],
                $row['type'], $row['proposedReqId'], $row['semesterCode']);
        }
        return $ret;

    }

    public function getCompletedRecordsForRequirement(\obj\Requirment $req) {
        // TODO: If grade gets converted to a numeric value update this function to utilize that
        $sql = "Select * from course_records where reqId=:rid";

        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(":rid", $req->getId());
        $stmt->execute();
        $results = $stmt->fetchAll();

        $ret = array();
        foreach ($results as $row) {
            if (\obj\Record::mapLetterGradeToNumber($row['grade']) < $req->getGrade()) {continue;}
            $course = $this->clist->getCourseById($row['courseId']);
            $ret[]=new \obj\Record($row['id'], $row['studentId'], $course, $row['grade'], $row['year'], $row['reqId'],
                $row['type'], $row['proposedReqId'], $row['semesterCode']);
        }
        return $ret;

    }

    public function getPendingRecordsForRequirement(\obj\Requirment $req) {
        $sql = "Select * from course_records where reqId=:rid and grade is NULL";

        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(":rid", $req->getId());
        $stmt->execute();
        $results = $stmt->fetchAll();

        $ret = array();
        foreach ($results as $row) {
            $course = $this->clist->getCourseById($row['courseId']);
            $ret[]=new \obj\Record($row['id'], $row['studentId'], $course, $row['grade'], $row['year'], $row['reqId'],
                $row['type'], $row['proposedReqId'], $row['semesterCode']);
        }
        return $ret;
    }



    public function getAllRecords($planned=true) {
        $sql = "Select * from course_records where studentId=:id";
        if (!$planned) { $sql .= " and grade IS NOT NULL";}

        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(":sid", $this->studentId);
        $stmt->execute();
        $results = $stmt->fetchAll();

        $ret = array();
        foreach ($results as $row) {
            $course = $this->clist->getCourseById($row['courseId']);
            $ret[]=new \obj\Record($row['id'], $row['studentId'], $course, $row['grade'], $row['year'], $row['reqId'],
                $row['type'], $row['proposedReqId'], $row['semesterCode']);
        }
        return $ret;
    }
}