<?php

require_once(__DIR__ . "/../config/config.php");

class PlanModel
{
    private $conn = NULL;

    public function __construct()
    {
        $this->conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
    }

    public function createPlan($title)
    {
        try {
            $sql = 'INSERT INTO plan_title'.
                '(title)'.
                'VALUES (:title)';

            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':title', $title);

            $stmt->execute();
        } catch (PDOException $e) {
            return 500;
        }
    }
}

?>