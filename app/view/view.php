<!DOCTYPE html>
<html>
<header>
    <!-- General Imports-->
    <script src="app/view/js/lib/jquery.min.js"></script>
    <script src="app/view/js/lib/jquery-ui.min.js"></script>
    <script src="app/view/js/lib/bootstrap.min.js"></script>
    <script src="app/view/js/lib/jquery-simulate.js"></script>

    <!-- <link rel="stylesheet" href="app/view/css/lib/jquery-ui.css"> -->
    <link rel="stylesheet" href="app/view/css/lib/bootstrap.min.css">
    <!-- End General Imports -->

    <!-- Custom Styling -->
    <link rel="stylesheet" href="app/view/css/navigation.css">
    <link rel="stylesheet" href="app/view/css/popup.css">
    <link rel="stylesheet" href="app/view/css/styles.css">
    <!-- End Custom Styling -->

    <div id="top" class="top">
        <h3> eZAdvising </h3>
    </div>
</header>
<!-- Nav Bar Tabs -->
<nav class="navWrapper">
    <div id="leftNav">
        <div id="pills">
            <ul class="nav nav-pills">
                <li class="planpill active" id="pill0"><a href="#plan0" data-toggle="pill" id="hover0"></a></li>
            </ul>
        </div>
    </div>
    <div id="rightNav">
        <div class="dropdown">
            <a class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                Menu
                <span class="icon-bars-button">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </span>
            </a>
            <ul class="dropdown-menu">
                <li><a onclick="RenameTab();">Change Plan Name</a></li>
                <li><a onclick="DeletePlan();">Delete Current Plan</a></li>
                <li role="separator" class="divider"></li>
                <li><a data-show="on" onclick="showHideSummers()">Show / Hide Summers</a></li>
            </ul>
        </div>
    </div>
</nav>
<!-- End Nav Bar Tabs -->

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
                            <input type="text" class="form-control" id="title" value=" ">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="closeModal">Close</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="addPill">Submit</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Bootstrap Form -->

<body>
<!-- Div Wrapper -->
<div class="tab-content">
    <div id="plan0" class="tab-pane in active">
        <div id="wrapper">
            <!-- Div Left -->
            <div id="left">
                <table>
                    <tr>
                        <th class="planHeader">Classes Selected</th>
                    </tr>
                </table>
                <div id="currentState0"></div>
            </div>
            <!-- End Div Left -->

            <!-- Div Main -->
            <div id="main">
                <!-- Need this here for some reason... without it there are plans on the right bar -->
                <button type="button" data-show="on" onclick="showHideSummers();" hidden>Submit</button>
                <div id="thePlan0"></div>
            </div>
            <!-- End Div Main -->

            <!-- Div Right -->
            <div class="target" id="right">

                <table id="required_table">
                    <tr>
                        <th class="planHeader">Need to Take</th>
                    </tr>
                </table>

                <div id="eligibleSwitch">
                    <input type="checkbox" id="semCheckBox"/>
                    <span>Highlight Courses Eligible </span>
                    <select id="semList0"></select>
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