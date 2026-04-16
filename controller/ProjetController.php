<?php
include_once __DIR__ . "/../config/connexion.php";
include_once __DIR__ . "/../model/Projet.php";

class ProjetController {

    public function ajouter($projet) {
        $sql = "INSERT INTO projet (nom, description, date_debut, date_fin, statut)
                VALUES (:nom, :description, :date_debut, :date_fin, :statut)";

        $db = config::getConnexion();
        $query = $db->prepare($sql);

        $query->execute([
            'nom' => $projet->getNom(),
            'description' => $projet->getDescription(),
            'date_debut' => $projet->getDateDebut(),
            'date_fin' => $projet->getDateFin(),
            'statut' => $projet->getStatut()
        ]);
    }

    // ✅ UNE SEULE FOIS
    public function afficher() {
        $sql = "SELECT * FROM projet";
        $db = config::getConnexion();
        return $db->query($sql);
    }

    public function supprimer($id) {
        $sql = "DELETE FROM projet WHERE id = :id";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        $query->execute(['id' => $id]);
    }

    public function recuperer($id) {
        $sql = "SELECT * FROM projet WHERE id = :id";
        $db = config::getConnexion();
        $query = $db->prepare($sql);
        $query->execute(['id' => $id]);
        return $query->fetch();
    }

    public function modifier($projet, $id) {
        $sql = "UPDATE projet SET 
                nom = :nom,
                description = :description,
                date_debut = :date_debut,
                date_fin = :date_fin,
                statut = :statut
                WHERE id = :id";

        $db = config::getConnexion();
        $query = $db->prepare($sql);

        $query->execute([
            'id' => $id,
            'nom' => $projet->getNom(),
            'description' => $projet->getDescription(),
            'date_debut' => $projet->getDateDebut(),
            'date_fin' => $projet->getDateFin(),
            'statut' => $projet->getStatut()
        ]);
    }
    public function countAll() {
    $sql = "SELECT COUNT(*) as total FROM projet";
    $db = config::getConnexion();
    return $db->query($sql)->fetch();
}

public function countByStatut($statut) {
    $sql = "SELECT COUNT(*) as total FROM projet WHERE statut = :statut";
    $db = config::getConnexion();
    $query = $db->prepare($sql);
    $query->execute(['statut' => $statut]);
    return $query->fetch();
}
}
?>