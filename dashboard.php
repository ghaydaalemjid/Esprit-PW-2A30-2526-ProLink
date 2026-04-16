<?php
include_once "../../controller/ProjetController.php";

$controller = new ProjetController();

$total = $controller->countAll();
$accepted = $controller->countByStatut("accepté");
$refused = $controller->countByStatut("refusé");
?>

<?php include("../header.php"); ?>

<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f6f9;
    margin: 0;
}

/* Titre principal */
h1 {
    text-align: center;
    font-size: 36px;
    font-weight: 700;
    color: #2c3e50;
    margin-top: 20px;
}

/* Sous titre */
h2 {
    text-align: center;
    font-size: 26px;
    font-weight: 600;
    color: #34495e;
    margin-top: 10px;
}

/* Dashboard container */
.dashboard {
    display: flex;
    justify-content: center;
    gap: 30px;
    margin-top: 40px;
}

/* Cards */
.card {
    width: 240px;
    padding: 30px;
    border-radius: 15px;
    color: white;
    text-align: center;
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    transition: 0.3s;
}

.card:hover {
    transform: translateY(-5px);
}

/* Colors */
.blue {
    background: linear-gradient(135deg, #1e90ff, #00c6ff);
}

.green {
    background: linear-gradient(135deg, #28a745, #7bed9f);
}

.red {
    background: linear-gradient(135deg, #e74c3c, #ff6b6b);
}

/* Texte dans card */
.card h3 {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 15px;
}

.card p {
    font-size: 36px;
    font-weight: bold;
}

/* Bouton */
.btn {
    display: block;
    width: 230px;
    margin: 40px auto;
    padding: 14px;
    text-align: center;
    background: #2c3e50;
    color: white;
    border-radius: 10px;
    text-decoration: none;
    font-size: 16px;
    font-weight: bold;
    transition: 0.3s;
}

.btn:hover {
    background: #000;
}
</style>



<div class="dashboard">

    <div class="card blue">
        <h3>Total projets</h3>
        <p><?= $total['total']; ?></p>
    </div>

    <div class="card green">
        <h3>Acceptés</h3>
        <p><?= $accepted['total']; ?></p>
    </div>

    <div class="card red">
        <h3>Refusés</h3>
        <p><?= $refused['total']; ?></p>
    </div>

</div>

<a class="btn" href="afficherProjet.php">Gérer les projets</a>

<?php include("../footer.php"); ?>