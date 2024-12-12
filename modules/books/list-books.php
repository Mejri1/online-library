<?php
include '../../config/database.php';

// Handle the search query
if (isset($_GET['query']) && !empty($_GET['query'])) {
    $query = $_GET['query'];

    // Prepare and execute the query to fetch books matching the search
    $stmt = $pdo->prepare("
        SELECT Livres.id, Livres.titre, Livres.genre, Livres.auteur_id, Livres.disponibilite, emprunts.date_retour_prevu 
        FROM Livres
        LEFT JOIN emprunts ON Livres.id = emprunts.id_livre AND emprunts.date_retour_reel IS NULL
        WHERE Livres.titre LIKE :query
    ");
    $stmt->bindValue(':query', $query . '%', PDO::PARAM_STR); // Search for books starting with the query
    $stmt->execute();
    $livres = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return books in JSON format for the AJAX call
    echo json_encode($livres);
    exit;
}

// Fetch all books initially if there's no search query
$stmt = $pdo->query("
    SELECT Livres.id, Livres.titre, Livres.genre, Livres.auteur_id, Livres.disponibilite, emprunts.date_retour_prevu 
    FROM Livres
    LEFT JOIN emprunts ON Livres.id = emprunts.id_livre AND emprunts.date_retour_reel IS NULL
");
$livres = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Livres</title>
    <link rel="stylesheet" href="../../assets/css/book.css">
    <style>
/* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body Styling */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f9f9f9;
    color: #333;
    line-height: 1.6;
    min-height: 100vh; /* Ensure full height for layout */
    display: flex;
    flex-direction: column;
}

/* Navbar (Same as index.php header) */
.navbar {
    background-color: #2c3e50;
    display: flex;
    justify-content: center;
    padding: 10px 20px;
    gap: 15px;
}

.navbar a {
    color: #fff;
    text-decoration: none;
    font-size: 1rem;
    font-weight: bold;
    padding: 10px 15px;
    transition: background-color 0.3s ease, color 0.3s ease;
    border-radius: 5px;
}

.navbar a:hover {
    background-color: #1abc9c;
    color: #fff;
}

/* Container */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    flex: 1; /* Flex-grow for content */
    text-align: center;
}

h1 {
    font-size: 2.5rem;
    color: #2c3e50;
    margin-bottom: 30px;
    font-weight: bold;
    font-family: 'Georgia', serif;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
}

/* Add Book Button */
.add-book-btn {
    display: inline-block;
    margin-bottom: 20px;
    padding: 10px 20px;
    font-size: 1rem;
    font-weight: bold;
    color: #fff;
    background-color: #3498db;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    transition: background-color 0.3s ease, transform 0.3s ease;
    cursor: pointer;
}

.add-book-btn:hover {
    background-color: #2980b9;
    transform: scale(1.05);
}

/* Books Container */
.books-container {
    display: grid;
    grid-template-columns: repeat(3, 1fr); /* Three cards per row */
    gap: 20px;
    margin-top: 20px;
}

/* Book Card */
.book-card {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.book-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

.book-card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 10px;
    margin-bottom: 15px;
}

.book-card h3 {
    font-size: 1.5rem;
    color: #2c3e50;
    margin-bottom: 10px;
}

.book-card p {
    font-size: 1rem;
    color: #555;
    margin-bottom: 15px;
}

/* Actions Buttons */
.actions {
    display: flex;
    justify-content: center;
    gap: 10px;
}

