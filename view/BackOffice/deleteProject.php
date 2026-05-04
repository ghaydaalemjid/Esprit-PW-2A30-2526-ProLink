<?php
require_once '../../controller/ProjectC.php';

$pc = new ProjectC();
$pc->deleteProject($_GET['id']);

header('Location:listProjects.php');
?>