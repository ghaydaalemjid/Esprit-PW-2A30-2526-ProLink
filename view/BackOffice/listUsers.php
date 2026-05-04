<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ⚠️ chemin + casse correcte
require_once('../../controller/UserP.php');

$userP = new UserP();
$list = $userP->listUsers();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des utilisateurs</title>
</head>

<body>

<?php include 'sidebar.php'; ?>

<div class="main-content">
    <div class="container">

        <div class="topbar">
            <div class="page-title">Liste des utilisateurs</div>
            <div class="actions">
                <input class="search-input" placeholder="Rechercher..." id="searchInput">
                <a href="addUser.php" class="btn btn-primary">+ Ajouter</a>
            </div>
        </div>

        <table class="table-modern" id="usersTable">
            <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Type</th>
                <th>Age</th>
                <th>Actions</th>
            </tr>
            </thead>

            <tbody>
            <?php if($list) { foreach ($list as $user) { ?>
                <tr>
                    <td><?= htmlspecialchars($user['idUser']); ?></td>
                    <td><?= htmlspecialchars($user['name']); ?></td>
                    <td><?= htmlspecialchars($user['prenom'] ?? ''); ?></td>
                    <td><?= htmlspecialchars($user['email']); ?></td>
                    <td><?= htmlspecialchars($user['role']); ?></td>
                    <td><?= htmlspecialchars($user['age'] ?? ''); ?></td>

                    <td>
                        <a class="btn btn-secondary" href="detailUser.php?id=<?= $user['idUser']; ?>">Voir</a>
                        <a class="btn btn-secondary" href="updateUser.php?id=<?= $user['idUser']; ?>">Modifier</a>
                        <a class="btn btn-danger js-delete" href="#" data-href="deleteUser.php?id=<?= $user['idUser']; ?>">Supprimer</a>
                    </td>
                </tr>
            <?php }} ?>
            </tbody>
        </table>

    </div>
</div>

<script>
document.getElementById('searchInput').addEventListener('input', function(e){
    const q = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#usersTable tbody tr');
    rows.forEach(r => {
        r.style.display = Array.from(r.cells).some(c => c.textContent.toLowerCase().includes(q)) ? '' : 'none';
    });
});
</script>

</body>
</html>