<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../model/Notification.php';
require_once __DIR__ . '/../controller/NotificationC.php';

class AuthController {

    // 🔹 LOGIN
    public function login($email, $mdp) {
        $sql = "SELECT * FROM user WHERE email = :email";
        $db = Config::getConnexion();

        $query = $db->prepare($sql);
        $query->execute(['email' => $email]);
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($mdp, $user['mdp'])) {
            if (session_status() !== PHP_SESSION_ACTIVE) {
                session_start();
            }
            $_SESSION['user'] = $user;
            return $user;
        }

        return null;
    }

    // 🔹 REGISTER (AVEC NOTIFICATIONS)
    public function register($user) {
        $sql = "INSERT INTO user (nom, prenom, email, mdp, type, age)
                VALUES (:nom, :prenom, :email, :mdp, :type, :age)";
        $db = Config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->execute([
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom(),
                'email' => $user->getEmail(),
                'mdp' => password_hash($user->getMdp(), PASSWORD_DEFAULT),
                'type' => $user->getType(),
                'age' => $user->getAge()
            ]);
            
            // Récupérer l'ID du nouvel utilisateur
            $newUserId = $db->lastInsertId();
            
            // =============================================
            // NOTIFICATION DE BIENVENUE POUR LE NOUVEAU CANDIDAT
            // =============================================
            $notifC = new NotificationC();
            
            // Message de bienvenue
            $messageBienvenue = "🎉 Bienvenue sur ProLink " . $user->getPrenom() . " ! Postulez à des projets et recevez des notifications en temps réel.";
            $notif = new Notification($newUserId, 'bienvenue', $messageBienvenue, 'FrontOffice/projects.php');
            $notifC->create($notif);
            
            // =============================================
            // NOTIFICATION POUR L'ADMIN (nouvel inscrit)
            // =============================================
            $stmt = $db->prepare("SELECT iduser FROM user WHERE type = 'admin'");
            $stmt->execute();
            $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach($admins as $admin) {
                $messageAdmin = "🆕 Nouvel inscrit : {$user->getNom()} {$user->getPrenom()} ({$user->getEmail()})";
                $notifAdmin = new Notification($admin['iduser'], 'new_user', $messageAdmin, '../BackOffice/listUsers.php');
                $notifC->create($notifAdmin);
            }
            
            return $newUserId;
            
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

    // 🔹 PROFILE
    public function profile() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        return $_SESSION['user'] ?? null;
    }

    // 🔹 LOGOUT
    public function logout() {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        session_destroy();
    }
}
?>