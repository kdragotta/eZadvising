$(initSemesterStart(0));
$(initSemesterStart(1));

$(initState());

$(init(0));
$(init(1));


function ClassBox(req, classStr, newEl) {
    this.req = req;
    this.classStr = classStr;
    this.newEl = newEl;
    this.reqSideId;
    this.planId;
    this.workingSideId;
    this.pStr;
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
        pSem = theCourseP.semester;
        pYear = theCourseP.year;
        this.pStr = "plan" + pYear + pSem;


        //console.log(pStr);
        var theCoursePId = theCourseP.id;
        //set the drop-down box to be the right course
        $("#" + this.pStr + " #op" + this.req.id).val(theCoursePId);

        //update hours for each semester
        var currentHours = parseInt($("#" + this.pStr).data("currentHours"), 10);
        var add = parseInt(theCourseP.hours);
        currentHours = currentHours + add;
        $("#" + this.pStr).data("currentHours", currentHours);
        var targetElSel = "#fstats" + this.pStr;
        $(targetElSel).text(currentHours);
    }
}

ClassBox.prototype.addCourseToPlan = function () {
    var newElPlan = $(this.newEl).clone(true); //to put on plan

    $(newElPlan).data("onSemester", this.pStr);
    $(newElPlan).data("whereami", "plan");
    $(newElPlan).addClass("req_on_plan");
    $(newElPlan).attr('id', this.planId);

    $(newElPlan).draggable({
        containment: 'document',
        cursor: 'move',
        snap: '.target',
        helper: 'clone',
        revert: 'true'
    });//end draggable

    if (typeof this.req.plan != "undefined")
        $("#plan" + this.req.plan).append(newElPlan);

}

ClassBox.prototype.createWorkingPlan = function () {
    var newElPlanWorking = $(this.newEl).clone(true); //to put on right side in case it gets moved off plan

    $(newElPlanWorking).data("whereami", "working");
    $(newElPlanWorking).addClass("req_working");
    $(newElPlanWorking).addClass("req_been_planned");
    $(newElPlanWorking).attr('id', this.workingSideId);

    $(newElPlanWorking).draggable({
        containment: 'document',
        cursor: 'move',
        snap: '.target',
        helper: 'clone',
        revert: 'true'
    });//end draggable

    $("#plan020162").append(newElPlanWorking);
}

ClassBox.prototype.addToCurrentState = function (index) {
    var newEl = $(this.newEl).clone(true); //for the right side

    //set up stats data
    $(newEl).data("hoursCounting", this.req.hoursCounting);
    $(newEl).data("hoursCountingPlanned", this.req.hoursCountingPlanned);
    $(newEl).data("hours", this.req.hours);

    var needed = this.req.hours - this.req.hoursCounting - this.req.hoursCountingPlanned;
    $(newEl).data("stillNeeded", needed);

    $(newEl).attr('id', this.reqSideId);
    $(newEl).data("whereami", "reqs");

    //Add stats to left side box
    $(newEl).append("<span class='stats'> c:" + this.req.hoursCounting + "/p:" +
        this.req.hoursCountingPlanned + "/r:" + this.req.hours + "</span>");

    // groupName not currently used
    $(newEl).attr("groupName", this.req['groupName']);

    $(newEl).data("req", this.req);

    if (typeof this.req.plan != "undefined") {
        var planIndex = this.req.plan.substr(0, 1);
        if(planIndex == index) {
            $(newEl).addClass("req_completePlanned");
        }
    }
    else {
        $(newEl).addClass("req_incomplete");
    }

    $("#currentState" + index).append(newEl);
}

