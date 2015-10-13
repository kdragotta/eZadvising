/**
 * Shows input form for changing title name
 */

$(function() {
    $("#submit").click(function(){
        var title = $("#title").val();

        alert(title);

        if(title == '')
        {
            alert("Title cannot be a null value")
        } else {
            $.post("app/model/planTitle.php", {
                newTitle: title
            });
        }
    });
});

function clearInput() {
    $("#title").val('');
}