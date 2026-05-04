<?php
require_once '../../config/config.php';

$db = config::getConnexion();

if(isset($_POST['status'])) {
    $status = $_POST['status'];
    $sql = "SELECT * FROM project WHERE status='$status'";
    $projects = $db->query($sql);
}
?>

<h2>Recherche projets</h2>

<form method="POST">
    Status:
    <select name="status">
        <option value="actif">Actif</option>
        <option value="termine">Terminé</option>
    </select>
    <button type="submit">Rechercher</button>
</form>

<?php if(isset($projects)) { ?>
    <?php foreach($projects as $p) { ?>
        <p><?= $p['title'] ?></p>
    <?php } ?>
<?php } ?>