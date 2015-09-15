<?php
session_start();
$_SESSION['username'] = "crystal";
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

    <style>
        html, body {
            font-size: 100%; /*set base rem unit to 10*/
            font-family: 'arial';
            height: 100%;
        }

        /****** Page Layout *******/
        header.top {
            display: flex;
            height: 50px;
        }

        #wrapper {
            display: flex;
            overflow: hidden;
            height: 100vh;
            margin-top: -100px;
            padding-top: 100px;
            position: relative;
            width: 100%;
            backface-visibility: hidden;
            will-change: overflow;
        }

        #left,
        #middle,
        #right {
            overflow: auto;
            height: auto;
            padding: .5rem;
            -webkit-overflow-scrolling: touch;
            -ms-overflow-style: none;
        }

        #left {
            width: 12.5rem;
        }

        #middle {
            flex: 1;
        }

        #right {
            width: 12.5rem;

        }

        /*
        #wrapper {height: 100%;}
        #stillRequiredList {height: 100%;}
        */

        /* left column */
        #left {
            background-color: #f3f2f2;
            width: 18%;
            float: left;
            position: relative;
            margin-right: .25%;
            padding: .5%

        }

        /* column wrapping middle and right columns */
        #col23 {
            background-color: white;
            width: 80%;
            float: right;
            position: relative;
            /*nesting slightly throws off margin and padding percentages-fix later */
            height: 100%;
        }

        #main {
            background-color: #f3f2f2;
            position: relative;
            float: left;
            width: 74%;
            padding: .5%
        }

        #right {
            background-color: #f3f2f2;
            position: relative;
            float: right;
            width: 23%;
            margin-left: .25%;
            padding: .5%;
            height: 100%;

        }

        /* not currently used */
        .center {
            margin-left: auto;
            margin-right: auto;
        }

        /* use a media query later to stop at a minimum size */

        tr.space_under > td {
            padding-bottom: 10px;
        }

        th {
            padding-bottom: 10px;
        }

        /************ ***********/

        /********* Requirement Box Styling ***********/

        /* all course boxes no matter where: includes  */
        /* Requirements boxes anywhere will be in "req_box" class */
        /* Requirements boxes showing on left as either complete,
           partial, planned, or incomplete will be
            in class "required_course"
            with "req_complete", "req_completePlanned", "req_partial", "req_planned",
             or "req_incomplete" */
        /* Note: ALl reqs show on left */

        /* Requirements boxes showing on right as incomplete will
           be in class "req_working" */
        /* Note: Only reqs that are incomplete or partially
           incomplete show on right */
        /* Note: Some boxes on right may be highlighted with class
            "eligible now" based on chosen semester */

        /* Requirements boxes showing in middle will be in class
            "req_on_plan" */
        /* Note: left and middle are complementary, overlap when
            req is partially planned */

        /** course counting or course planned sub boxes **/
        .req_box > select {
            font-size: .8rem;
        }

        span.course_box {
            background-color: #D8D8D8;
            color: black;
            font-size: 0.8rem;
        }

        span.course_counting {
            /*background-color:#e0e0eb;*/
            border-style: solid;
            border-width: 1px;
            border-radius: 5px;
            padding: 2px;
            z-index: 999;
            /* for showing span only as long as contents but not wrapping */
            display: inline-block;
            margin-bottom: 4px;
        }

        span.course_counting_planned {
            /* background-color:orange;*/
            background-color: #FFE0CC;
            border-style: solid;
            border-width: 1px;
            border-radius: 5px;
            padding: 2px;
            z-index: 999;
            /* for showing span only as long as contents but not wrapping */
            display: inline-block;
            margin-bottom: 4px;
        }

        /* Requirements boxes are in divs */
        /********* General styling for all requirements boxes ***********/
        div.req_box {
            background-color: #F8F8F8; /* lightest gray */
            border-style: solid;
            border-width: 1px;
            border-radius: 5px;
            padding: 2px;
            z-index: 999;
            /* for showing span only as long as contents but not wrapping */
            display: block;
            margin-bottom: 4px;
        }

        select {
            margin-left: 5px;
        }

        /*
        span.foundation {
        color: green;
        }
        span.major {
        color: blue;
        }
        span.complete {
        color: orange;
        }

        */

        span.eligible_now {
            background-color: #b3b3bc;
            /* add more to make it stand out and add more gray out to the non eligible */
        }

        /******** on left ******/
        div.req_box header {
            padding: 3px;
            padding-bottom: 1px;
            border-bottom: 1px solid black;
            margin-bottom: 2px;
            font-weight: bold;
            font-size: .9rem;
        }

        div.req_complete {
            color: white;
            /*  background-color: #E6F5EB;  light green */
            /* background-color: #009933;  dark green */
            background-color: darkgray;

        }

        div.req_completePlanned {
            color: white;
            /* background-color: #FFE0CC; light orange */
            background-color: #FF6600; /* dark orange */
        }

        /* some courses planned toward requirement but still not complete */
        div.req_partialPlanned {
            background-color: #FFE0CC; /* light orange */
            color: white;
        }

        /*no longer used? */
        div.req_partial {
            color: black;
            background-color: #F8F8F8;
        }

        /* incomplete and nothing on plan toward it */
        div.req_incomplete {
            color: black;
            background-color: #F8F8F8;
        }

        /* on right if planned -- keep them on right too but hidden */
        div.req_been_planned {
            background-color: green; /*light orange */
            border-color: gray;
            color: lightgray;
            /*display: none;*/

        }

        div.req_working {
            color: white;
            background-color: #008080; /* teal */
        }

        /*******************/

        /*********  Styling for  requirements boxes ON PLAN **********/

        div.req_on_plan {
            background-color: #FF6600;
            margin: 2px;

            /* to fill up row-1 course per row */
            display: block;

        }

        /*******************/

        /*********  Styling for  semester PLAN boxes **********/

        div.semester_block header.semester_name {
            background-color: lightgray;
            padding: 4px;
            font-size: .85rem;
        }

        div.semester_block {
            background-color: white;
            height: 16rem;
            width: 10.25rem;
            border-style: dotted;
            border-width: 1px;
            border-radius: 5px;
            float: left;
            margin-right: 5px;
            margin-bottom: 5px;
            scroll: auto;
        }

        div.semester_block footer {
            text-align: right;
            border-top: 1px solid black;
        }

        /* actual droppable part */
        div.semester_plan {
            height: 13rem;
            width: 10rem;
            scroll: auto;
        }

        /* when hovering over droppable */
        /*
        div.semester_plan.over {
          border: 2px dashed #000;
        }
        */
        .highlight_drop {
            border-style: dashed;
            border-width: 2px;
            border-color: red;
        }

        /* during drag */

        /********** Styling for Requirements Boxes IN MOTION ********/
        /** used? **/
        span.drag_helper {
            background-color: yellow;
        }

        span.ui-draggable-dragging {
            background-color: yellow;
            box-shadow: 5px 5px 2px #888888;
        }

        /********* *********/

        select.single {
            -webkit-appearance: none;
            -moz-appearance: none;
            text-indent: 1px;
            text-overflow: '';
            padding-left: 3px;
            padding-right: 3px;
            padding-top: 1px;
            padding-bottom: 1px;
        }

        span.stats {
            font-size: .7rem;
            display: block;
            text-align: right;
        }

        div.taken, div.planned, div.options {
            font-size: .75rem;
        }

        .req_on_plan div.options {

        }

        .req_on_plan div.taken {
            display: none;
        }

        .req_on_plan div.planned {
            display: none;
        }

        .req_working div.options {

        }

        .req_working div.taken {
            display: none;
        }

        .req_working div.planned {
            display: none;
        }


    </style>
