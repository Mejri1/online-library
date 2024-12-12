<?php
include '../../config/database.php';

$userId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Fetch user's information
$stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = :id");
$stmt->execute(['id' => $userId]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch borrowing history for the user
$stmtHistory = $pdo->prepare("
    SELECT l.titre, e.date_emprunt, e.date_retour
    FROM emprunts e
    JOIN livres l ON e.id_livre = l.id
    WHERE e.id_utilisateur = :id
");
$stmtHistory->execute(['id' => $userId]);
$borrowHistory = $stmtHistory->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique de l'Utilisateur</title>
    <link rel="stylesheet" href="../../assets/css/history-user.css">
    <style>
        /* Styling for the history page */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #2c3e50;
            padding: 10px 20px;
            text-align: center;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            font-size: 1.1rem;
            margin: 0 15px;
        }

        .navbar a:hover {
            color: #1abc9c;
        }

        .container {
            max-width: 900px;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #2c3e50;
        }

        .user-info {
            font-size: 1.2rem;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 30px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #2c3e50;
            color: white;
        }

        td {
            background-color: #f9f9f9;
        }

        .back-btn {
    background-color: #3498db;
    color: white;
    padding: 10px 20px;
    margin-top: 30px; /* Increased the margin-top value */
    text-decoration: none;
    border-radius: 5px;
    font-size: 1.1rem;
}

.back-btn:hover {
    background-color: #2980b9;
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
    <h1>Historique de l'Utilisateur: <?= htmlspecialchars($user['nom']); ?> <?= htmlspecialchars($user['prenom']); ?></h1>

    <div class="user-info">
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
        <p><strong>Role:</strong> <?= htmlspecialchars($user['role']); ?></p>
    </div>

    <h2>Historique des Emprunts</h2>

    <?php if (count($borrowHistory) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Titre du Livre</th>
                    <th>Date d'Emprunt</th>
                    <th>Date de Retour</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($borrowHistory as $history): ?>
                    <tr>
                        <td><?= htmlspecialchars($history['titre']); ?></td>
                        <td><?= htmlspecialchars($history['date_emprunt']); ?></td>
                        <td><?= htmlspecialchars($history['date_retour']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucun emprunt trouvé pour cet utilisateur.</p>
    <?php endif; ?>

    <div class="back-btn-container">
        <a href="list-users.php" class="back-btn">Retour à la liste des utilisateurs</a>
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
            margin-top:auto; /* Pushes footer to the bottom */
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
