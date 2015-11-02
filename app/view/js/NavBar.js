// Initializations
var title = '';
var currentTitle = '';

//TODO: Have rowcount pulled from the database
var rowCount = 0;

// Maximum Number of Plans
var maxNumOfPlans = 5;

/**
 * Handle passing of new tabs
 *  - Sets title to value from input
 */

$(function () {
    $('#addPill').click(function (e) {
        title = $('#title').val();

        if ($('.modal-title').text() == "Add New Plan") {
            NewTab();
        } else {
            RenameTab();
        }
    });
});

/**
 * Restrictions for certain key strokes
 * Key Codes:
 *  13 = 'enter'
 *  26 = 'esc'
 */

function keyStroke(e) {
    if (e.keyCode == 13) {
        e.preventDefault();
        return false;
    }

    if (e.keyCode == 26) {
        return false;
    }
}

/**
 * Opens form to add new plan
 *  - Focus on load
 *  - Clear input field on load
 */

function AddTitle() {
    $("#modal").modal('show').on('shown.bs.modal', function () {
        $('.modal-title').text("Add New Plan");
        ClearFormField();
    });
}

/**
 * Opens form to change plan name
 *  - Focus on load
 *  - Clear input field on load
 */

function ChangeTitle() {
    // Grabs index of active tab to change name
    currentTitle = $('.nav-pills .active').index() + 1;

    $("#modal").modal('show').on('shown.bs.modal', function () {
        $('.modal-title').text("Change Plan Name");
        ClearFormField();
    });
}

/**
 * Clears form field
 */

function ClearFormField() {
    $('#title').val('');
    $('#title').focus();
}

/**
 * Generate Tabs
 */

function GenerateTab() {
    $("#modal").modal('hide');

    rowCount++;

    $(".nav-pills").tabs();
    var pills = $("div#pills ul li");
    var tab = $("div#pills ul li a");
    var length = $("div#pills ul li").length;

    tab.eq(length - 1).text(title);
    pills.eq(length - 1).removeAttr('onclick');

    if (rowCount < maxNumOfPlans - 1) {
        $("div#pills ul").append("<li class='planpill' onclick='AddTitle()' id='pill" + length + "'><a href='#plan" + length + "'data-toggle='pill'>" +
            "<span class='glyphicon glyphicon-plus'></span></a></li>");
    } else {
        $("div#pills ul").append("<li class='planpill' id='pill" + length + "'><a href='#plan" + length + "'data-toggle='pill'>" +
            "</li>");
    }
}

/**
 * Rename current tab
 */

function RenameTab() {
    if (title == '') {
        ChangeTitle();
    } else {
        $.ajax({
            url: "index.php",
            method: 'POST',
            data: {
                op: 'plan',
                id: currentTitle,
                newTitle: title
            },
            success: function () {
                var tab = $(".nav-pills .active a");
                tab.eq(length - 1).text(title);
                title = '';
            }
        });
    }
}

/**
 * Reload existing tabs
 */

function ReloadTab() {
    $.ajax({
        url: "index.php",
        method: 'POST',
        data: {
            title: title,
            plan: rowCount
        },
        success: function () {
            while (rowCount <= 5 && title != '') {
                GenerateTab();
            }
        }
    });
}

/**
 * Create New Tab
 */

function NewTab() {
    if (title == '') {
        AddTitle();
    } else {
        if (rowCount == maxNumOfPlans) {
            alert('Maximum number of plans reached!');
        } else {
            $.ajax({
                url: "index.php",
                method: 'POST',
                data: {
                    op: 'plan',
                    title: title,
                    plan: rowCount + 1
                },
                success: function () {
                    GenerateTab();
                    title = '';
                }
            });
            GeneratePlan();
        }
    }
}

/**
 * Generates plans
 */

function GeneratePlan() {
    //TODO: shitty hack, need to fix later

    var length = $("div#pills ul li").length - 1;

    //rename DOM elements
    //todo use last time to copy instead of 0
    var plan = $('#plan0').clone(true);
    plan.attr('id', 'plan' + length);

    var currentState = $(plan.children().children().children()[1]);
    currentState.attr('id', 'currentState' + length);
    currentState.children().remove();

    var stillRequiredList = $(plan.children().children().children()[6]);
    stillRequiredList.attr('id', 'stillRequiredList' + length);
    stillRequiredList.children().remove();

    var thePlan = $(plan.children().children().children()[3]);
    thePlan.attr('id', 'thePlan' + length);
    thePlan.children().remove();

    //remove in active tabbing from active tab
    //todo fix later, from all tabs instead of one
    $('.in.active').removeClass('in active');

    //add dom
    plan.addClass(('in active'));

    $('.tab-content').append(plan);

    $(initSemesterStart(length));
    $(init(length));

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
            var reqs = JSON.parse(result);

            for (var i = 0; i < reqs.length; i++) {

                var req = reqs[i];
                if (req.type != "onplan") {
                    var count = length;

                    var classBox = new ClassBox(req);
                    classBox.createBox();
                    classBox.addCourseOptions();
                    classBox.addCompletedCourses();
                    classBox.addPlannedCourses();

                    classBox.addToCurrentState(length);
                    classBox.addToRequiredList(length);

                    classBox.addCourseToPlan();
                }

                if (req.type == "onplan") {
                    if (req.plan == length) {
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
            }
            //return result;
        }//end success
    });//end ajax
}