<?php

class Student {

    private $name = "";
    private $username = "";
    private $email = "";
    private $ezplan = 0;  //make obj
    private $coursesCompleted = 0; //makeobj

    function setName($name) {
         if(strlen($name) > 1)
              $this->name = $name;
    }

    function setEmail($email) {
        if(filter_var($email, FILTER_VALIDATE_EMAIL)
            $this->email = email;
    }

    function getName() {
        return $this->name;
    }

    //todo throw error
    function setUsername($username) {
        $MINSIZE = 3;
        $MAXSIZE = 15;
        if($username <= $MINSIZE)
            die();  //too short

        if($username > $MAXSIZE)
            die();  //too long

        $this->$username = $username;
    }

    function getEmail() {
        return $this->email;
    }

    function getUsername() {
        return $this->username;
    }
}

?>
