<?php
/**
 * Created by IntelliJ IDEA.
 * User: Billy
 * Date: 9/22/15
 * Time: 1:03 PM
 */

require_once "config.php";
require_once "planTitle.js";

$errormsg = "";
$showform = 1;

if (isset($_POST['submit'])) {
    // Cleanse title on submission
    $formfield['title'] = htmlspecialcharacters(stripslashes(trim($_POST['title'])));

    // Check if title name is empty
    if (empty($formfield['title'])) {
        $errormsg .= "<p>Title is empty</p>";
    }

    // Check for duplicate title
    if($formfield['title'] != $_POST['origtitle']) {
        try {
            // Pulls titles from the database
            $sqltitle = "SELECT * FROM ezadvising WHERE title = :title";

            $stmt = $pdo->prepare($sqltitle);
        }
    }
}