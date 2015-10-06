<?php

require_once(__DIR__ . "/../config/config.php");

class planTitle
{
    private $conn = NULL;

    public function __construct()
    {
        $this->conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
    }

    public function addTitle($planTitle)
    {
        try {
            $sql = 'INSERT INTO plan_title (planTitle) ';
            $sql = $sql . ' VALUES (:planTitle)';
            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':planTitle', $planTitle);
            $stmt->execute();
        }
        catch (PDOException $e) {
            return 500;
        }
    }
}

?>