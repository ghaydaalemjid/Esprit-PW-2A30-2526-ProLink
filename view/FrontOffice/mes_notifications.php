<?php
session_start();
require_once '../../controller/NotificationC.php';

// Vérifier si l'utilisateur est connecté
if(!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user']['iduser'];
$notifC = new NotificationC();

// Marquer toutes comme lues si demandé
if(isset($_GET['mark_all'])) {
    $notifC->markAllAsRead($user_id);
    header('Location: mes_notifications.php');
    exit();
}

$notifications = $notifC->getAll($user_id, 50);
$unreadCount = $notifC->countUnread($user_id);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes notifications - ProLink</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f7fa; }
        
        .navbar {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo { font-size: 24px; font-weight: bold; color: #0077b6; }
        .nav-links a {
            margin-left: 20px;
            text-decoration: none;
            color: #333;
        }
        
        .container { max-width: 800px; margin: 40px auto; padding: 20px; }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }
        h1 { color: #333; font-size: 28px; }
        .unread-badge {
            background: #ff4444;
            color: white;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 14px;
        }
        .mark-all {
            background: #2196F3;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
        }
        .notif-list {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .notif-item {
            padding: 18px 20px;
            border-bottom: 1px solid #eee;
        }
        .notif-item.unread {
            background: #e3f2fd;
            border-left: 4px solid #2196F3;
        }
        .notif-message { font-size: 14px; color: #333; margin-bottom: 8px; }
        .notif-date { font-size: 12px; color: #999; }
        .empty { text-align: center; padding: 60px 20px; color: #999; }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #0077b6;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="logo">ProLink</div>
    <div class="nav-links">
        <a href="home.php">Accueil</a>
        <a href="projects.php">Projets</a>
        <a href="mes_notifications.php" style="font-weight: bold; color:#0077b6;">🔔 Mes notifications</a>
        <a href="logout.php">Se déconnecter</a>
    </div>
</div>

<div class="container">
    <div class="header">
        <h1>
            🔔 Mes notifications
            <?php if($unreadCount > 0): ?>
                <span class="unread-badge"><?= $unreadCount ?> non lue(s)</span>
            <?php endif; ?>
        </h1>
        
        <?php if($unreadCount > 0): ?>
            <a href="?mark_all=1" class="mark-all">✅ Tout marquer comme lu</a>
        <?php endif; ?>
    </div>
    
    <div class="notif-list">
        <?php if(count($notifications) > 0): ?>
            <?php foreach($notifications as $notif): ?>
                <div class="notif-item <?= $notif['is_read'] == 0 ? 'unread' : '' ?>">
                    <div class="notif-message"><?= htmlspecialchars($notif['message']) ?></div>
                    <div class="notif-date">
                        📅 <?= date('d/m/Y à H:i', strtotime($notif['created_at'])) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="empty">
                📭 Aucune notification pour le moment
            </div>
        <?php endif; ?>
    </div>
    
    <a href="home.php" class="back-link">← Retour à l'accueil</a>
</div>

</body>
</html>