<?php
require_once('Requirment.php');
/**
 * Created by PhpStorm.
 * User: phillip
 * Date: 9/16/15
 * Time: 1:09 PM
 */
class RequirementsDBList
{
    /**
     * @var PDO
     */
    private $con;
    private $clist;
    private $reclist;
    private $programId;
    private $year;

    /**
     * RequirementsDBList constructor.
     */
    public function __construct(PDO $con, $clist, $reclist, $programId, $year)
    {
        $this->con = $con;
        $this->clist = $clist;
        $this->reclist = $reclist;
        $this->programId = $programId;
        $this->year = $year;
    }

    public function getRequirements() {
        $sql = "Select * from program_requirements where programId=:id and catalogYear=:year";

        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(":id", $this->programId);
        $stmt->bindValue(":year", $this->year);
        $stmt->execute();
        $results = $stmt->fetchAll();

        $ret = [];

        foreach ($results as $row) {
            $req = new \obj\Requirment($row['id'], $row['title'], $row['category'], $row['programId'], $row['groupId'],
                $row['numCreditHours'], $row['minGrade'], $row['catalogYear']);

            $req->setCourseOptions($this->getCoursesForRequirement($row['groupId']));
            $req->setCoursesCounting($this->reclist->getCompletedRecordsForRequirement($req));
            $req->setCoursesCountingPlanned($this->reclist->getPendingRecordsForRequirement($req));
            $ret[]=$req;
        }

       return $ret;

    }

    private function getCoursesForRequirement($groupId) {
        $sql = "Select courseId from course_groups where groupId=:id";

        $stmt = $this->con->prepare($sql);
        $stmt->bindValue(":id", $groupId);
        $stmt->execute();
        $results = $stmt->fetchAll();

        $ret = array();

        foreach ($results as $row) {
            $ret[] = $this->clist->getCourseById($row[0]);
        }
        return $ret;
    }
}