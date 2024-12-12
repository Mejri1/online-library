<?php
include '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titre = $_POST['titre'];
    $genre = $_POST['genre'];
    $auteur_id = $_POST['auteur_id'];

    // Insert the new book into the database
    $stmt = $pdo->prepare("INSERT INTO Livres (titre, genre, auteur_id) VALUES (:titre, :genre, :auteur_id)");
    $stmt->bindParam(':titre', $titre);
    $stmt->bindParam(':genre', $genre);
    $stmt->bindParam(':auteur_id', $auteur_id);
    if ($stmt->execute()) {
        header("Location: list-books.php?message=success");
        exit;
    } else {
        die("Error adding the book.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Livre</title>
    <link rel="stylesheet" href="../../assets/css/book.css">
</head>
<body>
<style>/* General Reset */
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
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    text-align: center;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

h1 {
    font-size: 2.5rem;
    color: #2c3e50;
    margin-bottom: 30px;
    font-weight: bold;
    font-family: 'Georgia', serif;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
}

/* Form Styling */
form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

form input {
    padding: 10px;
    font-size: 1rem;
    border: 1px solid #ddd;
    border-radius: 5px;
    transition: border-color 0.3s ease;
}

form input:focus {
    border-color: #3498db;
    outline: none;
}

.submit-btn {
    padding: 10px 20px;
    font-size: 1rem;
    font-weight: bold;
    color: #fff;
    background-color: #3498db;
    border: none;
    border-radius: 5px;
    transition: background-color 0.3s ease, transform 0.3s ease;
    cursor: pointer;
}

.submit-btn:hover {
    background-color: #2980b9;
    transform: scale(1.05);
}

.back-btn {
    text-decoration: none;
    color: #2c3e50;
    font-size: 1rem;
    font-weight: bold;
    padding: 10px 20px;
    border: 1px solid #2c3e50;
    border-radius: 5px;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.back-btn:hover {
    background-color: #2c3e50;
    color: #fff;
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
    .container {
        max-width: 100%;
        padding: 15px;
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
</style>
<div class="header-div">
        <h1>Online Library</h1>
        <div class="navbar-links">
            <a href="..\..\index.php">Home</a>
            <a href="..\books\list-books.php">Books</a>
            <a href="..\authors\list-authors.php">Authors</a>
            <a href="..\users\list-users.php">Users</a>
        </div>
        <style>/* Header Div */
        .header-div {
            background-color: #2c3e50;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
        }

        /* Header Title (Online Library) */
        .header-div h1 {
            color: #fff;
            font-size: 2rem;
            font-weight: bold;
            margin: 0;
            font-family: 'Georgia', serif;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        /* Navbar Links */
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
        }</style>
</div>
<div class="container">
        <h1>Ajouter un Livre</h1>
        <form method="POST" action="">
            <input type="text" name="titre" placeholder="Titre du livre" required><br>
            <input type="text" name="genre" placeholder="Genre" required><br>
            <input type="number" name="auteur_id" placeholder="ID de l'Auteur" required><br>
            <button type="submit" class="submit-btn">Ajouter</button>
            <a href="list-books.php" class="back-btn">Retour</a>
        </form>
    </div>
    <div class="footer-div">
        <p>&copy; 2024 Online Library. All Rights Reserved.</p>
        <a href="#">Privacy Policy</a> | 
        <a href="#">Terms of Service</a>
        <style> /* Footer Div */
        .footer-div {
            background-color: #2c3e50;
            color: #fff;
            text-align: center;
            padding: 20px;
            margin-top: auto; /* Pushes footer to the bottom */
        }

        .footer-div a {
            color: #1abc9c;
            text-decoration: none;
            font-size: 1rem;
            transition: color 0.3s ease;
        }

        .footer-div a:hover {
            color: #16a085;
        }

        .footer-div p {
            font-size: 1rem;
            margin: 10px 0;
        }</style>
    </div>
</body>
</html>
