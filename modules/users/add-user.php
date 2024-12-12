<?php
include '../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];
    $role = $_POST['role'];

    // Insert the new user into the database
    $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role) VALUES (:nom, :prenom, :email, :mot_de_passe, :role)");
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':prenom', $prenom);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':mot_de_passe', password_hash($mot_de_passe, PASSWORD_DEFAULT)); // Password hashing
    $stmt->bindParam(':role', $role);

    if ($stmt->execute()) {
        header("Location: list-users.php?message=success");
        exit;
    } else {
        die("Error adding user.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Utilisateur</title>
    <link rel="stylesheet" href="../../assets/css/add-user.css">
</head>
<body>
    <style>
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
            background-color: #2c3e50;
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

        /* Header */
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

        .navbar-links a {
            color: #fff;
            text-decoration: none;
            font-size: 1.1rem;
            transition: color 0.3s ease;
        }

        .navbar-links a:hover {
            color: #1abc9c;
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
            color: #2c3e50;
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
        form input[type="email"],
        form input[type="password"],
        form select {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            box-sizing: border-box;
        }

        form button {
            background-color: #3498db;
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
            background-color: #2980b9;
            transform: translateY(-2px);
        }

        form a {
            text-align: center;
            color: #2c3e50;
            font-size: 16px;
            text-decoration: none;
            margin-top: 10px;
        }

        form a:hover {
            text-decoration: underline;
        }

        /* Footer */
        .footer {
            background-color: #2c3e50;
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

    <!-- Header and Navbar -->
    <div class="header-div">
        <h1>Online Library</h1>
        <div class="navbar-links">
            <a href="..\..\index.php">Home</a>
            <a href="..\books\list-books.php">Books</a>
            <a href="..\authors\list-authors.php">Authors</a>
            <a href="..\users\list-users.php">Users</a>
        </div>
    </div>

    <!-- Container for Content -->
    <div class="container">
        <h1>Ajouter un Utilisateur</h1>
        <form method="POST">
            <input type="text" name="nom" placeholder="Nom" required><br>
            <input type="text" name="prenom" placeholder="Prénom" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="mot_de_passe" placeholder="Mot de passe" required><br>
            <select name="role">
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select><br>
            <button type="submit">Ajouter</button>
            <a href="list-users.php">Retour</a>
        </form>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2024 Library Management System. All rights reserved.</p>
    </div>
</body>
</html>
