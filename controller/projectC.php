<?php
require_once(__DIR__ . '/../config/config.php');

class ProjectC {
//sélectionne TOUS les projets.
    function listProjects() {
        $sql = "SELECT * FROM project";
        $db = Config::getConnexion();
        return $db->query($sql);
    }

    function addProject($project) {
        $sql = "INSERT INTO project (title, description, status) VALUES (?, ?, ?)";
        $db = Config::getConnexion();
        $db->prepare($sql)->execute([
            $project->getTitle(),
            $project->getDescription(),
            $project->getStatus()
        ]);
    }

    function deleteProject($id) {
        $sql = "DELETE FROM project WHERE idProject = ?";
        $db = Config::getConnexion();
        $db->prepare($sql)->execute([$id]);
    }

    function getProject($id) {
        $sql = "SELECT * FROM project WHERE idProject = ?";
        $db = Config::getConnexion();
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    function updateProject($project, $id) {
        $sql = "UPDATE project SET title=?, description=?, status=? WHERE idProject=?";
        $db = Config::getConnexion();
        $db->prepare($sql)->execute([
            $project->getTitle(),
            $project->getDescription(),
            $project->getStatus(),
            $id
        ]);
    }
}
?>