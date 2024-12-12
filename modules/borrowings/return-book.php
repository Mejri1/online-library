<?php
include '../../config/database.php';

// Get the borrowing ID from the URL
if (isset($_GET['id'])) {
    $id_emprunt = $_GET['id'];

    // Fetch the borrowing details, including book title and user name
    $stmt = $pdo->prepare("
        SELECT emprunts.*, livres.titre, utilisateurs.nom, utilisateurs.prenom
        FROM emprunts
        JOIN livres ON emprunts.id_livre = livres.id
        JOIN utilisateurs ON emprunts.id_utilisateur = utilisateurs.id
        WHERE emprunts.id_emprunt = :id_emprunt
    ");
    $stmt->bindParam(':id_emprunt', $id_emprunt);
    $stmt->execute();
    $emprunt = $stmt->fetch(PDO::FETCH_ASSOC);

    // If the borrowing record doesn't exist
    if (!$emprunt) {
        die("Emprunt non trouvé.");
    }

    // If the book is already returned
    if ($emprunt['date_retour_reel'] !== NULL) {
        die("Le livre a déjà été retourné.");
    }

    // Handle the return process
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $date_retour_reel = date('Y-m-d');

        // Update the return date for the borrowing
        $stmt = $pdo->prepare("UPDATE emprunts SET date_retour_reel = :date_retour_reel WHERE id_emprunt = :id_emprunt");
        $stmt->bindParam(':date_retour_reel', $date_retour_reel);
        $stmt->bindParam(':id_emprunt', $id_emprunt);

        if ($stmt->execute()) {
            // Now update the book's availability and reset the return date in the "livres" table
            $stmt = $pdo->prepare("UPDATE livres SET disponibilite = 1, date_retour = NULL WHERE id = :id_livre");
            $stmt->bindParam(':id_livre', $emprunt['id_livre']);
            $stmt->execute();

            // Redirect to the borrowings list with a success message
            header("Location: list-borrowings.php?message=return_success");
            exit;
        } else {
            die("Erreur lors du retour du livre.");
        }
    }
} else {
    die("ID emprunt manquant.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retourner un Livre</title>
    <link rel="stylesheet" href="../../assets/css/borrowings.css">
</head>
<body>
    <style>
        /* General Styling */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
            font-size: 2em;
            margin-bottom: 20px;
        }

        p {
            font-size: 1.1em;
            line-height: 1.6;
            margin: 8px 0;
        }

        p strong {
            color: #555;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }

        form button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 1.1em;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #0056b3;
        }

        form a {
            margin-top: 15px;
            text-decoration: none;
            color: #007bff;
            font-size: 1.1em;
            font-weight: bold;
        }

        form a:hover {
            color: #0056b3;
        }

        /* Confirm text styling */
        .confirm-text {
            font-size: 1.2em;
            text-align: center;
            color: #555;
        }
    </style>

    <div class="container">
        <h1>Retourner un Livre</h1>
        <p><strong>Nom du Livre:</strong> <?= htmlspecialchars($emprunt['titre']); ?></p>
        <p><strong>Nom de l'Utilisateur:</strong> <?= htmlspecialchars($emprunt['nom']) . ' ' . htmlspecialchars($emprunt['prenom']); ?></p>
        <p><strong>Date d'Emprunt:</strong> <?= htmlspecialchars($emprunt['date_emprunt']); ?></p>
        <p><strong>Date Prévue de Retour:</strong> <?= htmlspecialchars($emprunt['date_retour_prevu']); ?></p>

        <form method="POST" action="">
            <p class="confirm-text">Êtes-vous sûr de vouloir retourner ce livre?</p>
            <button type="submit">Retourner le Livre</button>
            <a href="list-borrowings.php">Retour</a>
        </form>
    </div>
</body>
</html>
