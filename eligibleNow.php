<?php
echo 'This will be the eligible now feature/function<br>';
$domain = 'mysql:dbname=ezadvising;host=localhost';
$user = 'advising';
$password = 'adv123';

try {
    $conn = new PDO($domain, $user, $password);
    //echo "Connection was good.<br>";
} catch (PDOException $e) {
    echo $user . " :: " . $password . "\n";
    echo 'Connection failed: ' . $e->getMessage();
}

echo "<select id = 's1'>";
//echo "<option>Select Department</option>";
$courseInfo = "CSCI";
echo "<option >CSCI</option>";
echo "<option>ENGL</option>";
echo "</select>";
class TableRows extends RecursiveIteratorIterator {
    function __construct($it) {
        parent::__construct($it, self::LEAVES_ONLY);
    }
    function beginChildren() {
        echo "<option>";
    }
    function endChildren() {
        echo "</option>";
    }
}
echo "<select>";
echo "<option>Select Course</option>";
$stmt = $conn->prepare("SELECT title FROM courses WHERE dept = 'document.getElementById('s1').value'");
$stmt->execute();

$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) {
    echo $v;
}
echo "</select>";



?>