<?php
require_once(__DIR__."/../config/config.php");

/**
 * Created by PhpStorm.
 * User: Cameron Collins
 * Date: 10/20/2015
 * Time: 1:27 AM
 */
class NetworkModel {
    private $conn = NULL;

    public function __construct() {
        $this->conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
    }

    public function emailPlan($fname, $lname) {
        $sql = 'SELECT email FROM accounts WHERE fname=:fname AND lname=:lname';
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':fname', $fname);
            $stmt->bindParam(':lname', $lname);
            $stmt->execute();
            $email = $stmt->fetch();
            echo $email;
        } catch(PDOException $e) {
            return $sql . "<br>" . $e->getMessage();
        }
    }
}