/**
 * Shows input form for changing title name
 */

$(function () {
    $('#submit').click(function (e) {
        e.preventDefault();
        processInput();
    });
});

function keyStroke(e) {
    if (e.keyCode == 13) {
        e.preventDefault();
        processInput();
    }

    if (e.keyCode == 26) {
        return false;
    }
}

function processInput() {
    var title = $('#title').val();

    if (title == '') {
        alert("Title cannot be a null value");
    } else {
        $.ajax({
            async: false,
            method: 'POST',
            url: "app/plan/planTitle.php",
            data: {
                newTitle: title
            },
            success: function() {
                $('.nav-pills .planpill .active').text(title);
            }
        });
    }
}