</head>

<body>
<header id="top" class="top">
    <h3> eZAdvising </h3>
</header>
<div id="wrapper">

    <div id="left">
        <table>
            <tr>
                <th>Requirements</th>
            </tr>
        </table>
        <div id="currentState">

        </div>
    </div>

    <div id="col23">
        <div class="target" id="right">

            <table id="required_table">
                <tr>
                    <th>Need to Take</th>
                </tr>
            </table>
            <div id="stillRequiredList">

            </div>
            <div id="eligibleSwitch"> (checkbox) Highlight Courses Eligible (semester drop-down)</div>
            <!-- end stillRequiredList div -->


        </div>

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
        </div>
        <!-- end div main -->

    </div>
    <!-- end div col23 -->


</div>
<!-- end div wrapper -->

<footer>
</footer>
<script src="advising_functions.js"></script>
<script>
    var reqs;
    var semesterList;
    $(initSemesterStart);
    $(initState);

    $(init);

    function showHideSummers() {
        $(".semester_block.minor").toggle();
    }
    function initState() {

//get user id from session or redirect to login (wiht message to come back)
//student meets prereqs based on already loaded classes
//would student meet prereq based on already loaded plus plan
//simple course prereq calculator in javascript - load prereq data for each course and fill with true or


        $.ajax({
            url: "reqsByStudent.php",
            success: function (result) {

                //Build DOM
                var reqs = JSON.parse(result); //reqs is array of requirement objects
                //each req object also has a list of course option objects and list of courses taken objects
                var reqsParentDiv = $('#currentState'); //left
                var missingParentDiv = $('#stillRequiredList'); //right

                //parse reqs
                for (i = 0; i < reqs.length; i++) {

                    var req = reqs[i];
                    var courseOptions = req.courseOptions; //courseOptions is now array of courses
                    var coursesCounting = req.coursesCounting; //coursesCounting is now array of course records
                    var coursesCountingPlanned = req.coursesCountingPlanned;

                    //build classes
                    var classStr = "req_box";
                    if (req.category == 2) classStr += " foundation";
                    else if (req.category == 3) classStr += " major";
                    //add more

                    //is requirement met or metPlanned, or ?


                    //create the MAIN requirement box element
                    //group name is the requirement name (should change?)
                    var newElStr = "<div draggable=true class='" + classStr + "'><header>" + req.groupName + "</header>" + "</div>";
                    var newEl = $(newElStr);


                    //prepare the ids
                    var reqSideId = "r" + req.id;
                    var planId = "p" + req.id;
                    var workingSideId = "w" + req.id;


                    var met = req.complete;
                    var metPlanned = req.completePlanned;
                    var somePlanned = req.somePlanned;

                    //start with classes for the left side, later adjust
                    if (met) {
                        $(newEl).addClass("req_complete");
                    }
                    else if (metPlanned) {
                        $(newEl).addClass("req_completePlanned");
                    }
                    else if (somePlanned) {
                        $(newEl).addClass("req_partialPlanned");
                    }
                    else {
                        $(newEl).addClass("req_incomplete");
                    }

                    //add the data object before cloning
                    $(newEl).data("req", req);


                    //add the select box for options OR select box for what counts including PLANNED

                    var selEl = $("<select></select");
                    var newId = "op" + req.id;
                    $(selEl).attr("id", newId);
                    var optionsCount = 0;
                    //alert("select id: "+ $(selEl).attr("id"));
                    for (j = 0; j < courseOptions.length; j++) {
                        //Is course already taken or planned?
                        var tempId = courseOptions[j].id;
                        var found = false;
                        for (q = 0; q < coursesCounting.length; q++) {
                            if (coursesCounting[q].id == tempId) {
                                found = true;
                                break;
                            }
                        }
                        if (!found)
                            for (q = 0; q < coursesCountingPlanned.length; q++) {
                                if (coursesCountingPlanned[q].id == tempId) {
                                    found = true;
                                    break;
                                }
                            }

                        if (!found) {

                            var opEl = $("<option>" + courseOptions[j].dept + " " + courseOptions[j].num + "</option>");
                            $(opEl).attr("value", courseOptions[j].id);
                            $(selEl).append(opEl);
                            optionsCount++;
                        }
                    }//end for j

                    //var met=req.complete;
                    //console.dir("req "+req.id);
                    //console.dir(selEl);
                    //add class to remove the drop-down arrow if single
                    if (optionsCount <= 1) {
                        $(selEl).addClass("single");
                    }

                    //only add option box if requirement is not complete
                    // var boxEl;
                    // var changeBoxEl;
                    if (!met && !metPlanned && optionsCount > 0) {
                        //put options in a box**
                        var boxStr = "<div class='options'> Options: </div>";
                        // var changeBoxStr="<div class='options'> Change to: </div>"
                        var boxEl = $(boxStr);
                        // var changeBoxEl=$(changeBoxStr);
                        $(boxEl).append(selEl);
                        $(boxEl).attr('id', req.id + "opbox");
                        //  $(changeBoxEl).append(selEl)
                        //$(changeBoxEl).attr('id',"change-"+planId);

                        //move to later
                        $(newEl).append(boxEl);
                    }


                    //split into places here
                    var newElWorking = $(newEl).clone();
                    $(newElWorking).attr('id', workingSideId);
                    $(newElWorking).data("whereami", "working");

                    $(newEl).attr('id', reqSideId);
                    $(newEl).data("whereami", "reqs");


                    //Create the courses taken list within the requirement
                    //  this is only for the left side

                    var takenBoxStr = "<div class='taken'> Completed: </div>";
                    var takenBoxEl = $(takenBoxStr);
                    //$(takenBoxEl).append(selEl);

                    for (j = 0; j < coursesCounting.length; j++) {
                        var theCourse = coursesCounting[j];
                        var theCourseName = theCourse.dept + " " + theCourse.num;
                        var courseTowardsStr = "<span class='course_box course_counting'>" + theCourseName + "</span>";
                        var courseTowardsEl = $(courseTowardsStr);

                        //attach the course data object and req id to each course span element
                        $(courseTowardsEl).data('course', theCourse);
                        $(courseTowardsEl).data('forReq', req.id);

                        $(takenBoxEl).append(courseTowardsEl);
                        //attach the course data and the requirement id req.id
                    }
                    if (coursesCounting.length >= 1) {
                        $(newEl).append(takenBoxEl);
                    }


                    //repeat loop for courses counting (and place courses counting in middle)
                    var plannedBoxStr = "<div class='planned'> Planned: </div>";
                    var plannedBoxEl = $(plannedBoxStr);


                    for (k = 0; k < coursesCountingPlanned.length; k++) {
                        var theCourseP = coursesCountingPlanned[k];
                        var theCourseNameP = theCourseP.dept + " " + theCourseP.num;
                        var courseTowardsStrP = "<span class='course_box course_counting_planned'>" + theCourseNameP + "</span>";
                        var courseTowardsElP = $(courseTowardsStrP);
                        $(courseTowardsElP).data('course', theCourseP);
                        $(courseTowardsElP).data('forReq', req.id);
                        $(courseTowardsElP).attr('id', "sel-" + planId);


                        $(plannedBoxEl).append(courseTowardsElP);

                        //need to go ahead and append the span here before putting on the plan
                        if (k == 0) {
                            $(newEl).append(plannedBoxEl);
                            //don't need to add to right side
                        }

                        //attach the course data and the requirement id req.id

                        //if planned then put on plan for matching semester
                        pSem = theCourseP.semester;
                        pYear = theCourseP.year;
                        pStr = "p" + pYear + pSem;
                        //console.dir("looking for: "+pStr);

                        var newElPlan = $(newEl).clone();

                        var newElPlanWorking = $(newEl).clone();

                        $(newElPlanWorking).data("whereami", "working");
                        //$(newElPlanWorking).addClass("req_working");
                        $(newElPlanWorking).addClass("req_been_planned");
                        $(newElPlanWorking).attr('id', workingSideId);


                        $(newElPlan).data("onSemester", pStr);
                        $(newElPlan).data("whereami", "plan");
                        $(newElPlan).addClass("req_on_plan");
                        $(newElPlan).attr('id', planId);
                        // $(newElPlan).append(changeBoxEl);

                        $(newElPlan).draggable({
                            containment: 'document',
                            cursor: 'move',
                            snap: '.target',
                            helper: 'clone',
                            revert: 'true'
                        });//end draggable

                        $("#" + pStr).append(newElPlan);
                        var currentHours = parseInt($("#" + pStr).data("currentHours"), 10);
                        var add = parseInt(theCourseP.hours);
                        //  currentHours = +currentHours;
                        currentHours = currentHours + add;
                        $("#" + pStr).data("currentHours", currentHours);
                        console.dir(pStr + " hrs: " + currentHours);
                        var targetElSel = "#fstats" + pStr;
                        console.dir($(targetElSel));
                        $(targetElSel).text(currentHours);
                        //*******notworkingyet

                        $(missingParentDiv).append(newElPlanWorking);

                        // $("#"+pStr).append($(newEl).clone().data('whereami',"plan").addClass("req_on_plan"));
                    }


                    $(newEl).append("<span class='stats'> c:" + req.hoursCounting + "/p:" + req.hoursCountingPlanned + "/r:" + req.hours + "</span>");
                    var needed = req.hours - req.hoursCounting - req.hoursCountingPlanned;
                    //console.dir("needed: "+needed);
                    $(newElWorking).append("<span class='stats'> need:" + needed + "</span>");
                    //is it partially complete?
                    //	if(coursesCounting != null && coursesCounting.length>0)
                    // 	console.dir("a course counts");


                    //set up properties on the course box
                    // $(newEl).attr("id","c"+req['id']);
                    $(newEl).attr("groupName", req['groupName']);


                    //add the jquery data object
                    $(newEl).data("req", req);
                    var newPlannedCourses = new Array();
                    $(newEl).data("newplan", coursesCountingPlanned);//each thing in this array should have a dirty flag for changes
                    //use a state field to note when/how it changes as it's dragged


                    //Place course box in appropriate section of page and make draggable

                    //$(newEl).append(boxEl);
                    //$(newElWorking).append(boxEl);
                    if (met) {
                        //place on left and style

                        $(reqsParentDiv).append(newEl);
                        $(newEl).addClass("req_complete");
                        // $(newEl).data("whereami","reqs");
                        // $(newEl).addClass("req_complete");
                    }
                    else if (metPlanned) {

                        $(reqsParentDiv).append(newEl);
                        $(newEl).addClass("req_completePlanned");
                        // $(newEl).data("whereami","reqs");
                    }

                    else {
                        //var copy = $(newEl).clone();
                        //still place on left but also on right
                        $(newEl).addClass("req_incomplete");
                        //$(copy).data("whereami","both");
                        $(reqsParentDiv).append(newEl);


                        $(missingParentDiv).append(newElWorking);
                        $(newElWorking).addClass("req_working");
                        // $(newEl).data("whereami","remaining");
                        $(newElWorking).draggable({
                            containment: 'document',
                            cursor: 'move',
                            snap: '.target',
                            helper: 'clone',
                            revert: 'true'
                        });//end draggable


                        //add code to restyle on right if already planned

                    }//end else

                }//end for each requirement

                //return result;
            }//end success
        });//end ajax

    }//end function

    //TODO add ajax method to

    function getSemesterName(code) {
        //keep in sync with semester_code table
        // but don't need to query database for this for performance reasons
        var name = "";
        switch (code) {
            case 1:
                name = "Fall";
                break;
            case 2:
                name = "Spring";
                break;
            case 3:
                name = "May";
                break;
            case 4:
                name = "Summer 1";
                break;
            case 5:
                name = "Summer II";
                break;
            case 6:
                name = "Summer 8-week";
                break;
            default:
                name = "N/A";
        }
        return name;
    }


    function initSemesterStart() {

//get date of first planned for student or current semester and show whichever
// is earlier
        var now = new Date();
        var nowYear = now.getFullYear();
//console.dir("year:"+nowYear);
        var nowMonth = now.getMonth();
        var startSem;
        var startYear;

        if (nowMonth >= 1 && nowMonth <= 5) //spring
        {
            startSem = 2;
            startYear = nowYear;
        }
        else if (nowMonth >= 6 && nowMonth <= 12) //fall
        {
            startSem = 1;
            startYear = nowYear;
        }
        else {
            startSem = 2;
            startYear = nowYear;
        }

//var fallStart = new Date("08/15/2015");

        var year = startYear;
        var sem = startSem;

        for (i = 0; i < 18; i++) {

            var newElStr = '<div class="semester_block"></div>';
            var newEl = $(newElStr);
            if (sem == 1 || sem == 2) {
                $(newEl).addClass("major");
            }
            else {
                $(newEl).addClass("minor");
            }
            var newElId = "s" + year + sem;

            $(newEl).attr('id', newElId);
            console.dir($(newEl).attr('id'));
            var headerStr = getSemesterName(sem) + " " + year;
            $(newEl).append("<header class='semester_name'>" + headerStr + "</header>");

            var innerDivStr = '<div class="target semester_plan"></div>';
            var innerDiv = $(innerDivStr);
            var innerDivId = "p" + year + sem;
            $(innerDiv).attr('id', innerDivId);
            $(innerDiv).data("currentHours", 0);
            $(newEl).append(innerDiv);
            $(newEl).append("<footer class='stats' id='fstats" + innerDivId + "'>0</footer>");


            $('#main').append(newEl);
            var nextSemArray = incrementSemester(sem, year, 1);
            sem = nextSemArray[1];
            year = nextSemArray[0];

        }//end for

    }//end function


    function incrementSemester(sem, year, scale) {
        //add code for scale later (increment to next major or next minor)
        var nextSemester = 0;
        var nextYear = 0;
        switch (sem) {
            case 1:
                nextSemester = 2;
                nextYear = ++year;
                break;
            case 6:
                nextSemester = 1;
                nextYear = year;
                break;
            default:
                nextSemester = ++sem;
                nextYear = year;

        }
        var next = [nextYear, nextSemester];
        return next;

    }


    //attach the correct semester to semester divs, allow more to be added with next semesters


    function init() {
        $('.req_box').draggable({
            containment: 'document',
            cursor: 'move',
            snap: '.target',
            helper: 'clone',
            revert: true
        });

        $('.semester_plan').droppable({
            drop: handleDropEventOnPlan,
            hoverClass: "highlight_drop"
        });
        $('#stillRequiredList').droppable({
            drop: handleDropEventOnRequired,
            hoverClass: "highlight_drop"
        });

        // $( ".req_box" ).draggable( "option", "helper", 'clone' );
        // $( ".req_box" ).on( "dragstop", function( event, ui ) {} ); //dragstart, drag, dragstop, dragcrete


    }
    function handleDropEventOnRequired(event, ui) {
//if($("#" + name).length == 0) {
        //it doesn't exist
//}
        var sourceId = ui.draggable.attr('id');
        if (sourceId.substr(0, 1) == "w") {

        }


        var newId = "c" + sourceId.substr(1);
        var sel = "#stillRequiredList #" + newId;
        console.log("sel: " + sel);
        if ($(sel).length != 0) {
            console.log("in req if");
            $(sel).removeClass("req_been_planned");
            $(sel).draggable('enable');
            $(sel).attr('draggable', 'true');
            $(sel).draggable('option', 'revert', true);
            $(ui.draggable).remove();
        }


    }
    //TODO add function for changing drop-down selection on plan
    function handleDropEventOnPlan(event, ui) {

        //if prereqs met and course offered, let it drop
        //update planned course record
        if (true) {

            var original;
            var sourceId = ui.draggable.attr('id');
            console.log("source is " + sourceId.substr(0, 1));
            if (sourceId.substr(0, 1) == "w") //original move coming from the working side-insert
            {
                console.log("in if");
                var oldId = sourceId;
                var newId = "p" + sourceId.substr(1);
                $(ui.draggable).clone().attr('id', newId).addClass('req_on_plan').removeClass('req_working').draggable({
                    containment: 'document',
                    cursor: 'move',
                    snap: '.target',
                    helper: 'original',
                    revert: true
                }).appendTo($(this));

                //update everything, do insert or update
                //get the selected course if necessary
                //create record
                //

                //style the copy of requirement still left on working side
                ui.draggable.addClass('req_been_planned');
                ui.draggable.draggable('disable');
                ui.draggable.attr('draggable', 'false');
                ui.draggable.draggable('option', 'revert', false);
            }//end if original move
            else if (sourceId.substr(0, 1) == "p") //move from one semester to another
            {

                $(ui.draggable).appendTo($(this)).css({position: 'relative', top: 0, left: 0});
                ui.draggable.draggable('option', 'revert', true);

                console.log("in else");
            }//end else not original move
        } //end if true
    }//end function


    /********* experimental for automating movement **********/
    function trigger_drop() {
        var draggable = $("div.semester_plan div.req_on_plan").draggable();
        var y = $("div.semester_plan div.req_on_plan").length;
        console.dir("y: " + y);
//  console.log("clicked:"+draggable);
        var droppable = $('#stillRequiredList').droppable({
            drop: handleDropEventOnRequired,
            hoverClass: "highlight_drop"
        });
        var x = $('#stillRequiredList').length;
        console.dir(droppable);


        var droppableOffset = droppable.offset();
        console.dir(droppableOffset);
//console.dir("droppableOffset:"+droppableOffset);
        var draggableOffset = draggable.offset();
        console.dir(draggableOffset);
        var dx = droppableOffset.left - draggableOffset.left;
        console.dir(dx);
        var dy = droppableOffset.top - draggableOffset.top;


        draggable.simulate("drag", {
            dx: dx,
            dy: dy
        });
    }

    function unplan() {
        console.log("clicked unplan");
        trigger_drop();
    }
</script>

<script>

</script>