ClassBox.prototype.addToRequiredList = function (index) {
    var needed = this.req.hours - this.req.hoursCounting - this.req.hoursCountingPlanned;

    //split into left only and right clone here
    var newElWorking = $(this.newEl).clone(true); //for the right side
    $(newElWorking).attr('id', this.workingSideId);
    $(newElWorking).data("whereami", "working");

    //Add stats to right side box
    $(newElWorking).append("<span class='stats'> need:" + needed + "</span>");

    //add the jquery data object - TODO check if already added
    $(newElWorking).data("req", this.req);

    //start with classes for the left side
    if (this.req.complete) {
        $(newElWorking).addClass("req_complete");
    }
    else if (this.req.completePlanned) {
        $(newElWorking).addClass("req_completePlanned");
    }
    else if (this.req.somePlanned) {
        $(newElWorking).addClass("req_partialPlanned");
    }
    else {
        $(newElWorking).addClass("req_incomplete");
    }

    $(newElWorking).addClass("req_working");

    $(newElWorking).draggable({
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

    if (typeof this.req.plan != "undefined") {
        var planIndex = this.req.plan.substr(0, 1);
        if(planIndex != index) {
            $("#stillRequiredList" + index).append(newElWorking);
        }
    }
    else {
        $("#stillRequiredList" + index).append(newElWorking);
    }
}



function processReqUpdate(req) {

    var courseOptions = req.courseOptions; //courseOptions is now array of courses
    var coursesCounting = req.coursesCounting; //coursesCounting is now array of course records
    var coursesCountingPlanned = req.coursesCountingPlanned;

    var classStr = "req_box";
    //create the MAIN requirement box element
    //group name is the requirement name (now comes from program_requirements.title)
    var newElStr = "<div draggable=true class='" + classStr + "'><header>" + req.groupName + "</header>" + "</div>";
    var newEl = $(newElStr);

    var count = $('.planpill').length;

    //build base classes
    //TODO: add classes for category
    /* if(req.category==2) classStr+=" foundation";
     else if(req.category==3) classStr+=" major";
     */

    var classBox = new ClassBox(req, classStr, newEl);
    classBox.createBox();
    classBox.addCourseOptions();
    classBox.addCompletedCourses();
    classBox.addPlannedCourses();
    classBox.addCourseToPlan();

    for(var i = 0; i < count; i++) {
        classBox.addToCurrentState(i);
        classBox.addToRequiredList(i);
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

//get user id from session or redirect to login (wiht message to come back)
//student meets prereqs based on already loaded classes
//would student meet prereq based on already loaded plus plan
//idea:simple course prereq calculator in javascript - load prereq data for each course and fill with true or

    //fix hardcoding for student, pass as post params
    //$token || studentId || $programId || !$year)

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

            }//end for each requirement

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

        //insert into database then update req everywhere.
        console.dir($(plannedEl).data('req'));
        var req = $(plannedEl).data('req');
        var reqId = req.id;
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

        var theCloneSelect = $("#" + newId + " " + "#op" + reqId);
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

        //insert into database then update req everywhere.
        console.dir($(plannedEl).data('req'));
        var req = $(plannedEl).data('req');
        var reqId = req.id;
        //console.dir(event.this.id);
        var semesterCode = targId.substr(9, 1);
        //note:  (5, 8) to get 2015 from 'plan2015'
        var planYear = targId.substr(5, 4);
        $(plannedEl).data("onSemester", targId);
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

        var theCloneSelect = $("#" + newId + " " + "#op" + reqId);
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

        //get '020154' of plan020154
        var plan = this.id.substr(4);

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
                //	alert("after parse");
                //for(i=0;i<reqs.length;i++)
                //{
                processReqUpdate(req);
                //}
                //parse reqs


                //return result;
            }//end success
        });//end ajax

        //update hours for the semester
        /*
         already done on update
         var currentHours = parseInt( $("#"+targId).data("currentHours"), 10);
         var add = parseInt(hours);
         currentHours = currentHours + add;
         $("#"+targId).data("currentHours",currentHours);
         var targetElSel = "#fstats"+targId;
         $(targetElSel).text(currentHours);
         */

        //Will this complete the requirement? If so, disable on right, otherwise, update hours on right
        //update left and right with returned requirement

        //style the copy of requirement still left on working side
        //

    }//end if original move
    else if (sourceId.substr(0, 1) == "p") //move from one semester to another
    {
        //move, don't clone
        $(ui.draggable).appendTo($(this)).css({position: 'relative', top: 0, left: 0});
        //do db update moveplan

        var req = $(ui.draggable).data('req');
        var reqId = req.id;

        //var reqIdToMove=req.id;
        //var reqToMove

        //update hours per semester for both


        /***** work with ****/

        //no cloning, just move
        //	var plannedEl= $(ui.draggable).clone();
        // $(plannedEl).data('req',req);
        /* $(plannedEl).attr('id',newId).addClass('req_on_plan').removeClass('req_working').draggable({
         containment: 'document',
         cursor: 'move',
         snap: '.target',
         helper: 'original',
         revert: true}).appendTo($(this));
         */

        //insert into database then update req everywhere.
        // console.dir($(plannedEl).data('req'));
        //var req=$(plannedEl).data('req');
        ///var reqId=req.id;
        //console.dir(event.this.id);

        //alert('tosem');
        //alert(targId);
        var toSemesterCode = targId.substr(9, 1);
        //alert(toSemesterCode);
        //note:  (5, 4) to get 2015 from 'plan020153'
        var toPlanYear = targId.substr(5, 4);
        var fromSemesterCode = $(ui.draggable).data('onSemester');
        //alert('fromsem');
        //alert(fromSemesterCode);
        fromSemesterCode = fromSemesterCode.substr(9, 1);
        //alert(fromSemesterCode);
        var fromPlanYear = $(ui.draggable).data('onSemester');
        fromPlanYear = fromPlanYear.substr(5, 4);

        var url = "index.php";
        var proposedReqId = "";


        //TODO get progyear from student session data
        // var progYear=2014;

        //jquery bug--doesn't properly clone or drag the selected value
        var theSourceSelect = $("#" + sourceId + " " + "#op" + reqId);
        //console.dir(theSourceSelect);
        var courseId = $(theSourceSelect).val();

        //TODO don't hardcode program id, pull from student session data
        var programId = 1;

        var hours = 0;
        hours = parseInt($("#op" + reqId + " #opt" + courseId).data("hours"));

        console.dir("hours: " + hours);
        //heeeeeeeeere set up ajax
        //insert into database &&&&&&&&&&& function movePlanItem($token, $studentId, $courseId, $semester, $year, $toSemester, $toYear,$reqId=null)

        $.ajax({
            url: "index.php",
            method: 'POST',
            data: {
                courseId: courseId, studentId: 1, fromSem: fromSemesterCode, fromYear: fromPlanYear,
                toSem: toSemesterCode, toYear: toPlanYear, reqId: reqId
            },
            success: function (result) {
                //alert("success");
                //alert(result);
                /*
                 var req=JSON.parse(result); //reqs is array of requirement objects
                 //each req object also has a list of course option objects and list of courses taken objects
                 alert("after parse");
                 //for(i=0;i<reqs.length;i++)
                 //{
                 processReqUpdate(req);
                 //}
                 */

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