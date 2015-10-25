<?php
#
# CSCI 490 - Software Engineering
# Developer: Cameron Collins
# Team: No Clue
#

require_once('ExpressionParser.php');
class Course {

    public $id;
    public $title;
    public $dept;
    public $num;
    public $difficulty;
    public $hours;
    public $description;
    public $prereq_expr;
    public $semestersOffered;
    private $prereq_tree;


    function __construct($id=0, $name="", $department="", $number=0, $hours=0, $description="",
                         $prereqs="", $semestersOffered="", $difficulty=0, $computeTree=false) {
        $this->id = $id;
        $this->title = $name;
        $this->dept = $department;
        $this->num = $number;
        $this->difficulty = $difficulty;
        $this->hours = $hours;
        $this->description = $description;
        $this->prereq_expr = $prereqs;
        $this->semestersOffered = Course::parseSemestersOffered($semestersOffered);
        if ($computeTree) {
            $this->parsePrereqExpression();
        }
    }

    /**
     * Parses a prereq expression into an array of arrays of integers
     */
    public function parsePrereqExpression(){
        $this->prereq_tree = ParseExpression($this->prereq_expr);
    }

    /**
     * Converts a string of Y's and N's to an array of ints for the semesters offered
     *
     * @param $expr : A string of Y's and N's for semesters
     * @return array
     */
    public static function parseSemestersOffered($expr) {
        $chars = str_split($expr);
        $ret = array();
        for ($i=0; $i<count($chars); $i++ ) {
            if($chars[$i] == "Y") {$ret[] = $i;}
        }
        return $ret;
    }

    /**
     * Takes an indexable object (ex. a database row) and calls the course constructor with it using the idxspec
     * as a map. Before calling the constructor, a conversion for the semester and prereq arguments will be attempted using
     * parseSemestersOffered and parsePrereqExpression. The position in the array corresponds to the argument
     * for the constructor, and the value at that position is the index of $row to use as the value; symbolically:
     *      $args(i) = $row($idxspec(i))
     *
     * If $idxspec(i) is <0, null will be passed to the constructor at that position
     *
     * @param $row : Any indexable variable
     * @param array $idxspec : An array that maps the index in the row to the constructor parameter
     * @param bool $convert : If true attempt to parse prereqs and semesters offered
     * @return Course
     */
    public static function courseFromRow($row, $idxspec=[0, 5, 1, 2, 4, 6, 3, 7, -1], $convert=false) {
        $args = array();
        foreach ($idxspec as $col) {
            if ($col<0){
                $args[] = null;
            } else {
                $args[] = $row[$col];
            }

        }
        $args[]=$convert;

        return new Course(...$args);
    }


    #getters
    function getTitle() {
        return $this->title;
    }

    function getDept() {
        return $this->dept;
    }

    function getNum() {
        return $this->num;
    }

    function getDifficulty() {
        return $this->difficulty;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getHours()
    {
        return $this->hours;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getPrereqExpr()
    {
        return $this->prereq_expr;
    }

    /**
     * @return string
     */
    public function getSemestersOffered()
    {
        return $this->semestersOffered;
    }

    /**
     * @return mixed
     */
    public function getPrereqTree()
    {
        return $this->prereq_tree;
    }





    #setters
    function setTitle($title) {
        if(strlen($title) > 3)
             $this->title = $title;
    }

    function setDept($dept) {
        if(strlen($dept))
            $this->dept = $dept;
    }

    function setNum($num) {
        $this->num = $num;
    }

    function setDifficulty($difficulty) {
        $this->difficulty = $difficulty;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param int $hours
     */
    public function setHours($hours)
    {
        $this->hours = $hours;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @param string $prereq_expr
     */
    public function setPrereqExpr($prereq_expr)
    {
        $this->prereq_expr = $prereq_expr;
    }

    /**
     * @param string $semestersOffered
     */
    public function setSemestersOffered($semestersOffered)
    {
        $this->semestersOffered = $semestersOffered;
    }



    #comparison
    function compareTo(Course $other) {
        if ($this->getDifficulty() > $other->getDifficulty())
            return 1;
        elseif ($this->getDifficulty() < $other->getDifficulty())
            return -1;
        else
            return 0;
    }

    #String output 
    function toString() {
        $toString = "";
        $toString .= "Name: " . $this->title . "\n";
        $toString .= "Title: " . $this->dept . " " . $this->num . "\n";
        $toString .= "Difficulty: " . $this->difficulty . "\n";
        return $toString;
    }
}

?>
