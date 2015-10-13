/**
 * Shows input form for changing title name
 */

$(document).ready(function () {
    $('#titleForm').submit(function (event) {

        var title = $("input#title").val();

        $.ajax({
            url: "app/model/planTitle.php",
            method: "POST",
            data: title
        });
    });
});

/*
 $("#titleForm").submit(function () {
 $.post($("#titleForm").attr("action"),
 $("#titleForm :input").serializeArray());
 clear();
 });
 */

function changeTitle() {
    $("#popUp").dialog({
        draggable: false
    });
}

function clear() {
    $("#titleForm :input").each(function () {
        $(this).val('');
    })
}