.btn {
    padding: 10px 20px;
    font-size: 1rem;
    font-weight: bold;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.btn-edit {
    background-color: #3498db;
    color: #fff;
}

.btn-edit:hover {
    background-color: #2980b9;
    transform: scale(1.05);
}

.btn-delete {
    background-color: #e74c3c;
    color: #fff;
}

.btn-delete:hover {
    background-color: #c0392b;
    transform: scale(1.05);
}

/* Footer */
.footer {
    background-color: #2c3e50;
    color: #fff;
    text-align: center;
    padding: 10px 20px;
    margin-top: auto; /* Pushes footer to the bottom */
}

.footer p {
    font-size: 0.9rem;
    margin: 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .books-container {
        grid-template-columns: 1fr;
    }

    .navbar {
        flex-wrap: wrap;
        gap: 10px;
    }

    .navbar a {
        font-size: 0.9rem;
        padding: 8px 10px;
    }
}
.search-container {
            margin: 20px auto;
            text-align: center;
            max-width: 600px;
        }

        .search-bar {
            padding: 12px 20px;
            font-size: 1.1rem;
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
            box-sizing: border-box;
        }

        .autocomplete-suggestions {
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fff;
            position: absolute;
            z-index: 10;
            width: calc(100% - 2px); /* Match the width of the search bar */
            display: none;
        }

        .suggestion-item {
            padding: 10px;
            cursor: pointer;
        }

        .suggestion-item:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>
    <div class="header-div">
        <h1>Online Library</h1>
        <div class="navbar-links">
            <a href="..\..\index.php">Home</a>
            <a href="..\books\list-books.php">Books</a>
            <a href="..\authors\list-authors.php">Authors</a>
            <a href="..\users\list-users.php">Users</a>
        </div>
        <style>
            .header-div {
                background-color: #2c3e50;
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px 20px;
            }
            .header-div h1 {
                color: #fff;
                font-size: 2rem;
                font-weight: bold;
                margin: 0;
                font-family: 'Georgia', serif;
                text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
            }
            .header-div .navbar-links {
                display: flex;
                gap: 20px;
            }
            .header-div .navbar-links a {
                color: #fff;
                text-decoration: none;
                font-size: 1.1rem;
                transition: color 0.3s ease;
            }
            .header-div .navbar-links a:hover {
                color: #1abc9c;
            }
        </style>
    </div>

    <div class="container">
        <h1>Liste des Livres</h1>
        <a href="add-book.php" class="add-book-btn">Ajouter un Livre</a>
    </div>

    <!-- Search Bar HTML -->
    <div class="search-container">
        <input type="text" id="search-book" class="search-bar" placeholder="Search for books..." autocomplete="off">
    </div>

    <div class="books-container" id="books-container">
        <!-- Books will be dynamically loaded here -->
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Function to fetch all books or filtered books
            function fetchBooks(query = '') {
                $.ajax({
                    url: 'searchBooks.php', // PHP script to handle the search
                    method: 'GET',
                    data: { query: query },
                    dataType: 'json',
                    success: function(data) {
                        var booksContainer = $('#books-container');
                        booksContainer.empty(); // Clear previous results

                        if (data.length > 0) {
                            data.forEach(function(book) {
                                // Display each book in a card format with edit and delete buttons
                                booksContainer.append(`
                                    <div class="book-card">
                                        <h3>${book.titre}</h3>
                                        <p><strong>Genre:</strong> ${book.genre}</p>
                                        <p><strong>Auteur:</strong> ${book.auteur_id}</p>
                                        <p><strong>Disponibilité:</strong> ${book.disponibilite == 0 ? 'Non disponible' : 'Disponible'}</p>
                                        <div class="actions">
                                            <button class="btn btn-edit" onclick="location.href='edit-book.php?id=${book.id}'">Modifier</button>
                                            <button class="btn btn-delete" onclick="if (confirm('Êtes-vous sûr de vouloir supprimer ce livre ?')) location.href='list-books.php?delete_id=${book.id}'">Supprimer</button>
                                        </div>
                                    </div>
                                `);
                            });
                        } else {
                            booksContainer.append('<p>No books found</p>');
                        }
                    }
                });
            }

            // Initial fetch to show all books when the page loads
            fetchBooks();

            // Listen for input in the search bar
            $('#search-book').on('input', function() {
                var query = $(this).val(); // Get the search query
                fetchBooks(query); // Fetch books based on the query
            });
        });
    </script>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2024 Online Library. All Rights Reserved.</p>
        <a href="#">Privacy Policy</a> | 
        <a href="#">Terms of Service</a>
    </div>
</body>
</html>
