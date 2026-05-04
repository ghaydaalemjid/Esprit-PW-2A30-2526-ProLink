<?php
require_once('../../controller/CandidatureC.php');
require_once('../../controller/NotificationC.php'); // AJOUTÉ

$cc = new CandidatureC();
$list = $cc->listCandidatures();

?>

<?php include('sidebar.php'); ?>

<div class="main-content" style="padding:20px;">

    <h2>Gestion des candidatures</h2>

    <table style="width:100%; border-collapse: collapse; background:white; border-radius:10px; overflow:hidden;">

        <tr style="background:#2c3e50; color:white;">
            <th style="padding:12px;">Projet</th>
            <th>Candidat</th>
            <th>Status</th>
            <th>CV</th>
            <th>Action</th>
        </tr>

        <?php foreach($list as $c) { ?>
        <tr style="text-align:center; border-bottom:1px solid #ddd;">

            <!-- Projet -->
            <td style="padding:10px;">
                <?= $c['title'] ?>
            </td>

            <!-- Nom + Email -->
            <td>
                <b><?= $c['nom'] ?></b><br>
                <small><?= $c['email'] ?></small>
            </td>

            <!-- Status -->
            <td>
                <?php if($c['status']=='accepte'){ ?>
                    <span style="color:green; font-weight:bold;">✔ Accepté</span>
                <?php } elseif($c['status']=='refuse'){ ?>
                    <span style="color:red; font-weight:bold;">✖ Refusé</span>
                <?php } else { ?>
                    <span style="color:orange; font-weight:bold;">⏳ En attente</span>
                <?php } ?>
            </td>

            <!-- CV -->
            <td>
                <a href="../FrontOffice/uploads/<?= $c['cv'] ?>" target="_blank">
                    📄 Voir CV
                </a>
            </td>
     
            <!-- Actions -->
            <td>

                <?php if($c['status']=='en_attente'){ ?>

                    <a href="updateStatus.php?id=<?= $c['idCandidature'] ?>&s=accepte&user_id=<?= $c['idUser'] ?>&project=<?= urlencode($c['title']) ?>"
                       onclick="return confirm('Accepter cette candidature ?')"
                       style="background:#28a745; color:white; padding:5px 10px; border-radius:5px; text-decoration:none;">
                       ✔ Accepter
                    </a>

                    <a href="updateStatus.php?id=<?= $c['idCandidature'] ?>&s=refuse&user_id=<?= $c['idUser'] ?>&project=<?= urlencode($c['title']) ?>"
                       onclick="return confirm('Refuser cette candidature ?')"
                       style="background:#dc3545; color:white; padding:5px 10px; border-radius:5px; text-decoration:none;">
                       ✖ Refuser
                    </a>

                <?php } else { ?>

                    <span style="color:gray;">Déjà traité</span>

                <?php } ?>

            </td>

        </tr>
        <?php } ?>

    </table>

</div>