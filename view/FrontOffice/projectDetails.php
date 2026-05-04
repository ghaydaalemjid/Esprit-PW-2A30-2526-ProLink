<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once('../../controller/projectC.php');

$pc = new ProjectC();

if(!isset($_GET['id'])){
    die("Projet introuvable");
}

$p = $pc->getProject($_GET['id']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Détail Projet</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>

body {
    font-family: 'Inter', sans-serif;
    background: #f1f5f9;
    margin:0;
}

/* CONTAINER */
.container {
    max-width:900px;
    margin:40px auto;
}

/* RETOUR */
.back {
    display:inline-block;
    margin-bottom:20px;
    color:#2563eb;
    text-decoration:none;
}

/* CARD */
.project-box {
    border-radius:20px;
    overflow:hidden;
    background:white;
    box-shadow:0 15px 40px rgba(0,0,0,0.15);
}

/* HEADER (IMAGE STYLE) */
.project-header {
    height:200px;
    background: linear-gradient(135deg,#00d4ff,#0066ff);
}

/* CONTENT */
.project-content {
    padding:30px;
    background:#0f1724;
}

/* TEXT */
.project-content h1 {
    color:white;
    margin-bottom:15px;
}

.project-content p {
    color:#cbd5e1;
    margin-bottom:10px;
}

/* STATUS */
.status {
    margin-top:10px;
    font-weight:bold;
}

.active {
    color:#22c55e;
}

.inactive {
    color:#ef4444;
}

/* BUTTON */
.btn-apply {
    display:inline-block;
    margin-top:20px;
    padding:12px 25px;
    border-radius:10px;
    background:#00d4ff;
    color:#00151b;
    text-decoration:none;
    font-weight:600;
    transition:0.3s;
}

.btn-apply:hover {
    background:#0ea5e9;
}

/* CLOSED */
.closed {
    margin-top:20px;
    color:#ef4444;
    font-weight:600;
}

</style>

</head>

<body>

<?php include('components/navbar.php'); ?>

<div class="container">

    <a href="home.php" class="back">← Retour</a>

    <div class="project-box">

        <!-- HEADER -->
        <div class="project-header"></div>

        <!-- CONTENT -->
        <div class="project-content">

            <h1><?= htmlspecialchars($p['title']) ?></h1>

            <p><?= htmlspecialchars($p['description']) ?></p>

            <div class="status <?= $p['status']=='actif' ? 'active' : 'inactive' ?>">
                Status: <?= $p['status'] ?>
            </div>

            <?php if($p['status']=='actif') { ?>
                <a href="apply.php?id=<?= $p['idProject'] ?>" class="btn-apply">
                    🚀 Postuler maintenant
                </a>
            <?php } else { ?>
                <p class="closed">❌ Projet fermé</p>
            <?php } ?>

        </div>

    </div>

</div>

<?php include('components/footer.php'); ?>

</body>
</html>