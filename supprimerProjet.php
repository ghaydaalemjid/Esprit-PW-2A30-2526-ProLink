<?php
include_once "../controller/ProjetController.php";

if (isset($_GET['id'])) {
    $controller = new ProjetController();
    $controller->supprimer($_GET['id']);

    header("Location: afficherProjet.php");
}
?>