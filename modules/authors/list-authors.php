<?php
include '../../config/database.php';

// Fetch all authors (auteurs) from the database
$stmt = $pdo->query("SELECT * FROM auteurs");
$auteurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Handle deleting an author
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    // Fetch the author's photo filename
    $stmt = $pdo->prepare("SELECT photo FROM auteurs WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $auteur = $stmt->fetch(PDO::FETCH_ASSOC);

    // If the author exists, proceed with deleting the record and the photo
    if ($auteur) {
        // Delete the photo from the upload folder if it exists
        $photo_path = "../../uploads/" . $auteur['photo'];
        if (file_exists($photo_path)) {
            unlink($photo_path); // Delete the photo
        }

        // Delete the author record from the database
        $stmt = $pdo->prepare("DELETE FROM auteurs WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // Redirect back to the authors list
        header("Location: list-authors.php?message=delete_success");
        exit;
    } else {
        die("Author not found.");
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Auteurs</title>
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

/* Add Author Button */
.add-author-btn {
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

.add-author-btn:hover {
    background-color: #2980b9;
    transform: scale(1.05);
}

/* Authors Container */
.authors-container {
    display: grid;
    grid-template-columns: repeat(3, 1fr); /* Three cards per row */
    gap: 20px;
    margin-top: 20px;
}

/* Author Card */
.author-card {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.author-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

.author-photo {
    width: 100%;
    height: 180px;
    object-fit: cover;
    border-radius: 10px;
    margin-bottom: 15px;
}

.author-card h3 {
    font-size: 1.5rem;
    color: #2c3e50;
    margin-bottom: 10px;
}

.author-card p {
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
    .authors-container {
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
        <h1>Liste des Auteurs</h1>
        <a href="add-author.php" class="add-author-btn">Ajouter Auteur</a>

        <!-- Authors Display -->
        <div class="authors-container">
            <?php foreach ($auteurs as $auteur): ?>
                <div class="author-card">
                    <img src="../../uploads/<?= htmlspecialchars($auteur['photo']); ?>" alt="Photo" class="author-photo">
                    <h3><?= htmlspecialchars($auteur['nom']); ?></h3>
                    <p><?= htmlspecialchars($auteur['biographie']); ?></p>
                    <div class="actions">
                        <button class="btn btn-edit" onclick="location.href='edit-author.php?id=<?= $auteur['id']; ?>'">Modifier</button>
                        <button class="btn btn-delete" onclick="if (confirm('Êtes-vous sûr de vouloir supprimer cet auteur ?')) location.href='list-authors.php?delete_id=<?= $auteur['id']; ?>'">Supprimer</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
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
