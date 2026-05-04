<?php
require_once(__DIR__ . '/../config/config.php');

// Debug temporaire
if (!file_exists(__DIR__ . '/../config/config.php')) {
    die('CONFIG INTROUVABLE');
}

class CandidatureC {

    // Récupère TOUTES les candidatures avec le nom du projet
    function listCandidatures() {
        $sql = "SELECT c.*, p.title 
                FROM candidature c
                JOIN project p ON c.idProject = p.idProject";
        $db = config::getConnexion();
        return $db->query($sql);
    }

    // Récupérer une candidature par son ID (CORRIGÉ)
    function getCandidatureById($id) {
        $sql = "SELECT 
                    c.idCandidature,
                    c.idProject,
                    c.idUser,
                    c.status,
                    c.nom,
                    c.email,
                    c.telephone,
                    c.motivation,
                    c.cv,
                    c.date_postulation,
                    p.title
                FROM candidature c
                JOIN project p ON c.idProject = p.idProject
                WHERE c.idCandidature = ?";
        $db = config::getConnexion();
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update status (accepte/refuse)
    function updateStatus($id, $status) {
        $sql = "UPDATE candidature SET status=? WHERE idCandidature=?";
        $db = config::getConnexion();
        $stmt = $db->prepare($sql);
        $stmt->execute([$status, $id]);
        return $stmt->rowCount() > 0;
    }

    // Ajouter une candidature
    function addCandidature($c){
        $sql = "INSERT INTO candidature 
        (idProject, idUser, status, nom, email, telephone, motivation, cv)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $db = Config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute([
                $c->getProject(),
                $c->getUser(),
                $c->getStatus(),
                $c->getNom(),
                $c->getEmail(),
                $c->getTelephone(),
                $c->getMotivation(),
                $c->getCv()
            ]);
            
            // =============================================
            // NOTIFICATION : Un candidat a postulé
            // ENVOYER À L'ADMIN
            // =============================================
            require_once(__DIR__ . '/NotificationC.php');
            
            // Récupérer le titre du projet
            $stmt = $db->prepare("SELECT title FROM project WHERE idProject = ?");
            $stmt->execute([$c->getProject()]);
            $project = $stmt->fetch(PDO::FETCH_ASSOC);
            $projectTitle = $project ? $project['title'] : 'un projet';
            
            // Récupérer tous les admins
            $stmt = $db->prepare("SELECT iduser FROM user WHERE type = 'admin'");
            $stmt->execute();
            $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $notifC = new NotificationC();
            
            // Envoyer une notification à chaque admin
            foreach($admins as $admin) {
                $message = "📝 Nouvelle candidature de \"{$c->getNom()}\" pour le projet \"{$projectTitle}\"";
                $notif = new Notification(
                    $admin['iduser'],
                    'candidature_recue',
                    $message,
                    '../BackOffice/listCandidatures.php'
                );
                $notifC->create($notif);
            }
            
            return true;
            
        } catch (Exception $e){
            die('Erreur: '.$e->getMessage());
        }
    }

    // Vérifie si un candidat a déjà postulé à un projet
    function alreadyApplied($idUser, $idProject){
        $sql = "SELECT * FROM candidature 
                WHERE idUser = ? AND idProject = ?";

        $db = config::getConnexion();
        $query = $db->prepare($sql);
        $query->execute([$idUser, $idProject]);

        return $query->rowCount() > 0;
    }
}
?>