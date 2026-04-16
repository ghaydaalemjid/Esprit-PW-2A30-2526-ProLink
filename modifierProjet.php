<?php include("header.php"); ?>
<?php
include_once "../controller/ProjetController.php";
include_once "../model/Projet.php";

$controller = new ProjetController();

if (isset($_GET['id'])) {
    $projetData = $controller->recuperer($_GET['id']);
}

if (isset($_POST["nom"])) {
    $projet = new Projet(
        $_POST["nom"],
        $_POST["description"],
        $_POST["date_debut"],
        $_POST["date_fin"],
        $_POST["statut"]
    );

    $controller->modifier($projet, $_GET['id']);
    header("Location: afficherProjet.php");
}
?>

<h2>Modifier projet</h2>

<form method="POST">
Nom: <input type="text" name="nom" value="<?= $projetData['nom']; ?>"><br><br>

Description: <input type="text" name="description" value="<?= $projetData['description']; ?>"><br><br>

Date début: <input type="date" name="date_debut" value="<?= $projetData['date_debut']; ?>"><br><br>

Date fin: <input type="date" name="date_fin" value="<?= $projetData['date_fin']; ?>"><br><br>

Statut: <input type="text" name="statut" value="<?= $projetData['statut']; ?>"><br><br>

<button type="submit">Modifier</button>
</form>
<?php include("footer.php"); ?>