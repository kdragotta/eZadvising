planMap = {};

$(initState());

function ClassBox(req, classStr, newEl) {
    this.req = req;
    this.classStr = classStr;
    this.newEl = newEl;
    this.boxId;
    this.reqSideId;
    this.planId;
    this.workingSideId;
};

ClassBox.prototype.createBox = function () {
    //prepare the ids
    this.reqSideId = "r" + this.req.id;
    this.planId = "p" + this.req.id;
    this.workingSideId = "w" + this.req.id;

    //add the data object before cloning (I don't think clone method copies data object too?)
    //Convert into jQuery object
    // not sure why but some things don't work without doing this first
    $(this.newEl).data("req", this.req);
};

ClassBox.prototype.addCourseOptions = function () {

// /***************** OPTIONS BOX *********************/
    //add the select box for options OR select box for what counts including PLANNED
    var selEl = $("<select></select>");
    var newId = "op" + this.req.id;
    $(selEl).attr("id", newId); //each select field has id "opX" where X is req.id

    var optionsCount = 0;
    for (j = 0; j < this.req.courseOptions.length; j++) {

        //Is course already taken or planned? If so, remove from options list or add a NOTE and style
        var tempId = this.req.courseOptions[j].id;
        var found = false;
        for (q = 0; q < this.req.coursesCounting.length; q++) {
            if (this.req.coursesCounting[q].id == tempId) {
                found = true;
                break;
            }
        }
        if (!found)
            for (q = 0; q < this.req.coursesCountingPlanned.length; q++) {
                if (this.req.coursesCountingPlanned[q].id == tempId) {
                    found = true;
                    break;
                }
            }

        if (!found) {

            var opEl = $("<option>" + this.req.courseOptions[j].dept + " " + this.req.courseOptions[j].num + "</option>");
            $(opEl).attr("value", this.req.courseOptions[j].id);
            $(opEl).data("hours", this.req.courseOptions[j].hours);
            $(opEl).addClass("option_available");
            $(opEl).attr('id', "opt" + this.req.courseOptions[j].id);
            $(selEl).append(opEl);
            optionsCount++;
        }
        if (found) {
            var opEl = $("<option>" + this.req.courseOptions[j].dept + " " + this.req.courseOptions[j].num + "- USED </option>");
            $(opEl).attr("value", this.req.courseOptions[j].id);
            $(opEl).data("hours", this.req.courseOptions[j].hours);
            $(opEl).addClass("option_used");
            $(opEl).attr('id', "opt" + this.req.courseOptions[j].id);
            $(selEl).append(opEl);
            optionsCount++;
        }
    }//end for j - creating options drop down
    //add class to remove the drop-down arrow if single
    if (optionsCount <= 1) {
        $(selEl).addClass("single");
    }

    //put the options in a div
    var boxStr = "<div class='options'> Options: </div>";
    var boxEl = $(boxStr);
    $(boxEl).append(selEl);
    $(boxEl).attr('id', this.req.id + "opbox");

    $(this.newEl).append(boxEl);
};

ClassBox.prototype.addCompletedCourses = function () {
    /***************** COMPLETED COURSES BOX *********************/

    //Create the courses taken list within the requirement
    //  Use CSS to only show on left side

    var takenBoxStr = "<div class='taken'> Completed: </div>";
    var takenBoxEl = $(takenBoxStr);

    for (j = 0; j < this.req.coursesCounting.length; j++) {
        var theCourse = this.req.coursesCounting[j];
        var theCourseName = theCourse.dept + " " + theCourse.num;
        var courseTowardsStr = "<span class='course_box course_counting'>" + theCourseName + "</span>";
        var courseTowardsEl = $(courseTowardsStr);

        //attach the course data object and req id to each course span element
        $(courseTowardsEl).data('course', theCourse);
        $(courseTowardsEl).data('forReq', req.id);

        $(takenBoxEl).append(courseTowardsEl);
        //attach the course data and the requirement id req.id

        //first time through, add the containing div with the first course
        if (j == 0) {
            $(this.newEl).append(takenBoxEl);
        }
        //after, just add more courses to the containing div
    }

    $(this.newEl).append(takenBoxEl);
}

