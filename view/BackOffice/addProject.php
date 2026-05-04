<script src="../assets/validation.js"></script>

<?php
require_once '../../controller/ProjectC.php';
require_once '../../model/Project.php';
require_once '../../controller/NotificationC.php';
require_once '../../config/config.php';

$pc = new ProjectC();

$error = "";
$success = "";

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $status = $_POST['status'] ?? '';

    if(empty($title) || empty($description)){
        $error = "⚠ Tous les champs sont obligatoires";
    } else {
        $project = new Project($title, $description, $status);
        $pc->addProject($project);

        // =============================================
        // NOTIFIER TOUS LES ADMIN (NOUVEAU PROJET)
        // =============================================
        $db = Config::getConnexion();
        
        // Récupérer tous les admins
        $stmt = $db->prepare("SELECT iduser FROM user WHERE type = 'admin'");
        $stmt->execute();
        $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Récupérer tous les candidats
        $stmt = $db->prepare("SELECT iduser FROM user WHERE type = 'candidat'");
        $stmt->execute();
        $candidats = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $notifC = new NotificationC();
        $nbNotifs = 0;
        
        // Notifier les admins
        foreach($admins as $admin) {
            $notif = new Notification(
                $admin['iduser'],
                'new_project',
                "📢 NOUVEAU PROJET AJOUTÉ : \"$title\"",
                '../BackOffice/listProjects.php'
            );
            $notifC->create($notif);
            $nbNotifs++;
        }
        
        // Notifier les candidats
        foreach($candidats as $candidat) {
            $notif = new Notification(
                $candidat['iduser'],
                'new_project',
                "📢 NOUVEAU PROJET : \"$title\" - Venez postuler !",
                '../FrontOffice/projects.php'
            );
            $notifC->create($notif);
            $nbNotifs++;
        }
        
        if($nbNotifs > 0) {
            $success = "✔ Projet ajouté avec succès - $nbNotifs personnes ont été notifiées !";
        } else {
            $success = "✔ Projet ajouté avec succès !";
        }
        $_POST = [];
    }
}
?>

<?php include('sidebar.php'); ?>

<div class="main-content">

    <div class="form-container">

        <h2>➕ Ajouter un projet</h2>

        <?php if($error) echo "<div class='error'>$error</div>"; ?>
        <?php if($success) echo "<div class='success'>$success</div>"; ?>

        <form method="POST" novalidate id="projectForm">

            <label>Titre</label>
            <input type="text" name="title" id="title" value="<?= $_POST['title'] ?? '' ?>">
            <div class="field-error" id="titleError"></div>

            <label>Description</label>
            <textarea name="description" id="description"><?= $_POST['description'] ?? '' ?></textarea>
            <div class="field-error" id="descriptionError"></div>

            <label>Status</label>
            <select name="status" id="status">
                <option value="actif">✔ Actif</option>
                <option value="inactif">✖ Inactif</option>
            </select>
            <div class="field-error" id="statusError"></div>

            <button type="submit" id="submitBtn">🚀 Ajouter projet</button>

        </form>

    </div>

</div>

<script>
    document.getElementById('projectForm').addEventListener('submit', function(e) {
        document.getElementById('titleError').innerHTML = '';
        document.getElementById('descriptionError').innerHTML = '';
        document.getElementById('statusError').innerHTML = '';
        
        let title = document.getElementById('title').value.trim();
        let description = document.getElementById('description').value.trim();
        let hasError = false;
        
        if(title === '') {
            document.getElementById('titleError').innerHTML = '⚠ Le titre est obligatoire';
            hasError = true;
        }
        
        if(description === '') {
            document.getElementById('descriptionError').innerHTML = '⚠ La description est obligatoire';
            hasError = true;
        }
        
        if(hasError) {
            e.preventDefault();
            return false;
        }
        
        return true;
    });
    
    document.getElementById('title').addEventListener('input', function() {
        document.getElementById('titleError').innerHTML = '';
    });
    
    document.getElementById('description').addEventListener('input', function() {
        document.getElementById('descriptionError').innerHTML = '';
    });
</script>

<style>
.main-content{
    padding:30px;
    background:#f4f6f9;
    min-height:100vh;
}

.form-container{
    max-width:500px;
    margin:auto;
    background:white;
    padding:25px;
    border-radius:12px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

h2{
    text-align:center;
    margin-bottom:20px;
}

label{
    font-weight:bold;
    display:block;
    margin-top:10px;
}

input, textarea, select{
    width:100%;
    padding:10px;
    margin-top:5px;
    border-radius:8px;
    border:1px solid #ccc;
    box-sizing:border-box;
}

button{
    width:100%;
    margin-top:15px;
    padding:12px;
    background:#28a745;
    color:white;
    border:none;
    border-radius:8px;
    font-weight:bold;
    cursor:pointer;
}

button:hover{
    background:#218838;
}

.error{
    background:#f8d7da;
    color:#842029;
    padding:10px;
    border-radius:8px;
    margin-bottom:10px;
}

.success{
    background:#d1e7dd;
    color:#0f5132;
    padding:10px;
    border-radius:8px;
    margin-bottom:10px;
}

.field-error {
    color: red;
    font-size: 12px;
    margin-top: 5px;
    margin-bottom: 5px;
}
</style>