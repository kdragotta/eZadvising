<?php

/**
 * Created by PhpStorm.
 * User: Victor
 * Date: 9/14/2015
 * Time: 7:25 PM
 */
class Professor
{
    private $name = "";
    private $dept = "";
    private $user = "";
    private $pass = "";
    private $courses = "";
    private $email = "";

    public function setName($name) { $this -> $name = $name; }
    public function setDept($dept) { $this -> $dept = $dept; }
    public function setUser($user) { $this -> $user = $user; }
    public function setPass($pass) { $this -> $pass = $pass; }
    public function setCourses($courses) { $this -> $courses = $courses; }
    public function setEmail($user) { $this -> $user = $user; }

    public function getName() { return $this -> $name; }
    public function getDept() { return $this -> $dept; }
    public function getUser() { return $this -> $user; }
    public function getPass() { return $this -> $pass; }
    public function getCourses() { return $this -> $courses; }
    public function getEmail() { return $this -> $email; }
}