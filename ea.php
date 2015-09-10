<!DOCTYPE html>
<html>
<head>
    <title> eZAdvising </title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>


    <style>
        body {
            font-size: 100%; /*set base rem unit to 10*/
        }

        #left {
            background-color: #f3f2f2;
            width: 18%;
            float: left;
            position: relative;
            margin-right: .25%;
            padding: .5%

        }

        #col23 {
            background-color: white;
            width: 80%;
            float: right;
            position: relative;
            /*nesting slightly throws off margin and padding percentages-fix later */

        }

        #main {
            background-color: #f3f2f2;
            position: relative;
            float: left;
            width: 74%;
            padding: .5%
        }

        #right {
            background-color: #f3f2f2;
            position: relative;
            float: right;
            width: 23%;
            margin-left: .25%;
            padding: .5%

        }

        .center {
            margin-left: auto;
            margin-right: auto;
        }

        /* use a media query to stop at a minimum size */

        .course_box {
            background-color: #e0e0eb;
            border-style: solid;
            border-width: 1px;
            border-radius: 5px;
            padding: 2px;
        }

        span.required_courses {
            background-color: #e0e0eb;
            border-style: solid;
            border-width: 1px;
            border-radius: 5px;
            padding: 2px;

        }

        span.eligible_now {
            background-color: #b3b3bc;
            /* add more to make it stand out and add more gray out to the non eligible */
        }

        span.planned_courses {
            background-color: blue;
            border-style: solid;
            border-width: 1px;
            border-radius: 5px;
            padding: 2px;
            margin: 2px;
            display: block;

        }

        tr.space_under > td {
            padding-bottom: 10px;
        }

        span.completed_courses {
            background-color: #ebd6cc;
            border-style: solid;
            border-width: 1px;
            border-radius: 5px;
            padding: 2px;

        }

        th {
            padding-bottom: 10px;
        }

        div.semester_block header {
            background-color: lightgray;
            padding: 4px;
        }

        div.semester_block {
            background-color: white;
            height: 14rem;
            width: 10.25rem;
            border-style: dotted;
            border-width: 1px;
            border-radius: 5px;
            float: left;
            margin-right: 5px;
            margin-bottom: 5px;
        }

        div.semester_plan {
            height: 11rem;
            width: 10rem;

        }

        div.semester_plan.over {
            border: 2px dashed #000;
        }

    </style>
</head>

<body>
<header>
    <h2> eZAdvising </h2>
</header>
<div id="wrapper">

    <div id="left">
        <table>
            <tr>
                <th>Already Taken</th>
            </tr>
            <tr class="space_under">
                <td><span draggable="true" class="completed_courses" id="c104">CSCI 140</span></td>
            </tr>
            <tr class="space_under">
                <td><span draggable="true" class="completed_courses" id="c105">CSCI 140L</span></td>
            </tr>
            <tr class="space_under">
                <td><span draggable="true" class="completed_courses" id="c106">CSCI 225</span></td>
            </tr>

        </table>
    </div>

    <div id="col23">
        <div id="right">

            <table id="required_list">
                <tr>
                    <th>Need to Take</th>
                </tr>


                <tr class="space_under">
                    <td><span draggable="true" class="required_courses" id="c101">CSCI 150</span></td>
                </tr>
                <tr class="space_under">
                    <td><span draggable="true" class="required_courses" id="c102">CSCI 150L</span></td>
                </tr>
                <tr class="space_under">
                    <td><span draggable="true" class="required_courses eligible_now" id="c103">CSCI 203</span></td>
                </tr>

            </table>
            <!--
            <span class="required_courses" id=101>CSCI 150</span>
            <span class="required_courses" id=102>CSCI 150L</span>
            <span class="required_courses" id=103>CSCI 203</span>
            -->
        </div>

        <div id="main">
            <table>
                <tr>
                    <th>Plan</th>
                </tr>

                <tr></tr>
            </table>

            <div class="semester_block">
                <header>Fall 2015</header>
                <div class="semester_plan" id="fall2015"></div>
            </div>

            <div class="semester_block">
                <header>Spring 2016</header>
                <div class="semester_plan" id="spring2016"></div>
            </div>

            <div class="semester_block">
                <header>Summer 2016</header>
                <div class="semester_plan" id="summer2016"></div>
            </div>

            <div class="semester_block">
                <header>Fall 2016</header>
                <div class="semester_plan" id="fall2016"></div>
            </div>

            <div class="semester_block">
                <header>Spring 2016</header>
                <div class="semester_plan" id="spring2017"></div>
            </div>

            <div class="semester_block">
                <header>Summer 2017</header>
                <div class="semester_plan" id="summer2017"></div>
            </div>
        </div>
        <!-- end div main -->

    </div>
    <!-- end div col23 -->


