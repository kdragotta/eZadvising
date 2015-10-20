<?php
/**
 * Created by PhpStorm.
 * User: phillip
 * Date: 9/21/15
 * Time: 8:43 AM
 */

namespace obj;


class Requirment implements \JsonSerializable
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $title;
    /**
     * @var string
     */
    private $category;
    /**
     * @var int
     */
    private $programid;
    /**
     * @var int
     */
    private $groupid;
    /**
     * @var int
     */
    private $hours;
    /**
     * @var int
     */
    private $grade;
    /**
     * @var int
     */
    private $catalogYear;
    /**
     * @var null
     */
    private $courseOptions;
    /**
     * @var array
     */
    private $coursesCounting;
    /**
     * @var array
     */
    private $coursesCountingPlanned;


    /**
     * Requirment constructor.
     */
    public function __construct($id=0, $title="", $category="", $programid=0, $groupid=0, $hours=0, $minGrade=0,
                                $catalogYear=0, $courses=[], $coursesCounting=[], $coursesCountingPlanned=[])
    {

        $this->id = $id;
        $this->title = $title;
        $this->category = $category;
        $this->programid = $programid;
        $this->groupid = $groupid;
        $this->hours = $hours;
        $this->grade = $minGrade;
        $this->catalogYear = $catalogYear;
        $this->courseOptions = $courses;
        $this->coursesCounting = $coursesCounting;
        $this->coursesCountingPlanned = $coursesCountingPlanned;
    }

    /**
     * @return array
     */
    public function getCoursesCounting()
    {
        return $this->coursesCounting;
    }

    /**
     * @param array $coursesCounting
     */
    public function setCoursesCounting($coursesCounting)
    {
        $this->coursesCounting = $coursesCounting;
    }

    /**
     * @return array
     */
    public function getCoursesCountingPlanned()
    {
        return $this->coursesCountingPlanned;
    }

    /**
     * @param array $coursesCountingPlanned
     */
    public function setCoursesCountingPlanned($coursesCountingPlanned)
    {
        $this->coursesCountingPlanned = $coursesCountingPlanned;
    }



    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param string $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return int
     */
    public function getProgramid()
    {
        return $this->programid;
    }

    /**
     * @param int $programid
     */
    public function setProgramid($programid)
    {
        $this->programid = $programid;
    }

    /**
     * @return int
     */
    public function getGroupid()
    {
        return $this->groupid;
    }

    /**
     * @param int $groupid
     */
    public function setGroupid($groupid)
    {
        $this->groupid = $groupid;
    }

    /**
     * @return int
     */
    public function getHours()
    {
        return $this->hours;
    }

    /**
     * @param int $hours
     */
    public function setHours($hours)
    {
        $this->hours = $hours;
    }

    /**
     * @return int
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * @param int $grade
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;
    }

    /**
     * @return int
     */
    public function getCatalogYear()
    {
        return $this->catalogYear;
    }

    /**
     * @param int $catalogYear
     */
    public function setCatalogYear($catalogYear)
    {
        $this->catalogYear = $catalogYear;
    }

    /**
     * @return null
     */
    public function getCourseOptions()
    {
        return $this->courseOptions;
    }

    /**
     * @param null $courseOptions
     */
    public function setCourseOptions($courseOptions)
    {
        $this->courseOptions = $courseOptions;
    }


    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        $completedhours = array_reduce($this->coursesCounting, function($total, $crs) {return $total + $crs->getCourse()->getHours();});
        $plannedhours = array_reduce($this->coursesCountingPlanned, function($total, $crs) {return $total + $crs->getCourse()->getHours();});
        return [
            'id' => $this->id,
            'groupName' => $this->title,
            'category' => $this->category,
            'programId' => $this->programid,
            'groupId' => $this->groupid,
            'hours' => $this->hours,
            'hoursCounting' => $completedhours,
            'hoursCountingPlanned' => $plannedhours,
            'somePlanned' => ($completedhours > 0 or $plannedhours > 0),
            'complete' => $completedhours >= $this->hours,
            'completePlanned' => ($completedhours + $plannedhours) >= $this->hours,
            'courseOptions' => $this->courseOptions,
            'coursesCounting' => $this->coursesCounting,
            'coursesCountingPlanned' => $this->coursesCountingPlanned,
            'catalogYear' => $this->catalogYear
        ];
    }
}