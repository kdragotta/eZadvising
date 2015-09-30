<!DOCTYPE html>
<html>
<head>
    <title> eZAdvising </title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="app/view/js/jquery-simulate.js"></script>

    <!--<script src="planTitle.js"></script>-->

    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
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
        <div id="currentState"></div>
    </div>

    <!-- newlayout <div id="col23"> -->

    <div id="main">
        <ul class="nav nav-pills">
            <li class="active"><a data-toggle="pill" href="#plan0">Home</a></li>
            <li><a data-toggle="pill" href="#plan1">Menu 1</a></li>
        </ul>
        <?php include 'plan.php'; ?>
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

        <div id="stillRequiredList"></div>
    </div>
    <!-- end div right -->


</div>
<!-- end div wrapper -->

<footer>
</footer>
<div id="temp_hidden" class="temp_hidden"></div>
<script src="app/view/js/advising_functions.js"></script>
