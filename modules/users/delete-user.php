<?php
include '../../config/database.php';

// Get the user ID from the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete the user from the database
    $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = :id");
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        header("Location: list-users.php?message=delete_success");
        exit;
    } else {
        die("Error deleting user.");
    }
} else {
    die("ID not provided.");
}
?>
