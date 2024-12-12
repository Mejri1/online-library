<?php
include '../../config/database.php';

// Fetch all books
$stmt = $pdo->prepare("SELECT id, titre FROM livres");
$stmt->execute();
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all users
$stmt2 = $pdo->prepare("SELECT id, nom, prenom FROM utilisateurs");
$stmt2->execute();
$users = $stmt2->fetchAll(PDO::FETCH_ASSOC);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_livre = $_POST['id_livre'];
    $id_utilisateur = $_POST['id_utilisateur'];
    $date_emprunt = $_POST['date_emprunt'];
    $date_retour_prevu = $_POST['date_retour_prevu'];

    // Check if the book is already borrowed
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM emprunts WHERE id_livre = :id_livre AND date_retour_prevu IS NULL");
    $stmt->bindParam(':id_livre', $id_livre);
    $stmt->execute();
    $isBorrowed = $stmt->fetchColumn();

    if ($isBorrowed > 0) {
        // Book is already borrowed
        $errorMessage = "Ce livre n'est pas disponible.";
    } else {
        // Book is available, proceed with the borrowing
        try {
            // Begin a transaction
            $pdo->beginTransaction();

            // Insert the borrow record into the "emprunts" table
            $stmt = $pdo->prepare("INSERT INTO emprunts (id_livre, id_utilisateur, date_emprunt, date_retour_prevu) 
                                   VALUES (:id_livre, :id_utilisateur, :date_emprunt, :date_retour_prevu)");
            $stmt->bindParam(':id_livre', $id_livre);
            $stmt->bindParam(':id_utilisateur', $id_utilisateur);
            $stmt->bindParam(':date_emprunt', $date_emprunt);
            $stmt->bindParam(':date_retour_prevu', $date_retour_prevu);

            if ($stmt->execute()) {
                // Update the book's availability to 0 (unavailable)
                $stmt = $pdo->prepare("UPDATE livres 
                SET disponibilite = 0, date_retour = :date_retour 
                WHERE id = :id_livre");
$stmt->bindParam(':date_retour', $date_retour_prevu);
$stmt->bindParam(':id_livre', $id_livre);
$stmt->execute();

                // Commit the transaction
                $pdo->commit();

                // Redirect to the borrowings list with a success message
                header("Location: list-borrowings.php?message=success");
                exit;
            } else {
                // Rollback the transaction in case of error
                $pdo->rollBack();
                die("Erreur lors de l'ajout de l'emprunt.");
            }
        } catch (Exception $e) {
            // Rollback the transaction in case of error
            $pdo->rollBack();
            die("Erreur lors de l'emprunt : " . $e->getMessage());
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Emprunt</title>
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

        /* Header Div */
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

        form select,
        form input[type="date"] {
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
        /* Retour Button */
a.btn-back {
    background-color: #6c757d;
    color: white;
    text-align: center;
    display: inline-block;
    padding: 10px 20px;
    border-radius: 5px;
    font-size: 1.1rem;
    margin-top: 20px;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

a.btn-back:hover {
    background-color: #5a6268;
}

    </style>

    <!-- Header Div -->
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
        <h1>Emprunter un Livre</h1>
        <?php if (!empty($errorMessage)): ?>
            <div class="error-message"><?= htmlspecialchars($errorMessage); ?></div>
        <?php endif; ?>
        <form method="POST">
            <select name="id_livre" required>
                <option value="">Sélectionner un Livre</option>
                <?php foreach ($books as $book): ?>
                    <option value="<?= $book['id']; ?>"><?= htmlspecialchars($book['titre']); ?></option>
                <?php endforeach; ?>
            </select>

            <select name="id_utilisateur" required>
                <option value="">Sélectionner un Utilisateur</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?= $user['id']; ?>">
                        <?= htmlspecialchars($user['nom'] . ' ' . $user['prenom']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <input type="date" name="date_emprunt" required>

            <input type="date" name="date_retour_prevu" required>

            <button type="submit">Emprunter</button>
        </form>
        <a href="list-borrowings.php " class="btn-back">Retour</a>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2024 Library Management System. All rights reserved.</p>
    </div>
</body>
</html>
