planMap = {};

$(initState(0));

function processReqUpdate(req) {

    if (req.type != "onplan") {
        var count = $('.planpill').length;

        var classBox = new ClassBox(req);
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

    if (req.type == "onplan") {
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

        classBox = new ClassBox(req);
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

    return [nextYear, nextSemester];

}

function showHideSummers() {
    $(".semester_block.minor").toggle();
}

function initState(index) {

    $(initSemesterStart(index));
    $(init(index));

    $.ajax({
        url: "index.php",
        method: 'POST',
        data: {
            op: 'student',
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
        helper: 'clone'
    });

    $('.semester_plan').droppable({
        drop: handleDropEventOnPlan,
        hoverClass: "highlight_drop",
        cursor: 'move',
        snap: '.target',
        helper: 'clone'
    });
    $('#stillRequiredList' + index).droppable({
        drop: handleDropEventOnWorking,
        hoverClass: "highlight_drop"
    });

     //$( ".semester_plan" ).draggable( "option", "helper", 'clone' );
     //$( ".semester_plan" ).on( "dragstop", function( event, ui ) {} ); //dragstart, drag, dragstop, dragcrete


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
        //$(sel).draggable('option', 'revert', true);
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
            helper: 'original'
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

        var hours = parseInt($("#op" + reqId + " #opt" + courseId).data("hours"));
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
                op: 'planitem',
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

                var req = JSON.parse(result); //reqs is array of requirement objects

            }//end success
        });

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
            helper: 'original'
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

        var hours = parseInt($("#op" + reqId + " #opt" + courseId).data("hours"));
        /*
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
        */


        $("#r" + reqId + plan).addClass("req_completePlanned");
        $("#r" + reqId + plan).removeClass("req_incomplete");
        $("#w" + reqId + plan).remove();

        //insert into database
        $.ajax({
            op: 'planitem',
            url: "index.php",
            method: 'POST',
            data: {
                op: 'planitem',
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
                var req = JSON.parse(result); //reqs is array of requirement objects

            }
        });



    }
    //move from one semester to another
    else if (sourceId.substr(0, 1) == "p")
    {
        //get source plan before moving
        var sourceParentId = ui.draggable[0].parentNode.id;
        //move, don't clone
        $(ui.draggable).appendTo($(this)).css({position: 'relative', top: 0, left: 0});


        var fromYear = sourceParentId.substr(5, 4);  //get 4 from plan020164
        var fromSemesterCode = sourceParentId.substr(9, 1);  //get 4 from plan020164

        //todo use .data() to manage
        var plan = targId.substr(4, 1);  //get 4 from plan020164

        /*
         var fromSemesterCode = $(ui.draggable).data('semesterCode');
         var fromYear = $(ui.draggable).data('year');
         var plan = $(ui.draggable).data('plan');
        */

        var toSemesterCode = targId.substr(9, 1);
        var toPlanYear = targId.substr(5, 4);  //note:  (5, 4) to get 2015 from 'plan020153'


        //jquery bug--doesn't properly clone or drag the selected value
        var theSourceSelect = $("#" + sourceId);
        //console.dir(theSourceSelect);
        var groupId = $(theSourceSelect).val();

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
                op: 'planitem',
                groupId: groupId,
                studentId: 1,
                fromSem: fromSemesterCode,
                fromYear: fromYear,
                toSem: toSemesterCode,
                toYear: toPlanYear,
                plan: plan
            },
            success: function (result) {

            }
        });
    }
}


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