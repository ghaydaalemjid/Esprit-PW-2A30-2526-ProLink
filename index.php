<?php
include_once "../../controller/ProjetController.php";

$controller = new ProjetController();
$liste = $controller->afficher();
?>

<?php include("../header.php"); ?>

<h2>Projets disponibles</h2>

<table border="1" cellpadding="10">
<tr>
    <th>Nom</th>
    <th>Description</th>
    <th>Statut</th>
</tr>

<?php foreach ($liste as $p) { ?>
<tr>
    <td><?= $p['nom']; ?></td>
    <td><?= $p['description']; ?></td>
    <td><?= $p['statut']; ?></td>
</tr>
<?php } ?>

</table>

<?php include("../footer.php"); ?>