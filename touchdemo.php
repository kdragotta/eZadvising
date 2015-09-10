<!doctype html>
<html lang="en">
<head>

    <style>
        #makeMeDraggable {
            float: left;
            width: 300px;
            height: 300px;
            background: red;
        }

        #makeMeDroppable {
            float: right;
            width: 300px;
            height: 300px;
            border: 1px solid #999;
        }
    </style>

    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.9/jquery-ui.min.js"></script>

    <script type="text/javascript">

        $(init);

        function init() {
            $('#makeMeDraggable').draggable();
            $('#makeMeDroppable').droppable({
                drop: handleDropEvent
            });
        }

        function handleDropEvent(event, ui) {
            var draggable = ui.draggable;
            alert('The square with ID "' + draggable.attr('id') + '" was dropped onto me!');
        }

    </script>

</head>
<body>

<div id="content" style="height: 400px;">

    <div id="makeMeDraggable"></div>
    <div id="makeMeDroppable"></div>

</div>

</body>
</html>