</div>
<!-- end div wrapper -->

<footer>
</footer>
<script>
    //alert("running");

    $(document).ready(function () {

        var dragSrcEl = null;
        var plan; //array of semesters each with array of courses
        var taken //array of courses
        var required //array of courses
//determine drag behavior and element id behavior

        function handleDragStart(e) {
            this.style.opacity = '0.4';  // this / e.target is the source node.
            dragSrcEl = this;
            // alert(dragSrcEl.innerHTML);

            e.dataTransfer.effectAllowed = 'move';
            //to get the whole tag, not just innerhtml:
            var theResult = $('<div />').append($(this).clone()).html();
            var result = $.parseHTML(theResult);
            $(result).removeAttr("style");
            $(result).removeClass("required_courses");
            $(result).addClass("planned_courses");
            var oldId = $(result).attr('id');
            var newId = "p" + oldId.substr(1);
            $(result).attr("id", newId);
            console.log(newId);
            theResult = $(result)[0].outerHTML;
            //theResult.
            // alert(theResult);

            e.dataTransfer.setData('text/html', theResult);
        }

        var cols = document.querySelectorAll('span.required_courses');

        [].forEach.call(cols, function (col) {
            col.addEventListener('dragstart', handleDragStart, false);
        });


        function handleDragOver(e) {
            if (e.preventDefault) {
                e.preventDefault(); // Necessary. Allows us to drop.
            }

            e.dataTransfer.dropEffect = 'move';  // See the section on the DataTransfer object.

            return false;
        }

        function handleDragEnter(e) {
            // this / e.target is the current hover target.
            this.classList.add('over');
        }

        function handleDragLeave(e) {
            this.classList.remove('over');  // this / e.target is previous target element.
        }

        function handleDrop(e) {
            // this / e.target is current target element.

            if (e.stopPropagation) {
                e.stopPropagation(); // stops the browser from redirecting.
            }


            // See the section on the DataTransfer object.
            // Don't do anything if dropping the same column we're dragging.
            if (dragSrcEl != this) {
                // Set the source column's HTML to the HTML of the column we dropped on.
                // dragSrcEl.innerHTML = this.innerHTML;
                //  this.innerHTML = e.dataTransfer.getData('text/html');
                $(this).append(e.dataTransfer.getData('text/html'));
            }
//alert("finished drop");
//if successful, disable further drag of source
            $(dragSrcEl).attr("draggable", "false");
            return false;
        }

        function handleDragEnd(e) {
            // this/e.target is the source node.

            [].forEach.call(cols, function (col) {
                col.classList.remove('over');
            });
        }

        var boxes = document.querySelectorAll('#main div.semester_plan');

        [].forEach.call(boxes, function (col) {
            //col.addEventListener('dragstart', handleDragStart, false);
            col.addEventListener('dragenter', handleDragEnter, false);
            col.addEventListener('dragover', handleDragOver, false);
            col.addEventListener('dragleave', handleDragLeave, false);
            col.addEventListener('drop', handleDrop, false);
            col.addEventListener('dragend', handleDragEnd, false);
        });


    }); //end document.ready

</script>
