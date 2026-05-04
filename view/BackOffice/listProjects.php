<?php

require_once('../../controller/projectC.php');
$pc = new ProjectC();
?>

<?php include('sidebar.php'); ?>

<div class="main-content" style="margin-left: 260px; padding: 20px; min-height: 100vh;">

    <!-- STATISTIQUES -->
    <?php
    $resultat = $pc->listProjects();
    $list = $resultat->fetchAll(PDO::FETCH_ASSOC);
    
    $total = count($list);
    $actifs = 0;
    $inactifs = 0;
    foreach($list as $projet) {
        if($projet['status'] == 'actif') {
            $actifs++;
        } else {
            $inactifs++;
        }
    }
    
    // Utiliser la même méthode que listCandidatures.php
    require_once('../../controller/CandidatureC.php');
    $cc = new CandidatureC();
    $candidaturesList = $cc->listCandidatures();
    
    // Compter les candidatures
    if(is_array($candidaturesList)) {
        $candidats = count($candidaturesList);
    } elseif(is_object($candidaturesList)) {
        $candidats = $candidaturesList->rowCount();
    } else {
        $candidats = 0;
    }
    ?>
    
    <!-- 4 CARTES -->
    <div style="display: flex; gap: 20px; margin-bottom: 30px; flex-wrap: wrap;">
        <div style="background: #2196F3; color: white; padding: 20px; border-radius: 15px; width: 220px; text-align: center;">
            <div style="font-size: 36px; font-weight: bold;"><?= $total ?></div>
            <div>📁 TOTAL PROJETS</div>
        </div>
        <div style="background: #4CAF50; color: white; padding: 20px; border-radius: 15px; width: 220px; text-align: center;">
            <div style="font-size: 36px; font-weight: bold;"><?= $actifs ?></div>
            <div>✅ ACTIFS</div>
        </div>
        <div style="background: #f44336; color: white; padding: 20px; border-radius: 15px; width: 220px; text-align: center;">
            <div style="font-size: 36px; font-weight: bold;"><?= $inactifs ?></div>
            <div>❌ INACTIFS</div>
        </div>
        <div style="background: #FF9800; color: white; padding: 20px; border-radius: 15px; width: 220px; text-align: center;">
            <div style="font-size: 36px; font-weight: bold;"><?= $candidats ?></div>
            <div>📝 CANDIDATURES</div>
            <a href="listCandidatures.php" style="display:inline-block; margin-top:10px; color:white; font-size:12px;">🔍 Voir détails →</a>
        </div>
    </div>

    <!-- HEADER + BOUTON -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2>Gestion des projets</h2>
        <a href="addProject.php" style="background:#28a745; color:white; padding:10px 15px; border-radius:8px; text-decoration:none;">➕ Ajouter projet</a>
    </div>

    <!-- TABLEAU -->
    <table style="width:100%; border-collapse: collapse; background:white; border-radius:10px; overflow:hidden;">
        <thead>
            <tr style="background:#2c3e50; color:white;">
                <th>Description</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($list as $p): ?>
            <tr style="border-bottom:1px solid #ddd; text-align:center;">
                <td style="padding:10px;"><?= htmlspecialchars($p['title'] ?? '') ?></td>
                <td>
                    <?php if(($p['status'] ?? '') == 'actif'): ?>
                        <span style="color:green;">✔ Actif</span>
                    <?php else: ?>
                        <span style="color:red;">✖ Inactif</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a href="updateProject.php?id=<?= $p['idProject'] ?? '' ?>" style="background:#007bff; color:white; padding:5px 10px; border-radius:5px; text-decoration:none;">✏ Modifier</a>
                    <a href="deleteProject.php?id=<?= $p['idProject'] ?? '' ?>" onclick="return confirm('Supprimer ?')" style="background:#dc3545; color:white; padding:5px 10px; border-radius:5px; text-decoration:none;">🗑 Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<style>
body { overflow-x: hidden; margin: 0; padding: 0; }
</style>