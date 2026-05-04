<?php
require_once __DIR__ . '/../config.php';

class CandidatC {

    public function showCandidat($candidat) {
        
    }

    public function listeCandidat() {
        $db = config::getConnexion();
        try {
            $liste = $db->query("SELECT * FROM candidat");
            return $liste->fetchAll();
        } catch (Exception $e) {
            die("Error: " . $e->getMessage());
        }
    }
    //fetchAll() retourne tous les résultats sous forme de tableau



    public function deleteCandidat($id) {
        $db = config::getConnexion();
        try {
            $req = $db->prepare('DELETE FROM candidat WHERE id = :id');
            $req->execute(['id' => $id]);
        } catch (Exception $e) {
            die('Error: ' . $e->getMessage());
        }
    }

   public function addCandidature($c){

    $sql = "INSERT INTO candidature 
    (idProject, idUser, status, motivation, cv)
    VALUES (?, ?, ?, ?, ?)";

    $db = Config::getConnexion();

    try {
        $query = $db->prepare($sql);

        $query->execute([
            $c->getProject(),
            $c->getUser(),
            $c->getStatus(),
            $c->getMotivation(), 
            $c->getCv()          
        ]);

    } catch (Exception $e){
        die('Erreur: '.$e->getMessage());
    }
}
    public function getCandidat($id) {
        $db = config::getConnexion();
        try {
            $req = $db->prepare('SELECT * FROM candidat WHERE id = :id');
            $req->execute(['id' => $id]);
            return $req->fetch();
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }

    public function updateCandidat($id) {
        $db = config::getConnexion();

        $candidat = $this->getCandidat($id);
        if (!$candidat) {
            die("Candidat introuvable.");//pour vrf cand mawjoud ou non
        }

//Si le formulaire a été envoyé (méthode POST)
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $req = $db->prepare(
                    'UPDATE candidat SET
                        nom    = :nom,
                        email  = :email,
                        statut = :statut
                     WHERE id = :id'
                );

                $req->execute([
                    'nom'    => $_POST['nom'],
                    'email'  => $_POST['email'],
                    'statut' => $_POST['statut'],
                    'id'     => $id
                ]);

                header('Location: liste_candidat.php');
                exit();

            } catch (PDOException $e) {
                die('Erreur PDO: ' . $e->getMessage());
            }
        }

        return $candidat;
    }
}
?>