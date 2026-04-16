<?php
class Projet {
    private $nom;
    private $description;
    private $date_debut;
    private $date_fin;
    private $statut;

    public function __construct($nom, $description, $date_debut, $date_fin, $statut) {
        $this->nom = $nom;
        $this->description = $description;
        $this->date_debut = $date_debut;
        $this->date_fin = $date_fin;
        $this->statut = $statut;
    }

    public function getNom() {
        return $this->nom;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getDateDebut() {
        return $this->date_debut;
    }

    public function getDateFin() {
        return $this->date_fin;
    }

    public function getStatut() {
        return $this->statut;
    }
}
?>