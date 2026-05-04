<?php
session_start();
require_once '../../controller/NotificationC.php';
require_once '../../config/config.php';

if (!isset($_SESSION['user'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Non authentifié']);
    exit();
}

$user_id = $_SESSION['user']['iduser'];
$db = Config::getConnexion();
$action = $_GET['action'] ?? 'get';

switch($action) {
    case 'get':
        // Pour le candidat : accepte, refuse, new_project, bienvenue
        $sql = "SELECT * FROM notification 
                WHERE id_user = ? 
                AND (type = 'accepte' OR type = 'refuse' OR type = 'new_project' OR type = 'bienvenue')
                AND is_read = 0
                ORDER BY created_at DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute([$user_id]);
        $unread = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $sqlCount = "SELECT COUNT(*) FROM notification 
                     WHERE id_user = ? 
                     AND (type = 'accepte' OR type = 'refuse' OR type = 'new_project' OR type = 'bienvenue')
                     AND is_read = 0";
        $stmtCount = $db->prepare($sqlCount);
        $stmtCount->execute([$user_id]);
        $count = $stmtCount->fetchColumn();
        
        echo json_encode([
            'success' => true,
            'count' => $count,
            'notifications' => $unread
        ]);
        break;
        
    case 'mark_read':
        $id = $_GET['id'] ?? 0;
        if($id) {
            $sql = "UPDATE notification SET is_read = 1 WHERE id = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$id]);
        }
        echo json_encode(['success' => true]);
        break;
        
    case 'mark_all_read':
        $sql = "UPDATE notification SET is_read = 1 
                WHERE id_user = ? 
                AND (type = 'accepte' OR type = 'refuse' OR type = 'new_project' OR type = 'bienvenue')";
        $stmt = $db->prepare($sql);
        $stmt->execute([$user_id]);
        echo json_encode(['success' => true]);
        break;
        
    default:
        echo json_encode(['error' => 'Action inconnue']);
}
?>