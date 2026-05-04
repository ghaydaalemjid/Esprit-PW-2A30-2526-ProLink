<?php
require_once '../../config/config.php';

$db = config::getConnexion();

$idProject = $_GET['id'];

$sql = "SELECT u.name
        FROM team_member tm
        JOIN user u ON tm.idUser = u.idUser
        JOIN team t ON tm.idTeam = t.idTeam
        WHERE t.idProject = $idProject";

$list = $db->query($sql);
?>

<h2>Equipe du projet</h2>

<?php foreach($list as $m) { ?>
    <p><?= $m['name'] ?></p>
<?php } ?>