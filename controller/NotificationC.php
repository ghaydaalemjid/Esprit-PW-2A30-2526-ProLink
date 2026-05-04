<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../model/Notification.php';

class NotificationC {

    // Créer une notification
    public function create($notification) {
        $sql = "INSERT INTO notification (id_user, type, message, lien, is_read) 
                VALUES (?, ?, ?, ?, ?)";
        $db = Config::getConnexion();
        $stmt = $db->prepare($sql);
        $stmt->execute([
            $notification->getIdUser(),
            $notification->getType(),
            $notification->getMessage(),
            $notification->getLien(),
            $notification->getIsRead()
        ]);
        return $db->lastInsertId();
    }

    // Récupérer les notifications non lues
    public function getUnread($id_user) {
        $sql = "SELECT * FROM notification WHERE id_user = ? AND is_read = 0 
                ORDER BY created_at DESC";
        $db = Config::getConnexion();
        $stmt = $db->prepare($sql);
        $stmt->execute([$id_user]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer toutes les notifications (avec limite)
    public function getAll($id_user, $limit = 20) {
        $sql = "SELECT * FROM notification WHERE id_user = ? 
                ORDER BY created_at DESC LIMIT " . intval($limit);
        $db = Config::getConnexion();
        $stmt = $db->prepare($sql);
        $stmt->execute([$id_user]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Compter les notifications non lues
    public function countUnread($id_user) {
        $sql = "SELECT COUNT(*) FROM notification WHERE id_user = ? AND is_read = 0";
        $db = Config::getConnexion();
        $stmt = $db->prepare($sql);
        $stmt->execute([$id_user]);
        return $stmt->fetchColumn();
    }

    // Marquer une notification comme lue
    public function markAsRead($id) {
        $sql = "UPDATE notification SET is_read = 1 WHERE id = ?";
        $db = Config::getConnexion();
        $db->prepare($sql)->execute([$id]);
    }

    // Marquer TOUTES les notifications comme lues
    public function markAllAsRead($id_user) {
        $sql = "UPDATE notification SET is_read = 1 WHERE id_user = ?";
        $db = Config::getConnexion();
        $db->prepare($sql)->execute([$id_user]);
    }

    // Supprimer une notification
    public function delete($id) {
        $sql = "DELETE FROM notification WHERE id = ?";
        $db = Config::getConnexion();
        $db->prepare($sql)->execute([$id]);
    }
}
?>