ClassBox.prototype.addPlannedCourses = function () {
    //repeat loop for courses counting (and place courses counting in middle)
    var plannedBoxStr = "<div class='planned'> Planned: </div>";
    var plannedBoxEl = $(plannedBoxStr);
    for (k = 0; k < this.req.coursesCountingPlanned.length; k++) {
        var theCourseP = this.req.coursesCountingPlanned[k];
        var theCourseNameP = theCourseP.dept + " " + theCourseP.num;
        var courseTowardsStrP = "<span class='course_box course_counting_planned'>" + theCourseNameP + "</span>";
        var courseTowardsElP = $(courseTowardsStrP);
        $(courseTowardsElP).data('course', theCourseP);
        $(courseTowardsElP).data('forReq', this.req.id);
        $(courseTowardsElP).attr('id', "planned-" + this.planId);

        $(plannedBoxEl).append(courseTowardsElP);

        //need to go ahead and append the span here before putting on the plan
        if (k == 0) {
            $(this.newEl).append(plannedBoxEl);
        }
        //if planned then put on plan for matching semester
        var pSem = theCourseP.semester;
        var pYear = theCourseP.year;
        var plan = theCourseP.plan;
        this.boxId = "plan" + plan + pYear + pSem;

        var theCoursePId = theCourseP.id;
        //set the drop-down box to be the right course
        $("#" + this.boxId + " #op" + this.req.id).val(theCoursePId);

        //update hours for each semester
        var currentHours = parseInt($("#" + this.boxId).data("currentHours"), 10);
        var add = parseInt(theCourseP.hours);
        currentHours = currentHours + add;
        $("#" + this.boxId).data("currentHours", currentHours);
        var targetElSel = "#fstats" + this.boxId;
        $(targetElSel).text(currentHours);
    }
}

ClassBox.prototype.addToCurrentState = function (index) {
    var newEl = $(this.newEl).clone(true); //for the right side

    //set up stats data
    $(newEl).data("hoursCounting", this.req.hoursCounting);
    $(newEl).data("hoursCountingPlanned", this.req.hoursCountingPlanned);
    $(newEl).data("hours", this.req.hours);

    var needed = this.req.hours - this.req.hoursCounting - this.req.hoursCountingPlanned;
    $(newEl).data("stillNeeded", needed);

    $(newEl).attr('id', this.reqSideId + index);
    $(newEl).data("whereami", "reqs");

    //Add stats to left side box
    $(newEl).append("<span class='stats'> c:" + this.req.hoursCounting + "/p:" +
        this.req.hoursCountingPlanned + "/r:" + this.req.hours + "</span>");

    // groupName not currently used
    $(newEl).attr("groupName", this.req['groupName']);

    $(newEl).data("req", this.req);

    $(newEl).addClass("req_incomplete");


    $("#currentState" + index).append(newEl);
}

