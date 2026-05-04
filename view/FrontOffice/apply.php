<?php
// permet à un candidat de postuler à un projet apply
// 🔥 AFFICHER LES ERREURS (important)
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// ✅ INCLUDES CORRECTS (chemins absolus depuis la racine)
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../controller/CandidatureC.php';
require_once __DIR__ . '/../../model/Candidature.php';

$pdo = config::getConnexion();
$cc = new CandidatureC();

// ✅ RÉCUPÉRER ID
$idProject = isset($_GET['id']) ? intval($_GET['id']) : 0;
// Récupérer l'ID du candidat connecté depuis $_SESSION['user']
$idUser = $_SESSION['user']['iduser'] ?? 1;

// 🔒 sécurité
if($idProject <= 0){
    die("❌ ID projet invalide");
}

// ✅ vérifier projet
$stmt = $pdo->prepare("SELECT * FROM project WHERE idProject = ?");
$stmt->execute([$idProject]);
$project = $stmt->fetch();

if(!$project){
    die("❌ Projet introuvable");
}

$error = "";
$success = "";

// ✅ message après redirect
if(isset($_GET['success'])){
    $success = "✔ Candidature envoyée avec succès";
}

// 🔥 TRAITEMENT FORMULAIRE (AVEC VÉRIFICATION DOUBLON)
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $nom = $_POST['nom'] ?? '';
    $email = $_POST['email'] ?? '';
    $telephone = $_POST['telephone'] ?? '';
    $motivation = $_POST['motivation'] ?? '';

    if(empty($nom) || empty($email) || empty($telephone) || empty($motivation)){
        $error = "⚠ Veuillez remplir tous les champs";
    }
    elseif(!isset($_FILES['cv']) || $_FILES['cv']['error'] != 0){
        $error = "⚠ Veuillez ajouter votre CV";
    }
    else{
        // ========== VÉRIFICATION DOUBLON ==========
        // Vérifier si ce candidat a déjà postulé à ce projet (même email)
        $checkSql = "SELECT COUNT(*) FROM candidature 
                     WHERE email = :email AND idProject = :idProject";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->execute([
            ':email' => $email,
            ':idProject' => $idProject
        ]);
        $existeDeja = $checkStmt->fetchColumn();
        
        if($existeDeja > 0) {
            $error = "⚠ Vous avez déjà postulé à ce projet ! Une seule candidature est autorisée.";
        }
        else {
            // Pas de doublon, on insère
            $uploadDir = __DIR__ . '/uploads/';
            if(!is_dir($uploadDir)){
                mkdir($uploadDir, 0777, true);
            }

            $cvName = time().'_'.basename($_FILES['cv']['name']);
            move_uploaded_file($_FILES['cv']['tmp_name'], $uploadDir . $cvName);

            $c = new Candidature(
                $idProject,
                $idUser,
                'en_attente',
                $nom,
                $email,
                $telephone,
                $motivation,
                $cvName
            );

            $cc->addCandidature($c);

            // ✅ REDIRECTION
            header("Location: apply.php?id=".$idProject."&success=1");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postuler - ProLink</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        /* Styles supplémentaires pour le formulaire */
        .apply-section {
            max-width: 700px;
            margin: 50px auto;
            background: white;
            padding: 35px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .apply-section h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 28px;
        }

        .project-badge {
            text-align: center;
            background: #e3f2fd;
            color: #007bff;
            padding: 12px;
            border-radius: 50px;
            margin-bottom: 30px;
            font-size: 18px;
            font-weight: bold;
        }

        .apply-section input, 
        .apply-section textarea {
            width: 100%;
            padding: 14px;
            margin: 10px 0;
            border-radius: 10px;
            border: 1px solid #ddd;
            box-sizing: border-box;
            font-size: 15px;
            transition: border 0.3s;
        }

        .apply-section input:focus, 
        .apply-section textarea:focus {
            border-color: #007bff;
            outline: none;
        }

        .apply-section textarea {
            resize: vertical;
            min-height: 120px;
        }

        .apply-section button {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            margin-top: 15px;
            transition: transform 0.2s;
        }

        .apply-section button:hover {
            transform: translateY(-2px);
        }

        .alert-success {
            background: #d1e7dd;
            color: #0f5132;
            padding: 14px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #0f5132;
        }

        .alert-danger {
            background: #f8d7da;
            color: #842029;
            padding: 14px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #842029;
        }

        .back-link {
            text-align: center;
            margin-top: 25px;
        }

        .back-link a {
            color: #007bff;
            text-decoration: none;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        /* Style pour les messages d'erreur sous les champs */
        .field-error {
            color: #dc3545;
            font-size: 12px;
            margin-top: -5px;
            margin-bottom: 10px;
        }

        .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<!-- INCLUSION DU HEADER/NAVBAR -->
<?php include __DIR__ . '/components/navbar.php'; ?>

<main>
    <div class="apply-section">
        <h1>🚀 Postuler au projet</h1>

        <!-- afficher nom du projet -->
        <div class="project-badge">
            📁 Projet : <strong><?= htmlspecialchars($project['title']) ?></strong>
        </div>

        <?php if($error): ?>
            <div class="alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <?php if($success): ?>
            <div class="alert-success"><?= $success ?></div>
        <?php endif; ?>

        <!-- FORMULAIRE -->
        <form method="POST" enctype="multipart/form-data" id="applyForm" novalidate>

            <div class="form-group">
                <input type="text" name="nom" id="nom" placeholder="📝 Nom complet" value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>">
                <div class="field-error" id="nomError"></div>
            </div>

            <div class="form-group">
                <input type="email" name="email" id="email" placeholder="📧 Email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                <div class="field-error" id="emailError"></div>
            </div>

            <div class="form-group">
                <input type="tel" name="telephone" id="telephone" placeholder="📞 Téléphone" value="<?= htmlspecialchars($_POST['telephone'] ?? '') ?>">
                <div class="field-error" id="telephoneError"></div>
            </div>

            <div class="form-group">
                <textarea name="motivation" id="motivation" placeholder="💬 Pourquoi voulez-vous rejoindre ce projet ?"><?= htmlspecialchars($_POST['motivation'] ?? '') ?></textarea>
                <div class="field-error" id="motivationError"></div>
            </div>

            <div class="form-group">
                <label style="display:block; margin-bottom:8px; color:#555; font-weight:500;">📄 CV (PDF, DOC)</label>
                <input type="file" name="cv" id="cv" accept=".pdf,.doc,.docx">
                <div class="field-error" id="cvError"></div>
            </div>

            <button type="submit">📩 Envoyer candidature</button>

        </form>

        <div class="back-link">
            <a href="projects.php">← Retour aux projets</a>
        </div>
    </div>
</main>

<!-- INCLUSION DU FOOTER -->
<?php include __DIR__ . '/components/footer.php'; ?>

<script>
// disparition message auto après 5 secondes
setTimeout(function(){
    let msg = document.querySelector('.alert-success, .alert-danger');
    if(msg){
        msg.style.transition = "opacity 0.5s";
        msg.style.opacity = "0";
        setTimeout(() => msg.remove(), 500);
    }
}, 5000);

// Validation JavaScript avec messages sous chaque champ
document.getElementById('applyForm').addEventListener('submit', function(e){
    
    // Réinitialiser les messages d'erreur
    document.getElementById('nomError').innerHTML = '';
    document.getElementById('emailError').innerHTML = '';
    document.getElementById('telephoneError').innerHTML = '';
    document.getElementById('motivationError').innerHTML = '';
    document.getElementById('cvError').innerHTML = '';
    
    let nom = document.getElementById('nom').value.trim();
    let email = document.getElementById('email').value.trim();
    let telephone = document.getElementById('telephone').value.trim();
    let motivation = document.getElementById('motivation').value.trim();
    let cv = document.getElementById('cv').files[0];
    let hasError = false;
    
    // Vérifier le nom
    if(nom === '') {
        document.getElementById('nomError').innerHTML = '⚠ Le nom est obligatoire';
        hasError = true;
    }
    
    // Vérifier l'email
    if(email === '') {
        document.getElementById('emailError').innerHTML = '⚠ L\'email est obligatoire';
        hasError = true;
    } else if(email.indexOf('@') === -1) {
        document.getElementById('emailError').innerHTML = '⚠ Email invalide (doit contenir @)';
        hasError = true;
    }
    
    // Vérifier le téléphone
    if(telephone === '') {
        document.getElementById('telephoneError').innerHTML = '⚠ Le téléphone est obligatoire';
        hasError = true;
    }
    
    // Vérifier la motivation
    if(motivation === '') {
        document.getElementById('motivationError').innerHTML = '⚠ La motivation est obligatoire';
        hasError = true;
    }
    
    // Vérifier le CV
    if(!cv) {
        document.getElementById('cvError').innerHTML = '⚠ Veuillez ajouter votre CV';
        hasError = true;
    }
    
    // S'il y a des erreurs, on bloque l'envoi
    if(hasError) {
        e.preventDefault();
        return false;
    }
    
    // Tout est bon, on envoie
    return true;
});

// Effacer les erreurs quand l'utilisateur commence à taper
document.getElementById('nom').addEventListener('input', function() {
    document.getElementById('nomError').innerHTML = '';
});

document.getElementById('email').addEventListener('input', function() {
    document.getElementById('emailError').innerHTML = '';
});

document.getElementById('telephone').addEventListener('input', function() {
    document.getElementById('telephoneError').innerHTML = '';
});

document.getElementById('motivation').addEventListener('input', function() {
    document.getElementById('motivationError').innerHTML = '';
});

document.getElementById('cv').addEventListener('change', function() {
    document.getElementById('cvError').innerHTML = '';
});
</script>

</body>
</html>