/**
 * Shows input form for changing title name
 */

$(function() {
    $("#submit").click(function(){
        var title = $("#title").val();

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

/**
 *   Clear input when closed
 */

$('#modal').on('hidden.bs.modal', function(){
    $('#title').val('');
});