ClassBox.prototype.addToRequiredList = function (index) {
    var newEl = $(this.newEl).clone(true); //for the right side
    var needed = this.req.hours - this.req.hoursCounting - this.req.hoursCountingPlanned;

    //split into left only and right clone here
    $(newEl).attr('id', this.workingSideId + index);
    $(newEl).data("whereami", "working");

    //Add stats to right side box
    $(newEl).append("<span class='stats'> need:" + needed + "</span>");

    //add the jquery data object - TODO check if already added
    $(newEl).data("req", this.req);

    //start with classes for the left side
    if (this.req.complete) {
        $(newEl).addClass("req_complete");
    }
    else if (this.req.completePlanned) {
        $(newEl).addClass("req_completePlanned");
    }
    else if (this.req.somePlanned) {
        $(newEl).addClass("req_partialPlanned");
    }
    else {
        $(newEl).addClass("req_incomplete");
    }

    $(newEl).addClass("req_working");

    $(newEl).draggable({
        containment: 'document',
        cursor: 'move',
        snap: '.target',
        helper: function (event) {
            //return $('<span style="white-space:nowrap;"/>').text($(this).text() + " helper");

            var theClone = $(this).clone(true);
            var baseId = $(theClone).attr('id');
            var selectedValue = $("#" + baseId + " #op" + baseId.substr(1)).val();
            console.dir("dragging: " + selectedValue);
            $(theClone).attr('id', "dragging" + baseId);
            //var test = $(theClone).attr('id'); console.dir("teset: "+test);
            //add the clone to a hidden area of the dom so we can select it
            $(theClone).addClass("temp_hidden").appendTo($("#temp_hidden"));

            //console.dir($("#dragging"+baseId+" #op"+baseId.substr(1)));
            $("#dragging" + baseId + " #op" + baseId.substr(1)).val(selectedValue);
            //$("#temp_hidden").remove($(theClone));
            return $(theClone);
        },

        revert: 'true'
    });//end draggable

    $("#stillRequiredList" + index).append(newEl);
}

ClassBox.prototype.addCourseToPlan = function () {
        var newElPlan = $(this.newEl).clone(true); //to put on plan

        $(newElPlan).data("semesterCode", this.req.semesterCode);
        $(newElPlan).data("year", this.req.year);
        $(newElPlan).data("plan", this.req.plan);
        $(newElPlan).data("whereami", "plan");
        $(newElPlan).addClass("req_on_plan");
        $(newElPlan).attr('id', 'p' + this.req.plan);
        $(newElPlan).val(this.req.id);


    $(newElPlan).draggable({
            containment: 'document',
            cursor: 'move',
            snap: '.target',
            helper: 'clone',
            revert: 'true'
        });//end draggable

        //boxId is a workaround should use this.boxId instead of making it
        var boxId = "plan" + this.req.plan + this.req.year + this.req.semesterCode;
        $("#" + boxId).append(newElPlan);
}


function processReqUpdate(req) {

    var count = $('.planpill').length;

    var classStr = "req_box";
    var newElStr = "<div draggable=true class='" + classStr + "'><header>" + req.groupName + "</header>" + "</div>";
    var newEl = $(newElStr);


    if(req.type != "onplan") {

        var classBox = new ClassBox(req, classStr, newEl);
        classBox.createBox();
        classBox.addCourseOptions();
        classBox.addCompletedCourses();
        classBox.addPlannedCourses();

        for (var i = 0; i < count; i++) {
            classBox.addToCurrentState(i);
            classBox.addToRequiredList(i);
        }

        classBox.addCourseToPlan();
    }

    if(req.type == "onplan") {
        /*
        //todo dynamically make it with tabbing
        if(planMap[req.plan] != true) {
            planMap[req.plan] = true;
            $(initSemesterStart(req.plan));
            $(init(req.plan));
        }
        */

        $("#r" + req.id + req.plan).addClass("req_completePlanned");
        $("#r" + req.id + req.plan).removeClass("req_incomplete");
        $("#w" + req.id + req.plan).remove();

        var classBox = new ClassBox(req, classStr, newEl);
        classBox.createBox();
        classBox.addCourseOptions();
        classBox.addCompletedCourses();
        classBox.addPlannedCourses();
        classBox.addCourseToPlan();
    }

}

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

function showHideSummers() {
    $(".semester_block.minor").toggle();
}


