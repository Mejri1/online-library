<?php
include '../../config/database.php';

// Get the author ID from the URL parameter
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the author's current data from the database
    $stmt = $pdo->prepare("SELECT * FROM auteurs WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $auteur = $stmt->fetch(PDO::FETCH_ASSOC);

    // If the author doesn't exist
    if (!$auteur) {
        die("Auteur non trouvé.");
    }

    // Handle form submission for editing
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nom = $_POST['nom'];
        $biographie = $_POST['biographie'] ?? '';
        $photo = $_FILES['photo']['name'] ?? $auteur['photo'];  // Keep the old photo if no new one is uploaded

        // Handle photo upload if a new photo is provided
        if ($photo && $photo != $auteur['photo']) {
            // Set up the upload folder
            $target_dir = "../../uploads/";
            $target_file = $target_dir . basename($photo);

            // Attempt to upload the new photo
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
                // If successful, we update the photo in the database
                $photo_uploaded = $photo;
            } else {
                die("Failed to upload the photo.");
            }
        } else {
            // If no new photo is provided, keep the old one
            $photo_uploaded = $auteur['photo'];
        }

        // Update the author data in the database
        $stmt = $pdo->prepare("UPDATE auteurs SET nom = :nom, biographie = :biographie, photo = :photo WHERE id = :id");
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':biographie', $biographie);
        $stmt->bindParam(':photo', $photo_uploaded);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: list-authors.php?message=edit_success");
            exit;
        } else {
            die("Error updating the author.");
        }
    }
} else {
    die("ID not provided.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Auteur</title>
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

<div class="container">
        <h1>Modifier l'Auteur</h1>
        <form method="POST" action="" enctype="multipart/form-data">
            <input type="text" name="nom" value="<?= htmlspecialchars($auteur['nom']); ?>" placeholder="Nom de l'auteur" required><br>
            <textarea name="biographie" placeholder="Biographie (facultatif)"><?= htmlspecialchars($auteur['biographie']); ?></textarea><br>
            <img src="../../uploads/<?= htmlspecialchars($auteur['photo']); ?>" alt="Photo de l'auteur" width="100"><br>
            <input type="file" name="photo"><br>
            <button type="submit">Enregistrer</button>
            <a href="list-authors.php">Retour</a>
        </form>
    </div>
     <!-- Footer Div -->
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
