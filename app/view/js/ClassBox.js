function ClassBox(req) {

    //todo: fix shitty code
    var classStr = "req_box";
    var newElStr = "<div draggable=true class='" + classStr + "'><header>" + req.groupName + "</header>" + "</div>";
    var newEl = $(newElStr);

    this.req = req;
    this.classStr = classStr;
    this.newEl = newEl;
    this.boxId;
    this.reqSideId;
    this.planId;
    this.workingSideId;
}

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
};

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
};

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
};

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
        //no reason to change color
        //$(newEl).addClass("req_partialPlanned");
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
};

ClassBox.prototype.addCourseToPlan = function () {
    var newElPlan = $(this.newEl).clone(true); //to put on plan

    $(newElPlan).data("semesterCode", this.req.semesterCode);
    $(newElPlan).data("year", this.req.year);
    $(newElPlan).data("plan", this.req.plan);
    $(newElPlan).data("whereami", "plan");
    $(newElPlan).addClass("req_on_plan");
    $(newElPlan).attr('id', 'p' + this.req.plan + this.req.courseId);  //using this.req.courseId is total shit
    $(newElPlan).val(this.req.id);


    $(newElPlan).draggable({
        containment: 'document',
        cursor: 'move',
        snap: '.target',
        helper: 'clone',
        revert: 'true'
    });//end draggable

    //boxId is a workaround should use this.boxId instead of making it
    var boxId = "plan" + this.req.plan + this.req.year +
                this.req.semesterCode;
    $("#" + boxId).append(newElPlan);
};