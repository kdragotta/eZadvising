/**
 * Shows input form for changing title name
 */

function title_show() {

    // $("#popUp").dialog();

    var title = prompt("Enter new plan title:");

    if (title != null) {
        alert(title);
    }
}

function titleSubmit() {
    /*
    $('#changeTitle').submit(function(e) {
        e.preventDefault();
    });
    */
}