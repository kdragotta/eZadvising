<?php
require_once 'config.php';
session_start();
if(!isset($_SESSION['first']))
{
    header("Location: login.php");
}
$u ="";
$t ="";
foreach($_COOKIE as $key => $token)
{
    if($key == $_SESSION['username'] && $token == $_SESSION['token'])
    {
        $u = $key;
        $t = $token;
    }
    else
    {

    }

}
if($u == "" || $t == "")
{
    echo "U: ".$u. "  T: ". $t;
    header("Location: logout.php");
}
else
{

//$_SESSION['username'] = "crystal";
//$_SESSION['first'] = $_REQUEST['first'];
//$_SESSION['studentId'] = 1;
//$_SESSION['token'] = "ABC";
/** login, registration and enter records, import records, auto-plan, print option, email option **/
/** scrape for course availability **/
?>
<!DOCTYPE html>
<html>
<head>
    <title> eZAdvising </title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

    <script src="jquery-simulate.js"></script>

    <link rel="stylesheet" href="main.css">
</head>

<body>
<div id="top" class="top">
    <h3> eZAdvising </h3>

</div>
<div id="left" class="left">
    <?php
        echo 'Welcome back, ' . $_SESSION['first'];
    ?>
</div>
<div id="wrapper">

    <div id="left">
        <table>
            <tr>
                <button type="button" onclick="window.location.href='logout.php'">Log Out</button>
            </tr>
            <tr>
                <th>Requirements</th>
            </tr>
<!--            <tr>-->
<!--                <th><button type="button" onclick="window.location.href='eligibleNow.php'">Eligible Now</button></th>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <th><button type="button" onclick="window.location.href='logout.php'">Log Out</button></th>-->
<!--            </tr>-->
        </table>
        <div id="currentState">

        </div>
    </div>

    <!-- newlayout <div id="col23"> -->

    <div id="main">
        <table>
            <tr>
                <th>Plan</th>
            </tr>

            <tr></tr>

        </table>


        <table>

            <tr>
                <td>
                    <button data-show="on" onclick="showHideSummers()"> Show/Hide Summers</button>
                </td>
            </tr>
            <!-- <tr> <td><button onclick="unplan()" > Save Plan </button> </td> </tr>
             <tr> <td><button onclick="unplan()" > Revert to Saved Plan </button></td></tr>
             -->
        </table>
        <div id="thePlan"></div>

    </div>
    <!-- end div main -->

    <!-- newlayout </div> --><!-- end div col23 -->

    <div class="target" id="right">

        <table id="required_table">
            <tr>
                <th>Need to Take</th>
            </tr>
        </table>
        <div id="eligibleSwitch">
            <input type="checkbox" id="semCheckBox"/>
            <span>Highlight Courses Eligible </span>
            <select id="semList"></select>
        </div>
        <div id="stillRequiredList">

        </div>

        <?php

        $servername = "localhost";
        $username = "advising";
        $password = "adv123";
        $dbname = "ezadvising";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $t= $_POST['addc'];
        parse_str($t, $parseOutput);

        $department = $parseOutput['dept'];
        $level = $parseOutput['num'];

        $sql2 = "SELECT * FROM nonrequired_courses WHERE DEPT='$department' AND NUM='$level'";
        $result2 = $conn->query($sql2);

        /*if($result2->num_rows >= 0){
            echo "alert(Already registered for the class)";
        }*/

        if ($t != NULL && $result2->num_rows == 0)
        {
            /*echo "<div draggable='true' class = 'drag' ondragstart='event.dataTransfer.setData('text/plain', 'This text may be dragged')'>";
            echo "<div class = underline>";
            echo $t;
            echo "</div>";
            echo "</div>";*/

            $sql = "INSERT INTO `ezadvising`.`nonrequired_courses` (`id`, `dept`, `num`, `programId`, `prereqs`, `defaultCreditHours`, `title`, `description`, `semestersOffered`) VALUES ";
            $sql .= "(NULL, ";
            $sql .= "'" . $parseOutput['dept'] . "', ";
            $sql .= "'" . $parseOutput['num'] . "', ";
            $sql .= "'" . $parseOutput['pid'] . "', ";
            $sql .= "'" . $parseOutput['prereqs'] . "', ";
            $sql .= "'" . $parseOutput['cred'] . "', ";
            $sql .= "'" . $parseOutput['title'] . "', ";
            $sql .= "'" . $parseOutput['descript'] . "', ";
            $sql .= "'" . $parseOutput['semest'] . "')";

            $result = $conn->query($sql);

        }

        $sql_pull = "SELECT * FROM `nonrequired_courses`";
        $compare_with_course = "SELECT * FROM `courses`";
        $result_pull = $conn->query($sql_pull);
        $compare_with = $conn->query($compare_with_groups);


        $tracker = 0;

        if($result_pull->num_rows > 0)
        {
            while($non_required = $result_pull->fetch_assoc())
            {

                echo "<div draggable='true' class = 'drag' ondragstart='event.dataTransfer.setData('text/plain', 'This text may be dragged')'>";
                echo "<div class = underline>";
                $concat_dept_num = $non_required["dept"] . " " . $non_required["num"];
                echo $concat_dept_num;
                echo "</div>";


               /* while($recs = $compare_with->fetch_assoc())
                {

                    if($concat_dept_num = $recs["name"] and $tracker != 99)
                    {

                        echo "&#9760";
                        $tracker = 99;
                    }
                }*/


                echo "</div>";

            }
        }

        echo "<br/>";
        ?>

        <table id = "nonRequredCourse">
            <th>Add Nonrequired Course</th>
        </table>

        <form action = "addCourse.php" value = "rbselect" method = "post">
            <select id = "nondept" value = "rbselect" name="nondept" onchange="enableSubmit()">
                <option selected value = "0">Select Dept</option>
                <option value = "CSCI">Computer Science</option>
                <option value = "ENGL">English</option>
                <option value = "JOUR">Journalism</option>
                <option value = "RSM">RSM</option>
                <option value = "POLI">Politics</option>
                <option value = "THEA">Theatre</option>
                <option value = "MUS">Music</option>
                </option>
            </select>
            <select id = "year" value = "yearSelect" name = "year" onchange="enableSubmit()">
                <option selected value = "%">Select Year</option>
                <option value = '1%'>100</option>;
                <option value = '2%'>200</option>;
                <option value = '3%'>300</option>;
                <option value = '4%'>400</option>;
            </select>

            <br/>
            <input type="submit" id = "deptSubmit" class = "rbsubmit" value="Search"/>
        </form>



        <!-- end stillRequiredList div -->

        <!-- Feature 14 -->

    </div>
    <!-- end div right -->


</div>
<!-- end div wrapper -->

<footer>
</footer>
<div id="temp_hidden" class="temp_hidden"></div>
<script src="advising_functions.js"></script>
<script>
    //Disables the submit button by default
    document.getElementById("deptSubmit").setAttribute('disabled', true)
    //Enables the submit button after a course is selected
    //Currently disabled whatever the course thats selected even if its not the "select dept" option will only enable
    //after another option is selected. It has to do the with the onChange event and the option not automatically set
    //to the default every time.d
    function enableSubmit() {
        if (this.value != 0) {
            document.getElementById("deptSubmit").removeAttribute('disabled');
        }
        else{
            document.getElementById("deptSubmit").setAttribute('disable', true);
        }
    }
</script>

<script>

</script>
<?php
}