<?php

require_once(__DIR__ . "/../config/config.php");

class PlanModel
{
    private $conn = NULL;

    public function __construct()
    {
        $this->conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
    }

    public function createPlan($title, $plan)
    {
        try {
            $sql = 'INSERT INTO plan_title' .
                '(title, plan)' .
                'VALUES (:title, :plan)';

            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':plan', $plan);

            $stmt->execute();
        } catch (PDOException $e) {
            return 500;
        }
    }

    public function reloadPlans($title, $plan)
    {
        try {
            $sql = 'SELECT *' .
                '(title, plan)' .
                'FROM plan_title' .
                'WHERE (:title = title, :plan = plan)';

            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':plan', $plan);

            $stmt->execute();
        } catch (PDOException $e) {
            return 200;
        }
    }

    public function updatePlanTitle($id, $newTitle)
    {
        try {
            $sql = 'UPDATE plan_title ' .
                'SET title = :newTitle '.
                'WHERE id = :id';

            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':newTitle', $newTitle);

            $stmt->execute();
        } catch (PDOException $e) {
            return 500;
        }
    }
}

?>