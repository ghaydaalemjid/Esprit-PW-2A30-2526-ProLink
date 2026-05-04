<?php
require_once "User.php";
//clase candidate jeya men user
class Candidat extends User {
    public function getType() {
        return "candidat";
    }
}
