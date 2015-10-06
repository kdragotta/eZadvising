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
            $sql = 'INSERT INTO plan_title (plan_title) ';
            $sql = $sql . ' VALUES (:planTitle)';
            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':planTitle', $plan_title);
            $stmt->execute();
        }
        catch (PDOException $e) {
            return 500;
        }
    }
}

?>