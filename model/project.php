<?php
class Project {
    private $idProject;
    private $title;
    private $description;
    private $status;

    public function __construct($title, $description, $status) {
        $this->title = $title;
        $this->description = $description;
        $this->status = $status;
    }

    public function getTitle(){ return $this->title; }
    public function getDescription(){ return $this->description; }
    public function getStatus(){ return $this->status; }
}
?>