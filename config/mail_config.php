<?php
// Fonction pour envoyer des emails
function envoyerEmailCandidature($email_destinataire, $nom_candidat, $projet_nom, $statut, $message_perso = '') {
    
    if (!filter_var($email_destinataire, FILTER_VALIDATE_EMAIL)) {
        error_log("Email invalide: $email_destinataire");
        return false;
    }
    
    $sujet = ($statut == 'accepte') ? "✅ Félicitations ! Votre candidature a été acceptée - ProLink" : "📧 Mise à jour de votre candidature - ProLink";
    
    if ($statut == 'accepte') {
        $couleur = "#4CAF50";
        $icone = "✅";
        $titre = "ACCEPTÉE";
        $message_defaut = "Nous avons le plaisir de vous informer que votre candidature pour le projet <strong>$projet_nom</strong> a été acceptée !";
    } else {
        $couleur = "#f44336";
        $icone = "ℹ️";
        $titre = "NON RETENUE";
        $message_defaut = "Nous vous remercions d'avoir postulé au projet <strong>$projet_nom</strong>. Votre candidature n'a pas été retenue cette fois-ci.";
    }
    
    $message_final = !empty($message_perso) ? $message_perso : $message_defaut;
    
    $html = "
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset='UTF-8'>
        <title>Résultat candidature - ProLink</title>
        <style>
            body { font-family: Arial, sans-serif; background: #f4f7fa; margin: 0; padding: 20px; }
            .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 10px; overflow: hidden; }
            .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 30px; text-align: center; color: white; }
            .content { padding: 30px; }
            .badge { display: inline-block; background: $couleur; color: white; padding: 8px 20px; border-radius: 20px; margin-bottom: 20px; }
            .message { background: #f8f9fa; padding: 15px; border-left: 4px solid $couleur; margin: 20px 0; }
            .footer { background: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #666; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>🏢 ProLink</h1>
            </div>
            <div class='content'>
                <div style='text-align: center;'>
                    <div class='badge'>$icone $titre</div>
                </div>
                <h3>Bonjour $nom_candidat,</h3>
                <div class='message'>
                    $message_final
                </div>
                <p>Cordialement,<br>L'équipe ProLink</p>
            </div>
            <div class='footer'>
                <p>© 2024 ProLink - Cet email est automatique</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";
    $headers .= "From: ProLink <no-reply@prolink.com>\r\n";
    
    return mail($email_destinataire, $sujet, $html, $headers);
}
?>