<?php include("../header.php"); ?>

<?php
include_once "../../controller/ProjetController.php";

$controller = new ProjetController();
$liste = $controller->afficher();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Liste des projets</title>

    <style>
        body {
            font-family: Arial;
        }

        /* NAVIGATION */
        .nav {
            margin-bottom: 20px;
        }

        .nav a {
            padding: 10px;
            color: white;
            text-decoration: none;
            margin-right: 10px;
            border-radius: 5px;
        }

        .dashboard { background: #007bff; }
        .add { background: green; }

        /* TABLE */
        table {
            border-collapse: collapse;
            width: 80%;
            margin-top: 20px;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        a {
            text-decoration: none;
        }
    </style>
</head>

<body>

<h2>Liste des projets</h2>

<!-- 🔥 NAVIGATION PROPRE -->
<div class="nav">
    <a href="dashboard.php" class="dashboard">Accueil</a>
    <a href="ajouterProjet.php" class="add">+ Ajouter projet</a>
</div>

<table border="1">
<tr>
    <th>ID</th>
    <th>Nom</th>
    <th>Description</th>
    <th>Date début</th>
    <th>Date fin</th>
    <th>Statut</th>
    <th>Actions</th>
</tr>

<?php foreach ($liste as $row) { ?>
<tr>
    <td><?= $row['id']; ?></td>
    <td><?= $row['nom']; ?></td>
    <td><?= $row['description']; ?></td>
    <td><?= $row['date_debut']; ?></td>
    <td><?= $row['date_fin']; ?></td>
    <td><?= $row['statut']; ?></td>
    <td>
        <a href="modifierProjet.php?id=<?= $row['id']; ?>">Modifier</a> |
        <a href="supprimerProjet.php?id=<?= $row['id']; ?>" 
           onclick="return confirm('Supprimer ce projet ?')">
           Supprimer
        </a>
    </td>
</tr>
<?php } ?>

</table>

</body>
</html>

<?php include("../footer.php"); ?>