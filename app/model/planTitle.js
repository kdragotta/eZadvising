/**
 * Created by Billy on 9/22/15.
 */

function title_show() {
    $('#popUp').dialog();
}

function titleSubmit() {
    var rice = document.getElementById("titleName").value;

    alert(rice);

    $("#titleForm").submit(function(e) {
        e.preventDefault();
    });
}