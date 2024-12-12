<?php
include '../../config/database.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete the author from the database
    $stmt = $pdo->prepare("DELETE FROM auteurs WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        // Redirect to the authors list page after successful deletion
        header("Location: list-auteurs.php?success=1");
        exit;
    } else {
        echo "Erreur lors de la suppression de l'auteur.";
    }
} else {
    echo "Aucun ID d'auteur fourni.";
}
