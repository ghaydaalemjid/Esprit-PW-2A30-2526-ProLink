<?php
require_once '../../config/config.php';
require_once '../../controller/NotificationC.php';

// Pour tester, on va utiliser l'utilisateur iduser = 1 (celui de ta base)
$user_id = 1;

$notifC = new NotificationC();

// Ajouter une notification de test
$notif = new Notification($user_id, 'test', '🔔 Ceci est une notification de test !', 'dashboard.php');
$notifC->create($notif);

// Afficher le nombre de notifications non lues
$count = $notifC->countUnread($user_id);
$notifications = $notifC->getUnread($user_id);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Test Notifications</title>
    <style>
        body { font-family: Arial; padding: 20px; background: #f5f5f5; }
        .container { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 10px; }
        .notif { background: #e3f2fd; padding: 10px; margin: 10px 0; border-radius: 5px; border-left: 4px solid #2196F3; }
        .count { background: #ff4444; color: white; padding: 5px 10px; border-radius: 20px; display: inline-block; }
        h1 { color: #333; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔔 Test des notifications</h1>
        
        <h2>Compteur : <span class="count"><?= $count ?></span> notification(s) non lue(s)</h2>
        
        <h3>Notifications non lues :</h3>
        <?php if(count($notifications) > 0): ?>
            <?php foreach($notifications as $notif): ?>
                <div class="notif">
                    <strong><?= htmlspecialchars($notif['message']) ?></strong><br>
                    <small>Type: <?= $notif['type'] ?> | Date: <?= $notif['created_at'] ?></small>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucune notification non lue.</p>
        <?php endif; ?>
        
        <hr>
        
        <form method="post" action="">
            <button type="submit" name="action" value="add" style="background:#4CAF50; color:white; padding:10px; border:none; border-radius:5px; cursor:pointer;">➕ Ajouter une notification de test</button>
            <button type="submit" name="action" value="mark_all" style="background:#2196F3; color:white; padding:10px; border:none; border-radius:5px; cursor:pointer;">✅ Tout marquer comme lu</button>
        </form>
        
        <?php
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            if($_POST['action'] === 'add') {
                $newNotif = new Notification($user_id, 'test', '🔔 Nouvelle notification à ' . date('H:i:s'), 'dashboard.php');
                $notifC->create($newNotif);
                echo "<p style='color:green;'>✅ Notification ajoutée !</p>";
                header('Refresh:0');
            }
            if($_POST['action'] === 'mark_all') {
                $notifC->markAllAsRead($user_id);
                echo "<p style='color:blue;'>✅ Toutes les notifications ont été marquées comme lues !</p>";
                header('Refresh:0');
            }
        }
        ?>
    </div>
</body>
</html>