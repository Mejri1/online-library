<?php
include '../../config/database.php';

// Handle deleting a book
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    // Delete the book record from the database
    $stmt = $pdo->prepare("DELETE FROM Livres WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Redirect back to the books list
    header("Location: list-books.php?message=delete_success");
    exit;
}
?>
