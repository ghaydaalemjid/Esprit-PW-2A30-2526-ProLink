<?php
// Démarrer la session au début
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Config {
    public static function getConnexion() {
        try {
            $conn = new PDO(
                "mysql:host=127.0.0.1;port=3307;dbname=projet;charset=utf8",
                "root",
                ""
            );
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch(PDOException $e) {
            die("Erreur DB: " . $e->getMessage());
        }
    }
}
?>