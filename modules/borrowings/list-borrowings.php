<?php
include '../../config/database.php';

$stmt = $pdo->prepare("SELECT e.id_emprunt, l.titre, u.nom, u.prenom, e.date_emprunt, e.date_retour_prevu, e.date_retour_reel
                       FROM emprunts e
                       JOIN livres l ON e.id_livre = l.id
                       JOIN utilisateurs u ON e.id_utilisateur = u.id");
$stmt->execute();
$borrowings = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Emprunts</title>
</head>
<body>
 <style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Arial', sans-serif;
    background-color: #f9f9f9;
    color: #333;
    line-height: 1.6;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
}

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

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
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

.add-borrowing-btn {
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
}

.add-borrowing-btn:hover {
    background-color: #2980b9;
    transform: scale(1.05);
}

.borrowing-card {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.borrowing-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
}

.borrowing-card h3 {
    font-size: 1.5rem;
    color: #2c3e50;
    margin-bottom: 10px;
}

.borrowing-card p {
    font-size: 1rem;
    color: #555;
    margin-bottom: 15px;
}

.actions {
    display: flex;
    justify-content: center;
    gap: 10px;
}

.btn-return {
    padding: 10px 20px;
    font-size: 1rem;
    font-weight: bold;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    background-color: #3498db;
    color: white;
}

.btn-return:hover {
    background-color: #2980b9;
}

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

.authors-container {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-top: 20px;
}

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
    <h1>Liste des Emprunts</h1>
    <a href="add-borrowing.php" class="add-borrowing-btn">Ajouter un Emprunt</a>

    <div class="authors-container">
        <?php foreach ($borrowings as $borrowing): ?>
            <div class="borrowing-card">
                <h3><?= htmlspecialchars($borrowing['titre']); ?></h3>
                <p>Emprunté par: <?= htmlspecialchars($borrowing['nom']) . ' ' . htmlspecialchars($borrowing['prenom']); ?></p>
                <p>Date d'Emprunt: <?= htmlspecialchars($borrowing['date_emprunt']); ?></p>
                <p>Date de Retour Prévu: <?= htmlspecialchars($borrowing['date_retour_prevu']); ?></p>
                <p>Date de Retour Réel: <?= $borrowing['date_retour_reel'] ? htmlspecialchars($borrowing['date_retour_reel']) : 'Non retourné'; ?></p>
                <div class="actions">
                    <a href="return-book.php?id=<?= $borrowing['id_emprunt']; ?>" class="btn-return">Retourner</a>
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
