<?php
session_start();

// Check if the user is logged in as an admin
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    if (basename($_SERVER['PHP_SELF']) !== 'readme_admin.php') {
        header("Location: readme_admin.php");
        exit();
    }
} elseif (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // Check if the user is logged in as a basic user
    if (basename($_SERVER['PHP_SELF']) !== 'student.php') {
        header("Location: student.php"); 
        exit();
    }
} else {
    // If the user is not logged in, redirect them to the index.php page
    if (basename($_SERVER['PHP_SELF']) !== 'index.php') {
        header("Location: index.php");
        exit(); 
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Read me admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.debug.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="final.css">
    <script src="backend/pdf.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="#">Math Gen app</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="admin.php">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="readme_admin.php">Tutorial</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="studentslist.php">List of students</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="backend/logout.php">Log out</a>
                        </li>
                        <li class="nav-item">
                            <a href="slovak/readme_admin_sk.php">
                                <img src="Flag_of_Slovakia.png" alt="Slovak Flag" style="height:30px; width:45px;">
                            </a>
                        </li>
                </ul>
            </div>
        </div>
    </nav>
    <div id="center">
        <div class="container text-center" id="readme">
            <h1>How to basic, for teacher!</h1>
            <p style="overflow-wrap: break-word;">This is read me note for you to know how to operate in this app! In navigation bar you can access list of your students. There you can see all your students and how many points they already have from your assigments. 
            Also by clicking on students name you can assign him new math problems. On your profile page you can upload all folders of problems into database. Last thing, in navbar you have choice to change language and also to log out.
            </p>
            <br>
            <p>Enjoy :) !!</p>
        </div>
    </div>
    <div id="center">
        <button id="generatePDF">Generate PDF with instructions</button>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
