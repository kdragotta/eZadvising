<html>

    <head>
        <link rel="stylesheet" type="text/css" href="addCourse.css">
    </head>

    <header>
        Add Course
    </header>

    <?php

    $servername = "localhost";
    $username = "advising";
    $password = "adv123";
    $dbname = "ezadvising";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $dept = $_POST['nondept'];

    $sql = "SELECT * FROM `courses` WHERE `dept` LIKE '";
    $sql .= $dept . "'";

    /*$sql = "SELECT * FROM `courses`";*/
    $result = $conn->query($sql);

    echo "<body>";

    echo "<table>";
    echo "<col width='100'>
          <col width='100'>
          <col width='160'>";
    echo "<th>Add Course</th>";
    echo "<th>Credit Hours</th>";
    echo "<th>Department</th>";
    echo "<th>Course</th>";
    echo "<th>Title</th>";

    echo "<form action = eatouch4.php method='post'>";

    if($result->num_rows > 0)
    {
        while($row = $result->fetch_assoc())
        {
            echo "<tr>";

            echo "<td>";
                echo "<input type='checkbox' class = 'check' name = 'addc' value = '" . $row["dept"] . " " . $row["num"] . "'/>";
            echo "</td>";


            echo "<td class='center'>" . $row["defaultCreditHours"] . "</td>";
            echo "<td class='center'>" . $row["dept"] . "</tc>";
            echo "<td>" . $row["num"] . "</td>";
            echo "<td>" . $row["title"] . "</td>";

            echo "</tr>";

        }
    }
    echo "</table>";

    echo "<input type='submit' value='Submit' class='sub'/>";

    echo "</form>";

    echo "</body>";
    $conn->close();

    ?>
</html>





