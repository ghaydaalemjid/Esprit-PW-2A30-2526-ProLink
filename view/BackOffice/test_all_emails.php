<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/email.php';

// Vérifier admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Accès non autorisé");
}

$pdo = getPDOConnection();

// Récupérer tous les emails des candidats
$sql = "SELECT DISTINCT u.email, u.nom 
        FROM candidatures c
        INNER JOIN users u ON c.user_id = u.id
        WHERE c.status = 'en_attente'";
        
$stmt = $pdo->prepare($sql);
$stmt->execute();
$candidats = $stmt->fetchAll();

echo "<h2>📧 Test d'envoi d'emails à tous les candidats</h2>";

foreach($candidats as $candidat) {
    echo "<p>Test pour: {$candidat['nom']} ({$candidat['email']})</p>";
    $resultat = envoyerEmailCandidature(
        $candidat['email'],
        $candidat['nom'],
        "Projet Test",
        'accepte',
        "Ceci est un test pour vérifier que votre email est bien configuré."
    );
    echo $resultat ? "✅ Email envoyé<br>" : "❌ Échec<br>";
    echo "<hr>";
}
?>