function initState() {

    for(var i = 0; i < 5; i++)
    {
        $(initSemesterStart(i));
        $(init(i));
    }

    $.ajax({
        url: "index.php",
        method: 'POST',
        data: {
            token: 'ABC',
            studentId: 1,
            programId: 1,
            year: 2014
        },
        success: function (result) {

            //Build DOM
            var reqs = JSON.parse(result); //reqs is array of requirement objects
            //each req object also has a list of course option objects, a list of
            // courses taken objects, and a list of courses planned objects
            //  see advising.php for description of these objects

            //parse reqs
            for (var i = 0; i < reqs.length; i++) {

                var req = reqs[i];
                console.log(req);
                processReqUpdate(req);

            }
            //return result;
        }//end success
    });//end ajax

}//end function


function initSemesterStart(index) {

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

    for (i = 0; i < 12; i++) {

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
        var innerDivId = 'plan' + index + year + sem;
        $(innerDiv).attr('id', innerDivId);
        $(innerDiv).data("currentHours", 0);
        $(newEl).append(innerDiv);
        $(newEl).append("<footer class='stats' id='fstats" + innerDivId + "'>0</footer>");


        $('#thePlan' + index).append(newEl);

        //add the semester to the semList drop-down on right
        var optId = "d" + year + sem;
        var semOptStr = "<option value='" + optId + "' id='" + optId + "' >" + headerStr + "</option>";
        var semOptEl = $(semOptStr);
        $(semOptEl).appendTo("#semList");

        var nextSemArray = incrementSemester(sem, year, 1);
        sem = nextSemArray[1];
        year = nextSemArray[0];

    }//end for

}//end function

function highlightEligible() {
//TODO if single course requirement - hightlight whole box with yellow or green
//If multiple options, highlight lighter box and highlight options
}


