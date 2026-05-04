<?php
require_once('../../config/config.php');

session_start();

// 🔥 user (temporaire)
$idUser = 1;

$db = Config::getConnexion();

$sql = "SELECT c.*, p.title 
        FROM candidature c
        JOIN project p ON c.idProject = p.idProject
        WHERE c.idUser = $idUser";

$list = $db->query($sql);
?>

<?php include('components/navbar.php'); ?>

<div class="container mt-4">

    <h2>Mes candidatures</h2>

    <!-- ✅ MESSAGE -->
    <?php if(isset($_GET['success'])) { ?>
        <div style="background:#d4edda;color:#155724;padding:10px;margin-bottom:10px;border-radius:5px;">
            ✔ Candidature ajoutée avec succès
        </div>
    <?php } ?>

    <?php if(isset($_GET['error']) && $_GET['error']=='deja') { ?>
        <div style="background:#f8d7da;color:#721c24;padding:10px;margin-bottom:10px;border-radius:5px;">
            ⚠ Vous avez déjà postulé
        </div>
    <?php } ?>

    <!-- 🔥 LISTE -->
    <?php foreach($list as $c) { ?>

        <?php
        $color = $c['status'] == 'accepte' ? 'green' : 
                ($c['status'] == 'refuse' ? 'red' : 'orange');
        ?>

        <div class="card p-3 mb-3">

            <h4><?= $c['title'] ?></h4>

            <p>
                Status :
                <span style="
                    background:<?= $color ?>;
                    color:white;
                    padding:5px 10px;
                    border-radius:5px;
                ">
                    <?= $c['status'] ?>
                </span>
            </p>

            <!-- 🔥 MOTIVATION -->
            <p><strong>Motivation:</strong> <?= $c['motivation'] ?></p>

            <!-- 🔥 DATE -->
            <p><strong>Date:</strong> <?= $c['date_postulation'] ?></p>

            <!-- 🔥 CV -->
            <?php if(!empty($c['cv'])) { ?>
                <a href="uploads/<?= $c['cv'] ?>" target="_blank" class="btn btn-primary">
                    📄 Voir CV
                </a>
            <?php } ?>

        </div>

    <?php } ?>

</div>

<?php include('components/footer.php'); ?>