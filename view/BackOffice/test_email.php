<?php
require_once __DIR__ . '/../config/email.php';

// Test simple
$email_test = "votre-email@gmail.com"; // Mettez votre email ici
$nom_test = "Utilisateur Test";
$projet_test = "Projet Démo";

echo "<h2>Test d'envoi d'email - ProLink</h2>";

// Test acceptation
echo "<h3>Test 1: Email d'acceptation</h3>";
$resultat1 = envoyerEmailCandidature($email_test, $nom_test, $projet_test, 'accepte', "Félicitations ! Vous êtes accepté(e) sur ce projet.");

if($resultat1) {
    echo "✅ Email d'acceptation envoyé avec succès à $email_test<br>";
} else {
    echo "❌ Erreur lors de l'envoi de l'email d'acceptation<br>";
}

echo "<br>";

// Test refus
echo "<h3>Test 2: Email de refus</h3>";
$resultat2 = envoyerEmailCandidature($email_test, $nom_test, $projet_test, 'refuse', "Nous vous remercions pour votre candidature.");

if($resultat2) {
    echo "✅ Email de refus envoyé avec succès à $email_test<br>";
} else {
    echo "❌ Erreur lors de l'envoi de l'email de refus<br>";
}
?>