/**
 * Shows input form for changing title name
 */

$(function() {
    $("#submit").click(function(){
        processInput();
    });
});

function keyStroke(event)
{
    if (event.keyCode == 13) {
        processInput();
    }

    if (event.keyCode == 26) {
        return false;
    }
}

function processInput() {
    var title = $("#title").val();

    alert (title);

    if(title == '')
    {
        alert("Title cannot be a null value");
    } else {
        $.post("app/plan/planTitle.php", {
            newTitle: title
        });
    }
}