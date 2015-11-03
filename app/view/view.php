<!DOCTYPE html>
<html>
<header>
    <!-- General Imports-->
    <script src="app/view/js/lib/jquery.min.js"></script>
    <script src="app/view/js/lib/jquery-ui.min.js"></script>
    <script src="app/view/js/lib/bootstrap.min.js"></script>
    <script src="app/view/js/lib/jquery-simulate.js"></script>

    <link rel="stylesheet" href="app/view/css/lib/jquery-ui.css">
    <link rel="stylesheet" href="app/view/css/lib/bootstrap.min.css">
    <!-- End General Imports -->

    <!-- Custom Styling -->
    <link rel="stylesheet" type="text/css" href="app/view/css/styles.css">
    <link rel="stylesheet" type="text/css" href="app/view/css/popup.css">
    <!-- End Custom Styling -->

    <div id="top" class="top">
        <h3> eZAdvising </h3>
    </div>
</header>

<nav>
    <!-- Nav Bar Tabs -->
    <div id="pills">
        <ul class="nav nav-pills">
            <li class="planpill active" id="pill0"><a href="#plan0" data-toggle="pill">Default</a></li>
            <li class="planpill" id="pill1" onclick="AddTitle()"><a href="#plan1" data-toggle="pill"><span
                        class="glyphicon glyphicon-plus"></span></a></li>
        </ul>
    </div>
    <!-- End Nav Bar Tabs -->
</nav>

<!-- Bootstrap Form -->
<div id="modal" class="modal fade" role="dialog" data-keyboard="false">
    <div class="vertical-alignment-helper">
        <div class="modal-dialog">
            <!-- Content -->
            <div class="modal-content" id="form-content">
                <div class="modal-header">
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
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="closeModal">Close</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="addPill">Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Bootstrap Form -->

<body>
<!-- Div Wrapper -->
<div class="tab-content">
    <div id="plan0" class="tab-pane fade in active">
        <div id="wrapper">
            <!-- Div Left -->
            <div id="left">
                <table>
                    <tr>
                        <th>Classes Selected</th>
                    </tr>
                </table>
                <div id="currentState0"></div>
            </div>
            <!-- End Div Left -->

            <!-- Div Main -->
            <div id="main">
                <tr>
                    <td>
                        <h4>
                            <button onclick="RenameTab()">Change Plan Name</button>
                            <button data-show="on" onclick="showHideSummers()"> Show/Hide Summers</button>
                        </h4>
                    </td>
                </tr>
                <div id="thePlan0"></div>
            </div>
            <!-- End Div Main -->

            <!-- Div Right -->
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

                <div id="stillRequiredList0"></div>
            </div>
            <!-- End Div Right -->
        </div>
    </div>
</div>
<!-- End Div Wrapper -->
</body>

<!-- Custom Scripts -->
<script src="app/view/js/AdvisingFunctions.js"></script>
<script src="app/view/js/ClassBox.js"></script>
<script src="app/view/js/NavBar.js"></script>
<!-- End Custom Scripts -->

<footer>
</footer>
<div id="temp_hidden" class="temp_hidden"></div>
</html>