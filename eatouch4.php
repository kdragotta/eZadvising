<?php
session_start();

//$_SESSION['username'] = "crystal";
//$_SESSION['first'] = $first;
$_SESSION['studentId'] = 1;
$_SESSION['token'] = "ABC";
/** login, registration and enter records, import records, auto-plan, print option, email option **/
/** scrape for course availability **/
?>
<!DOCTYPE html>
<html>
<head>
    <title> eZAdvising </title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
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
                <th>Requirements</th>
            </tr>
            <tr>
                <th><button type="button" onclick="window.location.href='eligibleNow.php'">Eligible Now</button></th>
            </tr>
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

        <table id = "nonRequredCourse">
            <th>Add Nonrequired Course</th>
        </table>

        <form action = "addCourse.php" value = "rbselect" method = "post">
            <select id = "nondept" value = "rbselect" name="nondept">
                <option selected = "Select Dept." value = "0">Select Dept</option>
                <option value = "CSCI">Computer Science</option>
                <option value = "ENGL">English</option>
                <option value="JOUR">Journalism</option>
                <option value="RSM">RSM</option>
                <option value="POLI">Politics</option>
                <option value="THEA">Theatre</option>
                <option value="MUS">Music</option>
                </option>
            </select>

            <br/>
            <input type="submit" class = "rbsubmit" value="Search"/>
        </form>

        <?php
            $addCourseArray = array();
            $arrayCount = 0;
            $t= $_POST['addc'];

            $addCourseArray[$arrayCount] = $t;

            echo "<div draggable='true' class = 'drag' ondragstart='event.dataTransfer.setData('text/plain', 'This text may be dragged')'>";
            echo $addCourseArray[$arrayCount];
            echo "</div>";

            $arrayCount++;
        ?>

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

</script>

<script>

</script>
