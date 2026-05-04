<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once('../../controller/projectC.php');
$pc = new ProjectC();
$projects = $pc->listProjects();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>ProLink - Accueil</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">

<style>
body {
    font-family: 'Inter', sans-serif;
    background: linear-gradient(180deg,#061022 0%, #07162a 100%);
    color:#e6f0f6;
    margin:0;
}

.container {
    max-width:1200px;
    margin:auto;
    padding:20px;
}

/* HERO */
.hero {
    padding:60px;
    background: linear-gradient(135deg,#00a7ff,#00d4ff);
    border-radius:20px;
    color:#00151b;
    margin-top:20px;
}

.hero h1 {
    font-size:40px;
    font-weight:800;
}

.hero p {
    font-size:18px;
}

.cta {
    background:#00151b;
    color:#00d4ff;
    padding:12px 25px;
    border-radius:10px;
    text-decoration:none;
    display:inline-block;
    margin-top:15px;
    transition:0.3s;
}

.cta:hover {
    background:#00313a;
}

/* FEATURES */
.features {
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:20px;
    margin-top:30px;
}

.feature-card {
    background:#0f1724;
    padding:25px;
    border-radius:15px;
    transition:0.3s;
}

.feature-card:hover {
    transform:translateY(-5px);
    box-shadow:0 10px 25px rgba(0,0,0,0.3);
}

/* PROJECT GRID */
.project-grid {
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(280px,1fr));
    gap:25px;
    margin-top:20px;
}

/* PROJECT CARD */
.project-card {
    position:relative;
    border-radius:18px;
    overflow:hidden;
    background:#0f1724;
    transition:0.4s;
    cursor:pointer;
}

/* IMAGE */
.project-img {
    height:180px;
    background: linear-gradient(135deg,#00d4ff,#0066ff);
    opacity:0.85;
    transition:0.4s;
}

/* CONTENT (FIX ICI) */
.project-content {
    padding:20px;
    color:#ffffff; /* ✅ correction principale */
}

.project-content h3 {
    margin:0 0 10px;
    font-size:20px;
    font-weight:700;
    color:#ffffff; /* ✅ titre visible */
}

.project-content p {
    font-size:14px;
    color:#cbd5e1; /* ✅ texte lisible */
    opacity:1;
}

/* BUTTON */
.btn-view {
    display:inline-block;
    margin-top:10px;
    padding:10px 18px;
    border-radius:8px;
    background:#00d4ff;
    color:#00151b;
    text-decoration:none;
    font-weight:600;
    transition:0.3s;
}

.btn-view:hover {
    background:#00a7cc;
}

/* BADGE */
.badge {
    position:absolute;
    top:12px;
    right:12px;
    padding:6px 12px;
    border-radius:20px;
    font-size:12px;
    font-weight:bold;
}

.badge.active {
    background:#28a745;
    color:white;
}

.badge.inactive {
    background:#dc3545;
    color:white;
}

/* HOVER */
.project-card:hover {
    transform:translateY(-10px) scale(1.02);
    box-shadow:0 20px 40px rgba(0,0,0,0.5);
}

.project-card:hover .project-img {
    opacity:1;
}

h2 {
    margin-top:40px;
}
</style>

</head>

<body>

<?php include 'components/navbar.php'; ?>

<div class="container">

    <!-- HERO -->
    <section class="hero">
        <h1>🚀 Bienvenue sur ProLink</h1>
        <p>Connectez-vous avec des professionnels et développez vos projets.</p>
        <a href="../register.php" class="cta">Commencer</a>
    </section>

    <!-- FEATURES -->
    <section class="features">
        <div class="feature-card">
            <h3>🌐 Réseau</h3>
            <p>Développez votre réseau professionnel.</p>
        </div>

        <div class="feature-card">
            <h3>📁 Projets</h3>
            <p>Publiez et collaborez sur des projets.</p>
        </div>

        <div class="feature-card">
            <h3>📅 Événements</h3>
            <p>Participez à des événements professionnels.</p>
        </div>
    </section>

    <!-- PROJETS -->
    <section>
        <h2>🔥 Derniers projets</h2>

        <div class="project-grid">
            <?php foreach($projects as $p) { ?>

                <div class="project-card">

                    <div class="project-img"></div>

                    <span class="badge <?= $p['status']=='actif' ? 'active' : 'inactive' ?>">
                        <?= $p['status'] ?>
                    </span>

                    <div class="project-content">
                        <h3><?= htmlspecialchars($p['title']) ?></h3>

                        <p>
                            <?= substr(htmlspecialchars($p['description']),0,80) ?>...
                        </p>

                        <a href="projectDetails.php?id=<?= $p['idProject'] ?>" class="btn-view">
                            👁 Voir détails
                        </a>
                    </div>

                </div>

            <?php } ?>
        </div>
    </section>

</div>

<?php include 'components/footer.php'; ?>

</body>
</html>