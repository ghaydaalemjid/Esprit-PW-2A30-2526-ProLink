<?php
class Candidature {

    private $idProject;
    private $idUser;
    private $status;
//info cand

    private $nom;
    private $email;
    private $telephone;
    private $motivation;
    private $cv;
//constructeur  faire appele quand cree nv objet cand yjih 8 parametre
    public function __construct($idProject, $idUser, $status,
        $nom, $email, $telephone, $motivation, $cv) {

        $this->idProject = $idProject;
        $this->idUser = $idUser;
        $this->status = $status;
        $this->nom = $nom;
        $this->email = $email;
        $this->telephone = $telephone;
        $this->motivation = $motivation;
        $this->cv = $cv;
    }

    public function getProject(){ return $this->idProject; }
    public function getUser(){ return $this->idUser; }
    public function getStatus(){ return $this->status; }

    public function getNom(){ return $this->nom; }
    public function getEmail(){ return $this->email; }
    public function getTelephone(){ return $this->telephone; }
    public function getMotivation(){ return $this->motivation; }
    public function getCv(){ return $this->cv; }
}
?>