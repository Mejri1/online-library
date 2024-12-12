<?php
include '../../config/database.php';

// Fetch all users
$stmt = $pdo->query("SELECT * FROM utilisateurs");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Utilisateurs</title>
    <link rel="stylesheet" href="../../assets/css/list-users.css">
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
    min-height: 100vh;
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
    margin: 20px 100px ;
    padding-left: 20px;
    padding-right: 20px;
    flex: 1;
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

/* Add User Button */
.add-user-btn {
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

.add-user-btn:hover {
    background-color: #2980b9;
    transform: scale(1.05);
}

/* Users Container */
.users-container {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-top: 20px;
}

/* User Card */
.user-card {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.user-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

.user-card h3 {
    font-size: 1.5rem;
    color: #2c3e50;
    margin-bottom: 10px;
}

.user-card p {
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
    margin-top: auto;
}

.footer p {
    font-size: 0.9rem;
    margin: 0;
}

/* Responsive Design */
@media (max-width: 768px) {
    .users-container {
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
        <h1>Liste des Utilisateurs</h1>
        <a href="add-user.php" class="add-user-btn">Ajouter un Utilisateur</a>
        <div class="users-container">
            <?php foreach ($users as $user): ?>
                <div class="user-card">
                    <h3><?= htmlspecialchars($user['nom']); ?> <?= htmlspecialchars($user['prenom']); ?></h3>
                    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
                    <p><strong>Role:</strong> <?= htmlspecialchars($user['role']); ?></p>
                    <div class="actions">
                        <button class="btn btn-edit" onclick="location.href='edit-user.php?id=<?= $user['id']; ?>'">Modifier</button>
                        <button class="btn btn-delete" onclick="if (confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?')) location.href='delete-user.php?id=<?= $user['id']; ?>'">Supprimer</button>
                        <button class="btn btn-history" onclick="location.href='history-user.php?id=<?= $user['id']; ?>'">Voir l'Historique</button>
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
