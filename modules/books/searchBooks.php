<?php
include '../../config/database.php';

// Get the search query from the URL
$query = isset($_GET['query']) ? $_GET['query'] : '';

// Initialize an array to store the filtered books
$filtered_books = [];

if ($query) {
    // Prepare a SQL query to fetch books that match the search query
    $stmt = $pdo->prepare("SELECT id, titre, genre, auteur_id, disponibilite FROM Livres WHERE titre LIKE :query");
    $stmt->execute(['query' => '%' . $query . '%']);
    $filtered_books = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // If no query, fetch all books
    $stmt = $pdo->query("SELECT id, titre, genre, auteur_id, disponibilite FROM Livres");
    $filtered_books = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Return the filtered books as JSON
echo json_encode($filtered_books);
?>
