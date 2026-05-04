<?php
require_once('../../controller/projectC.php');
$pc = new ProjectC();
$projects = $pc->listProjects();
?>

<?php include('components/navbar.php'); ?>

<div class="container">

    <h2>🚀 Nos Projets</h2>

    <div class="projects-grid">

        <?php foreach($projects as $p) { ?>

            <div class="project-card">

                <h3><?= $p['title'] ?></h3>

                <p><?= $p['description'] ?></p>

                <a href="projectDetails.php?id=<?= $p['idProject'] ?>" class="btn">
                    👁 Voir détails
                </a>

            </div>

        <?php } ?>

    </div>

</div>

<?php include('components/footer.php'); ?>

<style>

body{
    background:#f4f6f9;
    font-family:Arial;
}

.container{
    max-width:1100px;
    margin:auto;
    padding:30px;
}

h2{
    margin-bottom:20px;
}

/* GRID */
.projects-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit, minmax(300px,1fr));
    gap:20px;
}

/* CARD */
.project-card{
    background:white;
    padding:20px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
    transition:0.3s;
}

.project-card:hover{
    transform:translateY(-5px);
}

/* BUTTON */
.btn{
    display:inline-block;
    margin-top:10px;
    background:#00c6ff;
    color:white;
    padding:8px 15px;
    border-radius:8px;
    text-decoration:none;
}

.btn:hover{
    background:#009ec3;
}

</style>