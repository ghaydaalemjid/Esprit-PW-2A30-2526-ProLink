<?php
require_once '../../controller/ProjectC.php';
require_once '../../model/Project.php';

$pc = new ProjectC();

// UPDATE
if(isset($_POST['title'])) {
    $project = new Project($_POST['title'], $_POST['description'], $_POST['status']);
    $pc->updateProject($project, $_GET['id']);

    header('Location:updateProject.php?id='.$_GET['id'].'&success=1');
    exit();
}

// GET PROJECT
$p = $pc->getProject($_GET['id']);
?>

<?php include('sidebar.php'); ?>

<style>
.main-content {
    margin-left: 250px;
    padding: 40px;
    background: #f4f6f9;
    min-height: 100vh;
}

.container-flex {
    display: flex;
    gap: 30px;
}

/* carte formulaire */
.card {
    flex: 1;
    background: white;
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

/* carte droite */
.side-card {
    width: 300px;
    background: white;
    padding: 20px;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

h2 {
    margin-bottom: 25px;
    color: #2c3e50;
}

label {
    font-weight: 600;
    color: #555;
    display: block;
    margin-bottom: 5px;
}

input, select {
    width: 100%;
    padding: 12px;
    margin-bottom: 18px;
    border-radius: 8px;
    border: 1px solid #ddd;
    font-size: 14px;
    transition: 0.2s;
}

input:focus, select:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 5px rgba(0,123,255,0.2);
}

button {
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    padding: 12px;
    border: none;
    border-radius: 8px;
    width: 100%;
    font-weight: bold;
    font-size: 15px;
    cursor: pointer;
    transition: 0.3s;
}

button:hover {
    transform: scale(1.03);
    box-shadow: 0 5px 15px rgba(0,123,255,0.4);
}

/* message succès */
.success {
    background: #d4edda;
    color: #155724;
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 15px;
}
</style>

<div class="main-content">

    <h2>Modifier projet</h2>

    <div class="container-flex">

        <!-- FORMULAIRE -->
        <div class="card">

            <?php if(isset($_GET['success'])) { ?>
                <div class="success">✔ Projet modifié avec succès</div>
            <?php } ?>

            <form method="POST">

                <label>Titre</label>
                <input type="text" name="title"
                    value="<?= $p['title'] ?>" required>

                <label>Description</label>
                <input type="text" name="description"
                    value="<?= $p['description'] ?>" required>

                <label>Status</label>
                <select name="status">
                    <option value="actif" <?= $p['status']=='actif'?'selected':'' ?>>✔ Actif</option>
                    <option value="inactif" <?= $p['status']=='inactif'?'selected':'' ?>>❌ Inactif</option>
                </select>

                <button type="submit">🚀 Enregistrer les modifications</button>

            </form>

        </div>

        <!-- CARTE DROITE -->
        <div class="side-card">

            <h4 style="margin-bottom:15px;">📊 Aperçu projet</h4>

            <p><strong>Titre :</strong><br> <?= $p['title'] ?></p>

            <p><strong>Description :</strong><br> <?= $p['description'] ?></p>

            <p>
                <strong>Status :</strong><br>
                <?php if($p['status']=='actif'){ ?>
                    <span style="color:green;">✔ Actif</span>
                <?php } else { ?>
                    <span style="color:red;">❌ Inactif</span>
                <?php } ?>
            </p>

        </div>

    </div>

</div>