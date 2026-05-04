<?php
session_start();
echo "<!-- DEBUG: user session = ";
print_r($_SESSION['user']);
echo " -->";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - ProLink</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Chart.js pour les graphiques -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root{
            --vibrant-1: #00b4d8;
            --vibrant-2: #0077b6;
            --vibrant-3: #90e0ef;
            --glass: rgba(255,255,255,0.06);
        }
        html,body{ height:100%; margin:0; padding:0; }
        body{ font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, Arial; background: linear-gradient(180deg, #f6fbff 0%, #eef7ff 100%); }

        .content{ margin-left: 260px; padding:28px; }

        .dashboard-header{ display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:20px; flex-wrap:wrap; }
        .page-title{ font-size:28px; font-weight:800; color:#073b4c }
        .subtitle{ color:#376a79; font-weight:600 }

        .stats-grid{ display:grid; grid-template-columns: repeat(4,1fr); gap:18px; margin-bottom:30px; }
        
        .stat-card{ background: linear-gradient(135deg,var(--vibrant-1), var(--vibrant-2)); color: white; padding:20px; border-radius:12px; box-shadow: 0 12px 30px rgba(3,37,65,0.08); transition: transform .22s; display:flex; align-items:center; gap:14px; cursor:pointer; }
        .stat-card .icon{ width:56px; height:56px; border-radius:10px; background: rgba(255,255,255,0.12); display:flex; align-items:center; justify-content:center; font-size:22px; }
        .stat-card h3{ margin:0; font-size:28px; font-weight:700; }
        .stat-card p{ margin:4px 0 0 0; opacity:0.92; font-size:14px; }
        .stat-card small{ font-size:11px; opacity:0.8; }
        .stat-card:hover{ transform: translateY(-5px) scale(1.01); box-shadow: 0 20px 40px rgba(3,37,65,0.12); }

        .card-light{ background: white; color: #073b4c; padding:20px; border-radius:12px; box-shadow: 0 8px 22px rgba(3,37,65,0.06); transition: transform .18s; }
        .card-light:hover{ transform: translateY(-4px); }
        .card-light h4{ margin:0 0 15px 0; color:#0077b6; font-size:18px; border-left:3px solid #00b4d8; padding-left:12px; }
        
        .grid-2{ display:grid; grid-template-columns: repeat(2,1fr); gap:20px; margin-bottom:30px; }
        .grid-3{ display:grid; grid-template-columns: repeat(3,1fr); gap:20px; margin-bottom:30px; }
        
        .notification-bar{
            background: #fff3cd;
            border-left: 4px solid #FF9800;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .btn-primary{
            background: #0077b6;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
        }
        .btn-primary:hover{ background: #0056b3; }
        .btn-orange{ background: #FF9800; margin-left:10px; }
        .btn-orange:hover{ background: #e68900; }
        
        .badge{
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-green{ background: #d1e7dd; color: #0f5132; }
        .badge-orange{ background: #fff3cd; color: #856404; }
        .badge-red{ background: #f8d7da; color: #842029; }
        
        @media (max-width:1000px){ 
            .stats-grid{ grid-template-columns: repeat(2,1fr); }
            .grid-2{ grid-template-columns: 1fr; }
            .grid-3{ grid-template-columns: 1fr; }
            .content{ margin-left: 0; }
        }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<?php include 'sidebar.php'; ?>

<!-- CONTENT -->
<div class="content">

<?php
// ========== CONNEXION BDD ET REQUÊTES ==========
require_once '../../config/config.php';
$pdo = config::getConnexion();

// 1. STATISTIQUES GLOBALES
$totalUsers = $pdo->query("SELECT COUNT(*) FROM user")->fetchColumn();
$totalProjets = $pdo->query("SELECT COUNT(*) FROM project")->fetchColumn();
$projetsActifs = $pdo->query("SELECT COUNT(*) FROM project WHERE status = 'actif'")->fetchColumn();
$totalCandidatures = $pdo->query("SELECT COUNT(*) FROM candidature")->fetchColumn();
$enAttente = $pdo->query("SELECT COUNT(*) FROM candidature WHERE status = 'en_attente'")->fetchColumn();
$acceptes = $pdo->query("SELECT COUNT(*) FROM candidature WHERE status = 'accepte'")->fetchColumn();
$refuses = $pdo->query("SELECT COUNT(*) FROM candidature WHERE status = 'refuse'")->fetchColumn();

// Taux conversion
$tauxConversion = $totalCandidatures > 0 ? round(($acceptes / $totalCandidatures) * 100) : 0;

// 2. JOINTURE : Candidatures par projet (pour graphique barres)
$sqlBarres = "SELECT p.title, COUNT(c.idCandidature) as nb
              FROM project p
              LEFT JOIN candidature c ON p.idProject = c.idProject
              GROUP BY p.idProject
              ORDER BY nb DESC";
$statsProjets = $pdo->query($sqlBarres)->fetchAll(PDO::FETCH_ASSOC);

// 3. JOINTURE : Statuts des candidatures (pour camembert)
$sqlStatus = "SELECT 
                CASE status 
                    WHEN 'accepte' THEN 'Accepté'
                    WHEN 'refuse' THEN 'Refusé'
                    WHEN 'en_attente' THEN 'En attente'
                    ELSE status 
                END as status_label,
                COUNT(*) as nb 
              FROM candidature 
              GROUP BY status";
$statsStatus = $pdo->query($sqlStatus)->fetchAll(PDO::FETCH_ASSOC);

// 4. JOINTURE : Top 3 projets populaires
$sqlTop3 = "SELECT p.title, COUNT(c.idCandidature) as nb
            FROM project p
            JOIN candidature c ON p.idProject = c.idProject
            GROUP BY p.idProject
            ORDER BY nb DESC
            LIMIT 3";
$top3 = $pdo->query($sqlTop3)->fetchAll(PDO::FETCH_ASSOC);

// 5. Dernières candidatures avec JOINTURE
$sqlDernieres = "SELECT c.nom, c.email, c.status, p.title as projet
                 FROM candidature c
                 JOIN project p ON c.idProject = p.idProject
                 ORDER BY c.idCandidature DESC
                 LIMIT 5";
$dernieresCandidatures = $pdo->query($sqlDernieres)->fetchAll(PDO::FETCH_ASSOC);

// 6. Projets sans candidatures (LEFT JOIN IS NULL)
$sqlSansCandidat = "SELECT p.title FROM project p LEFT JOIN candidature c ON p.idProject = c.idProject WHERE c.idCandidature IS NULL";
$projetsSansCandidat = $pdo->query($sqlSansCandidat)->fetchAll(PDO::FETCH_ASSOC);
?>

    <!-- ========== HEADER ========== -->
    <div class="dashboard-header">
        <div>
            <div class="page-title">📊 Tableau de bord</div>
            <div class="subtitle">Statistiques en temps réel — projets et candidatures</div>
        </div>
        <div class="actions">
            <a class="btn-primary" href="listProjects.php">📁 Gérer projets</a>
            <a class="btn-primary btn-orange" href="listCandidatures.php">📝 Gérer candidatures</a>
        </div>
    </div>

    <!-- ========== ALERTE CANDIDATURES EN ATTENTE ========== -->
    <?php if($enAttente > 0): ?>
    <div class="notification-bar">
        <div>🔔 <strong><?= $enAttente ?> candidature(s) en attente</strong> - Besoin de votre attention</div>
        <a href="listCandidatures.php?filter=en_attente" style="background:#FF9800; color:white; padding:8px 18px; border-radius:5px; text-decoration:none; font-weight:bold;">Traiter maintenant →</a>
    </div>
    <?php endif; ?>

    <!-- ========== 4 CARTES STATISTIQUES ========== -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="icon">👥</div>
            <div>
                <h3><?= number_format($totalUsers) ?></h3>
                <p>Utilisateurs inscrits</p>
            </div>
        </div>

        <div class="stat-card" style="background: linear-gradient(135deg,#ff7a18,#ff3d67);">
            <div class="icon">📁</div>
            <div>
                <h3><?= $totalProjets ?></h3>
                <p>Projets totaux</p>
                <small><?= $projetsActifs ?> actifs</small>
            </div>
        </div>

        <div class="stat-card" style="background: linear-gradient(135deg,#8e2de2,#4a00e0);">
            <div class="icon">📝</div>
            <div>
                <h3><?= $totalCandidatures ?></h3>
                <p>Candidatures reçues</p>
                <small><?= $enAttente ?> en attente</small>
            </div>
        </div>

        <div class="stat-card" style="background: linear-gradient(135deg,#11998e,#38ef7d);">
            <div class="icon">✅</div>
            <div>
                <h3><?= $tauxConversion ?>%</h3>
                <p>Taux d'acceptation</p>
                <small><?= $acceptes ?> acceptés / <?= $refuses ?> refusés</small>
            </div>
        </div>
    </div>

    <!-- ========== 2 GRAPHIQUES CÔTE À CÔTE ========== -->
    <div class="grid-2">
        <!-- Graphique barres -->
        <div class="card-light">
            <h4>📊 Candidatures par projet</h4>
            <canvas id="barChart" style="height: 300px; width: 100%;"></canvas>
        </div>

        <!-- Graphique camembert -->
        <div class="card-light">
            <h4>🥧 Répartition des statuts</h4>
            <canvas id="pieChart" style="height: 300px; width: 100%;"></canvas>
        </div>
    </div>

    <!-- ========== 3 CARTES MÉTIER ========== -->
    <div class="grid-3">
        <!-- Top 3 projets -->
        <div class="card-light">
            <h4>🏆 Top 3 projets populaires</h4>
            <?php if(empty($top3)): ?>
                <p style="color:#999; text-align:center; padding:20px;">Aucune candidature encore</p>
            <?php else: ?>
                <?php $medailles = ['🥇', '🥈', '🥉']; ?>
                <?php foreach($top3 as $index => $projet): ?>
                    <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 0; border-bottom:1px solid #eee;">
                        <div>
                            <span style="font-size:24px;"><?= $medailles[$index] ?></span>
                            <strong style="margin-left:10px;"><?= htmlspecialchars($projet['title']) ?></strong>
                        </div>
                        <span class="badge badge-green"><?= $projet['nb'] ?> candidat(s)</span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <!-- Dernières candidatures -->
        <div class="card-light">
            <h4>⏳ Dernières candidatures</h4>
            <?php if(empty($dernieresCandidatures)): ?>
                <p style="color:#999; text-align:center; padding:20px;">Aucune candidature encore</p>
            <?php else: ?>
                <?php foreach($dernieresCandidatures as $cand): ?>
                    <div style="padding:10px 0; border-bottom:1px solid #eee;">
                        <div><strong><?= htmlspecialchars($cand['nom']) ?></strong> → <?= htmlspecialchars($cand['projet']) ?></div>
                        <div style="margin-top:5px;">
                            <?php if($cand['status'] == 'accepte'): ?>
                                <span class="badge badge-green">✅ Accepté</span>
                            <?php elseif($cand['status'] == 'refuse'): ?>
                                <span class="badge badge-red">❌ Refusé</span>
                            <?php else: ?>
                                <span class="badge badge-orange">⏳ En attente</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            <a href="listCandidatures.php" style="display:block; margin-top:15px; color:#0077b6; text-decoration:none; text-align:center;">📋 Voir toutes les candidatures →</a>
        </div>

        <!-- Projets sans candidatures -->
        <div class="card-light">
            <h4>⚠️ Projets sans candidature</h4>
            <?php if(empty($projetsSansCandidat)): ?>
                <div style="text-align:center; padding:20px; color:#4CAF50;">
                    ✅ Tous les projets ont des candidats !
                </div>
            <?php else: ?>
                <?php foreach($projetsSansCandidat as $projet): ?>
                    <div style="padding:10px 0; border-bottom:1px solid #eee; color:#f44336;">
                        ⚠️ <?= htmlspecialchars($projet['title']) ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

</div>

<script>
// Attendre que la page soit complètement chargée
document.addEventListener('DOMContentLoaded', function() {
    
    // Données pour les graphiques
    const barLabels = <?= json_encode(array_column($statsProjets, 'title')) ?>;
    const barData = <?= json_encode(array_column($statsProjets, 'nb')) ?>;
    
    const pieLabels = <?= json_encode(array_column($statsStatus, 'status_label')) ?>;
    const pieData = <?= json_encode(array_column($statsStatus, 'nb')) ?>;
    
    // Couleurs pour le camembert
    const pieColors = [];
    for(let i = 0; i < pieLabels.length; i++) {
        if(pieLabels[i] === 'Accepté') pieColors.push('#4CAF50');
        else if(pieLabels[i] === 'Refusé') pieColors.push('#f44336');
        else pieColors.push('#FF9800');
    }
    
    // Graphique à barres
    if(barLabels.length > 0) {
        const barCtx = document.getElementById('barChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: barLabels,
                datasets: [{
                    label: 'Nombre de candidatures',
                    data: barData,
                    backgroundColor: '#00b4d8',
                    borderRadius: 8,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'top' },
                    tooltip: { 
                        callbacks: { 
                            label: function(tooltipItem) { 
                                return tooltipItem.raw + ' candidature(s)'; 
                            } 
                        } 
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        stepSize: 1, 
                        title: { display: true, text: 'Nombre de candidatures', font: { weight: 'bold' } },
                        grid: { color: '#e0e0e0' }
                    },
                    x: { 
                        title: { display: true, text: 'Projets', font: { weight: 'bold' } }
                    }
                }
            }
        });
    } else {
        document.getElementById('barChart').style.display = 'none';
        document.getElementById('barChart').insertAdjacentHTML('afterend', '<p style="text-align:center; padding:50px; color:#999;">Aucune donnée disponible</p>');
    }
    
    // Graphique camembert
    if(pieLabels.length > 0) {
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: pieLabels,
                datasets: [{
                    data: pieData,
                    backgroundColor: pieColors,
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: { 
                        callbacks: { 
                            label: function(tooltipItem) { 
                                let total = pieData.reduce((a,b) => a + b, 0);
                                let pourcentage = Math.round((tooltipItem.raw / total) * 100);
                                return tooltipItem.raw + ' candidature(s) (' + pourcentage + '%)'; 
                            } 
                        } 
                    }
                }
            }
        });
    } else {
        document.getElementById('pieChart').style.display = 'none';
        document.getElementById('pieChart').insertAdjacentHTML('afterend', '<p style="text-align:center; padding:50px; color:#999;">Aucune donnée disponible</p>');
    }
});
</script>

</body>
</html>