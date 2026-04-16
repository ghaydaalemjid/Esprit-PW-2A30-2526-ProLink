<?php include("../header.php"); ?>

<?php
include_once "../../controller/ProjetController.php";
include_once "../../model/Projet.php";

if (isset($_POST['nom'])) {

    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $statut = $_POST['statut'];

    // Validation PHP (sécurité)
    if (!empty($nom) && !empty($description) && !empty($date_debut) && !empty($date_fin) && !empty($statut)) {

        if ($date_fin > $date_debut) {

            $projet = new Projet($nom, $description, $date_debut, $date_fin, $statut);
            $controller = new ProjetController();
            $controller->ajouter($projet);

            header("Location: afficherProjet.php");
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ajouter projet</title>

    <style>
        body { font-family: Arial; }

        input {
            margin-bottom: 10px;
            padding: 8px;
            border-radius: 5px;
        }

        button {
            padding: 8px 15px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
        }

        .error {
            color: red;
            font-size: 12px;
            margin-left: 10px;
        }

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
        .list { background: green; }
    </style>
</head>

<body>

<h2>Gestion des projets</h2>

<div class="nav">
    <a href="dashboard.php" class="dashboard">Accueil</a>
    <a href="afficherProjet.php" class="list">Voir projets</a>
</div>

<h3>Ajouter un projet</h3>

<form method="POST" id="formProjet">

Nom:
<input type="text" id="nom" name="nom">
<span id="errorNom" class="error"></span><br>

Description:
<input type="text" id="description" name="description">
<span id="errorDesc" class="error"></span><br>

Date début:
<input type="text" id="date_debut" name="date_debut" placeholder="YYYY-MM-DD">
<span id="errorDebut" class="error"></span><br>

Date fin:
<input type="text" id="date_fin" name="date_fin" placeholder="YYYY-MM-DD">
<span id="errorFin" class="error"></span><br>

Statut:
<input type="text" id="statut" name="statut">
<span id="errorStatut" class="error"></span><br>

<button type="submit">Ajouter</button>

</form>

<!-- VALIDATION JS -->
<script>

// validation au submit
document.getElementById("formProjet").addEventListener("submit", function(e) {

    let valid = true;

    let nom = document.getElementById("nom");
    let description = document.getElementById("description");
    let date_debut = document.getElementById("date_debut");
    let date_fin = document.getElementById("date_fin");
    let statut = document.getElementById("statut");

    document.querySelectorAll(".error").forEach(e => e.innerText = "");
    document.querySelectorAll("input").forEach(e => e.style.border = "");

    let regex = /^\d{4}-\d{2}-\d{2}$/;

    if (nom.value.trim() === "") {
        document.getElementById("errorNom").innerText = "Nom obligatoire";
        nom.style.border = "2px solid red";
        valid = false;
    }

    if (description.value.trim() === "") {
        document.getElementById("errorDesc").innerText = "Description obligatoire";
        description.style.border = "2px solid red";
        valid = false;
    }

    if (!regex.test(date_debut.value)) {
        document.getElementById("errorDebut").innerText = "Format: YYYY-MM-DD";
        date_debut.style.border = "2px solid red";
        valid = false;
    }

    if (!regex.test(date_fin.value)) {
        document.getElementById("errorFin").innerText = "Format: YYYY-MM-DD";
        date_fin.style.border = "2px solid red";
        valid = false;
    }

    if (regex.test(date_debut.value) && regex.test(date_fin.value)) {
        let d1 = new Date(date_debut.value);
        let d2 = new Date(date_fin.value);

        if (d2 <= d1) {
            document.getElementById("errorFin").innerText = "Date fin > début";
            date_fin.style.border = "2px solid red";
            valid = false;
        }
    }

    if (statut.value.trim() === "") {
        document.getElementById("errorStatut").innerText = "Statut obligatoire";
        statut.style.border = "2px solid red";
        valid = false;
    }

    if (!valid) e.preventDefault();
});


// validation en temps réel (live)
function clearError(inputId, errorId) {
    let input = document.getElementById(inputId);
    let error = document.getElementById(errorId);

    input.addEventListener("input", function () {
        if (input.value.trim() !== "") {
            input.style.border = "2px solid green";
            error.innerText = "";
        }
    });
}

clearError("nom", "errorNom");
clearError("description", "errorDesc");
clearError("statut", "errorStatut");

function clearDate(inputId, errorId) {
    let input = document.getElementById(inputId);
    let error = document.getElementById(errorId);

    input.addEventListener("input", function () {
        let regex = /^\d{4}-\d{2}-\d{2}$/;

        if (regex.test(input.value)) {
            input.style.border = "2px solid green";
            error.innerText = "";
        }
    });
}

clearDate("date_debut", "errorDebut");
clearDate("date_fin", "errorFin");

</script>

</body>
</html>

<?php include("../footer.php"); ?>