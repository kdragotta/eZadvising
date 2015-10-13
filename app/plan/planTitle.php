<?php

require_once(__DIR__ . "/../config/config.php");

//$title = $_POST['title'];
$errorMessage;

if (isset($_POST['newTitle'])) {
    $title = strip_tags($_POST['newTitle']);

    try {
        $inserting = 'INSERT INTO plan_title (title) VALUES (:title)';
        $sqlInsert = $sql->prepare($inserting);
        $sqlInsert->bindvalue(':title', $title);
        $sqlInsert->execute();

    } catch (PDOException $e) {
        echo 'Error inserting plan title <br />ERROR MESSAGE:<br />' . $e->getMessage();
        exit();
    }
}

/*
// Update current title
try {
    $updating = "UPDATE plan_title
                  SET title = :title
                  WHERE ID = :ID";
    $sqlUpdate = $sql->prepare($updating);
    $sqlUpdate->bindvalue(':title', $title);
    $sqlUpdate->execute();
} catch (PDOException $e) {
    echo 'Error updating plan title <br />ERROR MESSAGE:<br />' . $e->getMessage();
    exit();
}
*/

/*
 * Checks to see if plan title exists
 */

/*
//Started on code to prepare for checking duplicates when creating new plan
if ($title == '') {
    try {
        $check = "SELECT * FROM plan_title WHERE title = :title";
        $sqlCheck = $sql->prepare($check);
        $sqlCheck->bindValue(':title', $title);
        $sqlCheck->execute();
        // Return number of rows if duplicate exists
        $count = $sqlCheck->rowCount();
        //If there are entries in the database, then concatenate the error message.
        if ($count > 0) {
            $errorMessage .= "<p>The title already exists.</p>";
        }
    } catch (PDOException $e) {
        echo 'Unable to fetch title to check for existing. <br />ERROR MESSAGE:<br />' . $e->getMessage();
        exit();
    }
} else {
    // Prepare title to be inserted into database
    try {
        $inserting = 'INSERT INTO plan_title (title) VALUES (:title)';
        $sqlInsert = $sql->prepare($inserting);
        $sqlInsert->bindvalue(':title', $title);
        $sqlInsert->execute();

    } catch (PDOException $e) {
        echo 'Error inserting plan title <br />ERROR MESSAGE:<br />' . $e->getMessage();
        exit();
    }
}
*/
?>