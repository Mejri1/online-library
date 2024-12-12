<?php
include '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $biographie = $_POST['biographie'] ?? '';
    $photo = $_FILES['photo']['name'];

    // Handle photo upload
    if ($photo) {
        $target_dir = "../../uploads/";
        $target_file = $target_dir . basename($photo);
        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            die("Failed to upload the photo.");
        }
    }

    // Insert the new author into the database
    $stmt = $pdo->prepare("INSERT INTO auteurs (nom, biographie, photo) VALUES (:nom, :biographie, :photo)");
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':biographie', $biographie);
    $stmt->bindParam(':photo', $photo);
    if ($stmt->execute()) {
        header("Location: list-authors.php?message=success");
        exit;
    } else {
        die("Error adding the author.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Auteur</title>
</head>
<body>
    <style>/* Reset and Basic Styles */
/* Reset and Basic Styles */
body {
    margin: 0;
    padding: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f4f9;
    color: #333;
}

/* Navbar */
.navbar {
    background-color: #2c3e50; /* Updated color */
    padding: 15px 20px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.navbar a {
    color: white;
    text-decoration: none;
    font-size: 18px;
    font-weight: bold;
    margin: 0 15px;
    transition: color 0.3s ease;
}

.navbar a:hover {
    color: #f0ad4e;
}

/* Container */
.container {
    max-width: 800px;
    margin: 50px auto;
    background: white;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    padding: 30px;
}

.container h1 {
    text-align: center;
    color: #2c3e50; /* Updated color */
    font-size: 28px;
    margin-bottom: 20px;
    font-weight: bold;
}

/* Form Styles */
form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

form input[type="text"],
form textarea,
form input[type="file"] {
    width: 100%;
    padding: 12px;
    font-size: 16px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    box-sizing: border-box;
}

form textarea {
    height: 120px;
    resize: none;
}

form button {
    background-color: #3498db; /* Updated button color */
    color: white;
    font-size: 18px;
    font-weight: bold;
    border: none;
    border-radius: 5px;
    padding: 12px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

form button:hover {
    background-color: #2980b9; /* Updated hover color */
    transform: translateY(-2px);
}

form a {
    text-align: center;
    color: #2c3e50; /* Updated color */
    font-size: 16px;
    text-decoration: none;
    margin-top: 10px;
}

form a:hover {
    text-decoration: underline;
}

/* Image Styling */
form img {
    display: block;
    margin: 10px auto;
    border-radius: 5px;
}

/* Footer */
.footer {
    background-color: #2c3e50; /* Updated color */
    color: white;
    text-align: center;
    padding: 15px 0;
    position: relative;
    bottom: 0;
    width: 100%;
    font-size: 14px;
}

/* Responsive Adjustments */
@media (max-width: 600px) {
    .container {
        padding: 20px;
    }

    form button {
        font-size: 16px;
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

    <!-- Container for Content -->
    <div class="container">
        <h1>Ajouter un Auteur</h1>
        <form method="POST" action="" enctype="multipart/form-data">
            <input type="text" name="nom" placeholder="Nom de l'auteur" required><br>
            <textarea name="biographie" placeholder="Biographie (facultatif)"></textarea><br>
            <input type="file" name="photo" required><br>
            <button type="submit">Ajouter</button>
            <a href="list-authors.php" >Retour</a>
        </form>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2024 Library Management System. All rights reserved.</p>
    </div>
</body>
</html>
