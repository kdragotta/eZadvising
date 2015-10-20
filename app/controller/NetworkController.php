<?php
require_once(__DIR__ . '/../model/NetworkModel.php');

/**
 * Created by PhpStorm.
 * User: Cameron Collins
 * Date: 10/20/2015
 * Time: 1:27 AM
 */
class NetworkController {
    private $networkModel = NULL;

    public function __construct() {
        $this->networkModel = new NetworkModel();
    }

    public function handleRequest() {
        $this->handleEmailPlan();
    }

    public function handleEmailPlan() {
        if(isset($_POST['fname']) && isset($_POST['lname'])) {
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
        } else {
            $fname = NULL;
            $lname = NULL;
        }

        if(!$fname || !$lname) {
            return;
        }

        echo $this->networkModel->emailPlan($fname, $lname);
    }
}