/**
 *  Functionality of Navigation Bar
 *  - Renaming of tabs
 *  - Adding new tabs
 *  - Reloads old tabs if exists
 *  - Clear form fields on load
 *  - Color coded
 *
 *  ERROR CHECKING:
 *  - Disables user from renaming default tab
 *  - Disables user from renaming the '+' tab
 *  - Disables key input of ESC and Enter
 *  - Always keeps track of user's last active tab
 *  - Checks if title is null & if json result is null, exit(-1)
 *
 *  TODO LIST:
 *  - Instead of css hardcoded hovers, use javascript to manipulate
 *  - Remove commenting of bootstrap.min.css due to override of active color
 *  - Return to last active tab on reload
 *  - Finalize error checking
 */

// Maximum number of plans
var maxNumOfPlans = 6;

// LGBT Color Array
var lgbt = ['#ff3e18', '#fc9a00', '#ffd800', '#39ea7c', '#0bb2ff', ' #985aff'];

// Initializations
var color = '#';
var colors = [];
var title = '';
var plan = 0;
var index = -1;
var lastTab = 0;

/**
 * Loads on start, resets the color of active plan
 */

$(window).load(function () {
    ReloadTab();

    var defaultColor = document.getElementById('hover' + plan);
    defaultColor.style.backgroundColor = lgbt[plan];
    defaultColor.style.color = 'black';

    var currentPlan = document.getElementsByClassName('semester_name');

    for (var i = 0; i < currentPlan.length; i++) {
        currentPlan[i].style.backgroundColor = lgbt[plan];
        currentPlan[i].style.color = 'black';
    }
});

/**
 * On click event handler for nav bar
 */

$('.nav-pills').click(function (e) {

    lastTab = $('.nav-pills .active').index();

    ReloadActiveColor(($(e.target).attr("id").substring(5)));

});

/**
 * If user decides to close modal, return to last active tab
 */

$(function () {
    $('#closeModal').click(function (e) {
        title = '';

        if ($('.modal-title').text() == "Add New Plan") {
            $('.nav-pills .active').removeClass('active');
            $('#pill' + lastTab).addClass('active');
            ResetActiveTabColor(lastTab);
        }
    });
});

/**
 * Handle passing of new tabs
 *  - Sets title to value from input
 */

