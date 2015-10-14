/**
 * Shows input form for changing title name
 */

$(function () {
    $("#submit").click(function () {
        processInput();
    });
});

function keyStroke(event) {
    if (event.keyCode == 13) {
        processInput();
    }

    if (event.keyCode == 26) {
        return false;
    }
}

function processInput() {
    var title = $("#title").val();

    alert(title);

    if (title == '') {
        alert("Title cannot be a null value");
    } else {
        /* AJAX that only posts
        $.post("app/plan/planTitle.php", {
         newTitle: title
         })
         */

        $.ajax({
            async: false,
            type: 'POST',
            url: "app/plan/planTitle.php",
            data: {
                newTitle: title
            }
        });
    }
}