<!DOCTYPE html>
<html>
<head>
    <title> eZAdvising </title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="app/view/js/jquery-simulate.js"></script>
    <!--<script src="planTitle.js"></script>-->

    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="app/view/css/styles.css">
    <link rel="stylesheet" type="text/css" href="app/view/css/planNamePopup.css">
</head>

<body>
<div id="top" class="top">
    <h3> eZAdvising </h3>
</div>

<div id="wrapper">

    <div id="left">
        <table>
            <tr>
                <th>Requirements</th>
            </tr>
        </table>
        <div id="currentState">

        </div>
    </div>

    <!-- newlayout <div id="col23"> -->

    <div id="main">
        <tr>
            <!-- Plan Title Manipulation -->
            <td><h3 id="titlePlaceholder">Default Title</h3></td>
        </tr>
        <tr>
            <td>
                <button data-show="on" onclick="title_show()"> Change Plan Name</button>
                <button data-show="on" onclick="showHideSummers()"> Show/Hide Summers</button>
            </td>
        </tr>
        <!-- <tr> <td><button onclick="unplan()" > Save Plan </button> </td> </tr>
         <tr> <td><button onclick="unplan()" > Revert to Saved Plan </button></td></tr>
         -->
        </table>
        <div id="thePlan"></div>

    </div>
    <!-- end div main -->

    <!-- Popup Title Form -->
    <div id="popUp" title="Change Plan Name" style="display: none">
        <div id="titlePopup">
            <div id="changePlanTitle">
                <form action="#" id="titleForm" method="post" name="titleForm">
                    <input id="titleName" name="name" placeholder="Name" type="text">
                    <input type="submit" id="changeTitle" onclick="titleSubmit();" value="Submit">
                </form>
            </div>
        </div>
    </div>
    <!-- End of Title Form -->

    <!-- newlayout </div> --><!-- end div col23 -->

    <div class="target" id="right">

        <table id="required_table">
            <tr>
                <th>Need to Take</th>
            </tr>
        </table>
        <div id="eligibleSwitch">
            <input type="checkbox" id="semCheckBox"/>
            <span>Highlight Courses Eligible </span>
            <select id="semList"></select>
        </div>
        <div id="stillRequiredList">

        </div>

        <!-- end stillRequiredList div -->


    </div>
    <!-- end div right -->


</div>
<!-- end div wrapper -->

<footer>
</footer>
<div id="temp_hidden" class="temp_hidden"></div>
<script src="app/view/js/advising_functions.js"></script>
