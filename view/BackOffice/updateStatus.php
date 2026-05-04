<?php
require_once('../../controller/CandidatureC.php');
require_once('../../controller/NotificationC.php');
require_once('../../config/config.php');

// Démarrer la session si besoin
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si les paramètres existent
if(isset($_GET['id']) && isset($_GET['s'])) {
    $idCandidature = $_GET['id'];
    $nouveauStatut = $_GET['s']; // accepte ou refuse
    
    // Récupérer les infos de la candidature
    $cc = new CandidatureC();
    $candidature = $cc->getCandidatureById($idCandidature);
    
    if($candidature && isset($candidature['idUser']) && $candidature['idUser'] > 0) {
        // Mettre à jour le statut
        $cc->updateStatus($idCandidature, $nouveauStatut);
        
        // =============================================
        // AJOUTER UNE NOTIFICATION POUR LE CANDIDAT
        // =============================================
        $notifC = new NotificationC();
        
        if($nouveauStatut == 'accepte') {
            $message = "✅ Félicitations ! Votre candidature pour le projet \"{$candidature['title']}\" a été ACCEPTÉE.";
            $type = "accepte";
        } else {
            $message = "❌ Votre candidature pour le projet \"{$candidature['title']}\" a été REFUSÉE.";
            $type = "refuse";
        }
        
        // Envoyer la notification au candidat
        $notif = new Notification(
            (int)$candidature['idUser'],
            $type,
            $message,
            '../FrontOffice/mes_notifications.php'
        );
        $notifC->create($notif);
        
        $_SESSION['success'] = "✅ Statut mis à jour et notification envoyée au candidat !";
        
    } else {
        $_SESSION['error'] = "❌ Candidature non trouvée ou ID candidat invalide";
    }
}

// Redirection vers la liste
header('Location: listCandidatures.php');
exit();
?>