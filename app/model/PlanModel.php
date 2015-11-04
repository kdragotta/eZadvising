<?php

require_once(__DIR__ . "/../config/config.php");

class PlanModel
{
    private $conn = NULL;

    public function __construct()
    {
        $this->conn = new PDO(DBCONNECTSTRING, DBUSER, DBPASSWORD);
    }

    public function createPlan($title, $plan, $color)
    {
        try {
            $sql = 'INSERT INTO saved_plans' .
                '(title, plan, color)' .
                'VALUES (:title, :plan, :color)';

            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':plan', $plan);
            $stmt->bindParam(':color', $color);

            $stmt->execute();
        } catch (PDOException $e) {
            return 500;
        }
    }

    public function reloadPlans($id)
    {
        try {
            $sql = 'SELECT title, color FROM saved_plans';

            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':id', $id);

            $stmt->execute();

            $result = $stmt->fetchAll();

            $jsonResult = json_encode($result);

            return $jsonResult;
        } catch (PDOException $e) {
            return 200;
        }
    }

    public function deletePlans($plan)
    {
        try {
            $sql = 'DELETE * FROM saved_plans WHERE plan = :plan';

            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':plan', $plan);

            $stmt->execute();
        } catch (PDOException $e) {
            return 500;
        }
    }

    public function updatePlanTitle($id, $newTitle)
    {
        try {
            $sql = 'UPDATE saved_plans SET title = :newTitle WHERE id = :id';

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