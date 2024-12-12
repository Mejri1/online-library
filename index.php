<?php include 'includes/header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Library</title>
</head>
<body>
    <style>/* Reset and Basic Styles */
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
    min-height: 100vh; /* Ensure body takes full height */
    display: flex;
    flex-direction: column;
}

/* Container */
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    text-align: center;
    flex: 1; /* Ensures content grows between header and footer */
}

/* Header Styling */
h1 {
    font-size: 2.5rem;
    color: #2c3e50;
    margin-bottom: 20px;
    font-weight: bold;
    font-family: 'Georgia', serif;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
}

/* Paragraph */
p {
    font-size: 1.1rem;
    color: #555;
    margin-bottom: 30px;
}

/* Buttons */
.actions {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
    margin-top: 20px;
}

.btn {
    display: inline-block;
    text-decoration: none;
    color: #fff;
    background-color: #3498db;
    padding: 12px 20px;
    font-size: 1rem;
    font-weight: bold;
    border-radius: 5px;
    transition: all 0.3s ease;
}

.btn:hover {
    background-color: #2980b9;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
}

/* Navbar */
.navbar {
    background-color: #2c3e50;
    display:flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 20px;
}

/* Navbar Title */
.navbar h1 {
    color: #fff;
    font-size: 1.8rem;
    font-weight: bold;
    margin: 0;
    font-family: 'Georgia', serif;
}

/* Navbar Links */
.navbar a {
    color: #fff;
    text-decoration: none;
    margin: 0 10px;
    font-size: 1rem;
    transition: color 0.3s ease;
}

.navbar a:hover {
    color: #1abc9c;
}

/* Footer */
footer {
    background-color: #2c3e50;
    color: #fff;
    text-align: center;
    padding: 10px 20px;
    font-size: 0.9rem;
    margin-top: auto; /* Pushes footer to the bottom */
}

footer a {
    color: #1abc9c;
    text-decoration: none;
    transition: color 0.3s ease;
}

footer a:hover {
    color: #16a085;
}

/* Responsive Design */
@media (max-width: 768px) {
    .navbar {
        flex-direction: column;
        text-align: center;
    }

    .navbar a {
        margin: 5px 0;
    }

    .btn {
        font-size: 0.9rem;
        padding: 10px 15px;
    }

    h1 {
        font-size: 2rem;
    }

    p {
        font-size: 1rem;
    }
}


</style>
<div class="navbar">
    <h1>Online Library</h1>
    <div>
        <a href="#">Home</a>
        <a href="#">Books</a>
        <a href="#">Authors</a>
        <a href="#">Users</a>
    </div>
</div>

    <div class="container">
        <h1>Welcome to the Online Library</h1>
        <p>Manage books, authors, users, and borrowings seamlessly.</p>
        <div class="actions">
            <a href="modules/authors/list-authors.php" class="btn">Manage Authors</a>
            <a href="modules/books/list-books.php" class="btn">Manage Books</a>
            <a href="modules/users/list-users.php" class="btn">Manage Users</a>
            <a href="modules/borrowings/list-borrowings.php" class="btn">Manage Borrowings</a>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