$(function () {
    $('#addPill').click(function () {
        title = $('#title').val();
        if ($('.modal-title').text() == "Add New Plan") {
            NewTab();
            RefreshData();
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
    if ($('.nav-pills .active').index() == 0) {
        window.alert("You are not allowed to rename the default plan.");
    } else {
        // Grabs index of active tab to change name
        index = $('.nav-pills .active').index() + 1;

        $("#modal").modal('show').on('shown.bs.modal', function () {
            $('.modal-title').text("Change Plan Name");
            ClearFormField();
        });
    }
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

    $(".nav-pills").tabs();
    var pills = $("div#pills ul li");
    var tab = $("div#pills ul li a");
    var length = $("div#pills ul li").length;

    tab.eq(length - 1).text(title);

    pills.eq(length - 1).removeAttr('onclick');

    // Do if the number of plans allowed has not been hit
    if (plan < maxNumOfPlans - 1) {
        $("div#pills ul").append("<li class='planpill' onclick='AddTitle()' id='pill" +
            length + "'><a href='#plan" + length + "'data-toggle='pill' id='hover" + length + "'>+</a></li>");
    }

    // Reset old tab to correct colors
    $('#newPlan').attr('id', 'hover' + plan);

    // Removes next tab coloring for some fluid appearance
    $('#hover' + (plan + 1)).attr('id', 'newPlan');

    title = '';
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
                id: index,
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
            op: 'plan',
            id: 1
        },
        success: function (result) {
            var titleHolder = JSON.parse(result);

            if (!result) {
                exit(-1);
            } else {
                for (var count = 0; count < titleHolder.length; count++) {
                    title = titleHolder[count].title;
                    plan = count;
                    colors[count] = titleHolder[count].color;
                    GenerateTab();
                    GeneratePlan(count + 1);
                }
            }
        }
    });
}

/**
 * Refreshes data on submit
 */

function RefreshData() {
    $.ajax({
        url: "index.php",
        method: 'POST',
        data: {
            op: 'plan',
            id: 1
        },
        success: function (result) {
            var titleHolder = JSON.parse(result);

            if (!result) {
                exit(-1);
            } else {
                for (var count = 0; count < titleHolder.length; count++) {
                    title = titleHolder[count].title;
                    colors[count] = titleHolder[count].color;
                }
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
        if (plan == maxNumOfPlans) {
            alert('Maximum number of plans reached!');
        } else {
            $.ajax({
                url: "index.php",
                method: 'POST',
                data: {
                    op: 'plan',
                    title: title,
                    plan: plan + 1,
                    // LBGT Color
                    color: GetLBGTColor(),
                    // Random Color
                    // color: GetRandomColor(),
                    active: 'TRUE',
                },
                success: function () {
                    plan++;
                    GenerateTab();
                    NewTabColor();
                    title = '';
                }
            });
            GeneratePlan(-1);
        }
    }
}

/**
 * Random color generator
 */

function GetRandomColor() {
    var letters = '0123456789ABCDEF'.split('');
    color = '#';

    for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }

    return color;
}

/**
 * Get lbgt color
 */

function GetLBGTColor() {

    return lgbt[plan + 1];
}

/**
 * Reload styling for tabs
 *  - Returns if value is null on click
 *      (Means user clicked somewhere between or around the pills)
 */

function ReloadActiveColor(value) {
    if (value == '') {
        return;
    }

    var defaultColor = document.getElementById('hover' + lastTab);
    defaultColor.style.backgroundColor = '';

    if (value == plan + 1) {
        var currentPlan = document.getElementsByClassName('semester_name');

        for (var i = 0; i < currentPlan.length; i++) {
            currentPlan[i].style.backgroundColor = 'lightgrey';
            currentPlan[i].style.color = 'black';
        }
    } else {
        var currentPlan = document.getElementsByClassName('semester_name');

        for (var i = 0; i < currentPlan.length; i++) {
            currentPlan[i].style.backgroundColor = lgbt[value];
            currentPlan[i].style.color = 'black';
        }
    }

    var activeColor = document.getElementById('hover' + value);
    activeColor.style.backgroundColor = colors[value];
    activeColor.style.color = 'black';
}

function ResetActiveTabColor(value) {
    var lastActiveColor = document.getElementById('hover' + value);
    lastActiveColor.style.backgroundColor = colors[value];

    var lastPlan = document.getElementsByClassName('semester_name');

    for (var i = 0; i < lastPlan.length; i++) {
        lastPlan[i].style.backgroundColor = lgbt[value];
        lastPlan[i].style.color = 'black';
    }
}

/**
 * New tab styling
 */

function NewTabColor() {
    lastTab = $('.nav-pills .active').index();

    var newColor = document.getElementById('hover' + (lastTab));
    newColor.style.backgroundColor = lgbt[lastTab];

    var currentPlan = document.getElementsByClassName('semester_name');

    for (var i = 0; i < currentPlan.length; i++) {
        currentPlan[i].style.backgroundColor = lgbt[lastTab];
        currentPlan[i].style.color = 'black';
    }
}

/**
 * Generates plans
 */

function GeneratePlan(value) {
    var length;

    //TODO: shitty hack, need to fix later
    if (value == -1) {
        length = $("#pills ul li").length;
    } else {
        length = value;
    }

    // Rename DOM elements
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

    // Remove in active tabbing from active tab
    //todo fix later, from all tabs instead of one
    $('.in.active').removeClass('in active');

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
            var reqs = JSON.parse(result);

            for (var i = 0; i < reqs.length; i++) {

                var req = reqs[i];
                var classBox;

                if (req.type != "onplan") {
                    classBox = new ClassBox(req);
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

            // Removes 'in active' after each iteration
            $('#plan' + value).removeClass('in active');

            // On final iteration add active back to plan0 for refreshes
            if (value == $('.nav-pills').length) {
                $('#plan0').addClass('in active');
            }
        }
    });
}