function init(index) {
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
    $('#stillRequiredList' + index).droppable({
        drop: handleDropEventOnWorking,
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
    var sel = "#stillRequiredList" + " #" + newId;
    //console.log("sel: " + sel);
    if ($(sel).length != 0) {
        console.log("in req if");
        $(sel).removeClass("req_been_planned");
        $(sel).draggable('enable');
        $(sel).attr('draggable', 'true');
        $(sel).draggable('option', 'revert', true);
        $(ui.draggable).remove();
    }


}


//TODO - redo this whole method to undo the plan
//redo this one, on drop on plan adjust semester hours, get from semester/year TODO
function handleDropEventOnWorking(event, ui) {

    var targId = $(this).attr('id');

    //if prereqs met and course offered, let it drop
    //update planned course record
    //if (true) {

    var original;
    var sourceId = ui.draggable.attr('id');
    console.log("source is " + sourceId.substr(0, 1));
    var req;
    if (sourceId.substr(0, 1) == "w") //original move coming from the working side-insert
    {
        //console.log("in if");
        var oldId = sourceId;
        var newId = "p" + sourceId.substr(1);
        //console.dir($(ui.draggable).data('req'));
        req = $(ui.draggable).data('req');
        //show reqbox on plan

        var plannedEl = $(ui.draggable).clone();
        $(plannedEl).data('req', req);
        $(plannedEl).attr('id', newId).addClass('req_on_plan').removeClass('req_working').draggable({
            containment: 'document',
            cursor: 'move',
            snap: '.target',
            helper: 'original',
            revert: true
        }).appendTo($(this));

        //console.dir(event.this.id);
        var semesterCode = targId.substr(10, 1);
        //note:  (5,4) to get 2015 from 'plan020151'
        var planYear = targId.substr(5, 4);
        var url = "index.php";
        var proposedReqId = "";
        //get selected course
        //var selOptionBox=$(plannedEl).

        //TODO get progyear from student session data
        var progYear = 2014;

        //jquery bug--doesn't properly clone or drag the selected value
        var theSourceSelect = $("#" + sourceId + " " + "#op" + reqId);
        console.dir(theSourceSelect);
        var courseId = $(theSourceSelect).val();

        var theCloneSelect = $("#" + targId + " " + "#op" + reqId);
        $(theCloneSelect).val(courseId);
        console.dir("value: " + courseId);

        //TODO don't hardcode program id, pull from student session data
        var programId = 1;

        var hours = 0;
        hours = parseInt($("#op" + reqId + " #opt" + courseId).data("hours"));
        var hoursRequired = parseInt($("#r" + reqId).data("hours"));
        var hoursCounting = parseInt($("#r" + reqId).data("hoursCounting"));
        var hoursPlanned = parseInt($("#r" + reqId).data("hoursCountingPlanned"));

        var remaining = hoursRequired - hoursCounting - hoursPlanned - hours;
        console.dir("remaining:" + remaining);
        if (remaining <= 0) {
            //remove
            $(ui.draggable).remove();
        }
        else {
            ui.draggable.addClass('req_been_planned');
            //ui.draggable.draggable('disable');
            //ui.draggable.attr('draggable','false');
            //ui.draggable.draggable( 'option', 'revert', false );
        }


        console.dir("hours: " + hours);

        //insert into database
        $.ajax({
            url: "index.php",
            method: 'POST',
            data: {
                programId: programId,
                courseId: courseId,
                hours: hours,
                semesterCode: semesterCode,
                planYear: planYear,
                progYear: progYear,
                reqId: reqId,
                proposedReqId: proposedReqId
            },
            success: function (result) {
                //alert("success");
                //alert(result);
                //Build DOM
                var req = JSON.parse(result); //reqs is array of requirement objects
                //each req object also has a list of course option objects and list of courses taken objects
                //alert("after parse");
                //for(i=0;i<reqs.length;i++)
                //{
                processReqUpdate(req);
                //}
                //parse reqs


                //return result;
            }//end success
        });//end ajax


        //Will this complete the requirement? If so, disable on right, otherwise, update hours on right
        //update left and right with returned requirement

        //style the copy of requirement still left on working side
        //

    }//end if original move
    else if (sourceId.substr(0, 1) == "p") //move from one semester to another
    {
        //call movemethod
        $(ui.draggable).appendTo($(this)).css({position: 'relative', top: 0, left: 0});
        // ui.draggable.draggable( 'option', 'revert', true );

        console.log("in else");
    }//end else not original move
    //add code for drop-down change

}//end function


//TODO add function for changing drop-down selection on plan
function handleDropEventOnPlan(event, ui) {

    var targId = $(this).attr('id');

    //if prereqs met and course offered, let it drop
    //update planned course record
    //if (true) {

    var original;
    var sourceId = ui.draggable.attr('id');


    if (sourceId.substr(0, 1) == "w") //original move coming from the working side-insert
    {
        var req = $(ui.draggable).data('req');
        var reqId = req.id;

        var plannedEl = $(ui.draggable).clone();


        //todo use .data() to manage
        var plan = targId.substr(4, 1);  //get 4 from plan020164
        var year = targId.substr(5, 4);  //get 4 from plan020164
        var semesterCode = targId.substr(9, 1);  //get 4 from plan020164

        $(plannedEl).data('req', req);
        $(plannedEl).attr('id', targId).addClass('req_on_plan').removeClass('req_working').draggable({
            containment: 'document',
            cursor: 'move',
            snap: '.target',
            helper: 'original',
            revert: true
        }).appendTo($(this));

        var url = "index.php";
        var proposedReqId = "";

        //TODO get progyear from student session data
        var progYear = 2014;

        //jquery bug--doesn't properly clone or drag the selected value
        var theSourceSelect = $("#" + sourceId + " " + "#op" + reqId);
        console.dir(theSourceSelect);
        var courseId = $(theSourceSelect).val();

        var theCloneSelect = $("#" + targId + " " + "#op" + reqId);
        $(theCloneSelect).val(courseId);
        console.dir("value: " + courseId);

        //TODO don't hardcode program id, pull from student session data
        var programId = 1;

        var hours = 0;
        hours = parseInt($("#op" + reqId + " #opt" + courseId).data("hours"));
        var hoursRequired = parseInt($("#r" + reqId).data("hours"));
        var hoursCounting = parseInt($("#r" + reqId).data("hoursCounting"));
        var hoursPlanned = parseInt($("#r" + reqId).data("hoursCountingPlanned"));

        var remaining = hoursRequired - hoursCounting - hoursPlanned - hours;
        console.dir("remaining:" + remaining);
        if (remaining <= 0) {
            //remove
            $(ui.draggable).remove();
        }
        else {
            ui.draggable.addClass('req_been_planned');
            //ui.draggable.draggable('disable');
            //ui.draggable.attr('draggable','false');
            //ui.draggable.draggable( 'option', 'revert', false );
        }


        console.dir("hours: " + hours);

        //insert into database
        $.ajax({
            url: "index.php",
            method: 'POST',
            data: {
                plan: plan,
                programId: programId,
                courseId: courseId,
                hours: hours,
                semesterCode: semesterCode,
                planYear: year,
                progYear: progYear,
                reqId: reqId,
                proposedReqId: proposedReqId
            },
            success: function (result) {
                //alert("success");
                //alert(result);
                //Build DOM
                var req = JSON.parse(result); //reqs is array of requirement objects
                //each req object also has a list of course option objects and list of courses taken objects
                //	alert("after parse");
                //for(i=0;i<reqs.length;i++)
                //{
                //todo parse json and doing processreq will readd box
                //processReqUpdate(req);
                //}
                //parse reqs


                //return result;
            }//end success
        });//end ajax

        $("#r" + reqId + plan).addClass("req_completePlanned");
        $("#r" + reqId + plan).removeClass("req_incomplete");
        $("#w" + reqId + plan).remove();


    }//end if original move
    else if (sourceId.substr(0, 1) == "p") //move from one semester to another
    {
        //move, don't clone
        $(ui.draggable).appendTo($(this)).css({position: 'relative', top: 0, left: 0});

        //todo use .data() to manage
        var plan = targId.substr(4, 1);  //get 4 from plan020164
        var fromYear = targId.substr(5, 4);  //get 4 from plan020164
        var fromSemesterCode = targId.substr(9, 1);  //get 4 from plan020164
        /*
        var fromSemesterCode = $(ui.draggable).data('semesterCode');
        var fromYear = $(ui.draggable).data('year');
        var plan = $(ui.draggable).data('plan');
        */
        var toSemesterCode = targId.substr(9, 1);
        var toPlanYear = targId.substr(5, 4);  //note:  (5, 4) to get 2015 from 'plan020153'


        var url = "index.php";
        var proposedReqId = "";


        //jquery bug--doesn't properly clone or drag the selected value
        var theSourceSelect = $("#" + sourceId);
        //console.dir(theSourceSelect);
        var groupId = $(theSourceSelect).val();

        //TODO don't hardcode program id, pull from student session data
        var programId = 1;

       // var hours = 0;
       // hours = parseInt($("#op" + reqId + " #opt" + courseId).data("hours"));

        console.dir("hours: " + hours);
        //heeeeeeeeere set up ajax
        //insert into database &&&&&&&&&&& function movePlanItem($token, $studentId, $courseId, $semester, $year, $toSemester, $toYear,$reqId=null)

        groupId = parseInt(groupId);
        fromSemesterCode = parseInt(fromSemesterCode);
        fromYear = parseInt(fromYear);
        toSemesterCode = parseInt(toSemesterCode);
        toPlanYear = parseInt(toPlanYear);
        plan = parseInt(plan);

        $.ajax({
            url: "index.php",
            method: 'POST',
            data: {
                groupId: groupId,
                studentId: 1,
                fromSem: fromSemesterCode,
                fromYear: fromYear,
                toSem: toSemesterCode,
                toYear: toPlanYear,
                plan: plan
            },
            success: function (result) {

            }//end success
        });//end ajax


        /***** *****/


        // ui.draggable.draggable( 'option', 'revert', true );

        //console.log("in else");
    }//end else not original move
    //add code for drop-down change

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