<!DOCTYPE html>
<html>
<head>
    <title> eZAdvising </title>

    <script src="app/view/js/lib/jquery.min.js"></script>
    <script src="app/view/js/lib/jquery-ui.min.js"></script>
    <script src="app/view/js/lib/bootstrap.min.js"></script>
    <script src="app/view/js/lib/jquery-simulate.js"></script>

    <!-- Need to fix <script src="app/plan/planTitle.js"></script> -->

    <link rel="stylesheet" href="app/view/css/lib/jquery-ui.css">
    <link rel="stylesheet" href="app/view/css/lib/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="app/view/css/styles.css">
    <link rel="stylesheet" type="text/css" href="app/view/css/popup.css">
</head>

<body>
<div id="top" class="top">
    <h3> eZAdvising </h3>

</div>

<!-- Bootstrap Form -->
<div id="modal" class="modal fade" role="dialog" data-keyboard="false">
    <div class="vertical-alignment-helper">
        <div class="modal-dialog">
            <!-- Content -->
            <div class="modal-content" id="form-content">
                <div class="modal-header">
                    <a type="button" class="close" data-dismiss="modal">x</a>
                    <h4 class="modal-title">Add New Plan</h4>
                </div>
                <div class="modal-body">
                    <form role="form" action="" method="POST" id="form" class="changeTitle"
                          onkeydown="keyStroke(event)">
                        <div class="form-group">
                            <input type="text" class="form-control" id="title" value=" " autofocus>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="addPill">Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End of Bootstrap Form -->

<!-- Nav Bar Tabs -->
<div id="pills">
    <ul class="nav nav-pills">
        <li class="planpill active" id="pill0" href="plan0"><a href="#plan0" data-toggle="pill">Default</a></li>
        <li class="planpill" id="pill1" onclick="ShowBox()"><a href="#plan1" data-toggle="pill">+</a></li>
    </ul>
</div>
<!-- End of Nav Bar Tabs -->

<?php
$planCount = 5;

if ($planCount > 1) {
    echo('<div class="tab-content">');
}

for ($i = 0; $i <= $planCount; $i++) {
    ?>
    <div id="plan<?php echo $i; ?>" class="tab-pane fade<?php if ($i == 0) echo ' in active'; ?>">

        <div id="wrapper">
            <div id="left">
                <table>
                    <tr>
                        <th>Classes Selected</th>
                    </tr>
                </table>
                <div id="currentState<?php echo $i; ?>"></div>
            </div>

            <!-- newlayout <div id="col23"> -->

            <div id="main">
                <tr>
                    <td>
                        <h4>
                            <button data-toggle="modal" data-target="#modal" type="button">Change Plan Name</button>
                            <button data-show="on" onclick="showHideSummers()"> Show/Hide Summers</button>
                        </h4>
                    </td>
                </tr>
                <!-- <tr> <td><button onclick="unplan()" > Save Plan </button> </td> </tr>
                <tr> <td><button onclick="unplan()" > Revert to Saved Plan </button></td></tr>
                -->
                <div id="thePlan<?php echo $i; ?>"></div>
            </div>

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

                <div id="stillRequiredList<?php echo $i; ?>"></div>
            </div>
            <!-- end div right -->
        </div>
    </div>
<?php } ?>
</div>
<!-- end div wrapper -->

</body>
<script src="app/view/js/advising_functions.js"></script>
<footer>
</footer>
<div id="temp_hidden" class="temp_hidden"></div>
