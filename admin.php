<?php
session_start();

// Check if the user is logged in as an admin
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    if (basename($_SERVER['PHP_SELF']) !== 'admin.php') {
        header("Location: admin.php");
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
    <title>Admin page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="final.css">
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
                            <a class="nav-link active" href="admin.php">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="readme_admin.php">Tutorial</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="studentslist.php">List of students</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="backend/logout.php">Log out</a>
                        </li>
                        <li class="nav-item">
                            <a href="slovak/admin_sk.php">
                                <img src="Flag_of_Slovakia.png" alt="Slovak Flag" style="height:30px; width:45px;">
                            </a>
                        </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container2">
        <h1>Welcome Admin. You are the boss!</h1>
        <button style="margin-top:5px" class="round-btn btn-block" onclick="uploadExercises()">Upload exercises</button>
    </div>
    <script>
    function uploadExercises() {
        $.ajax({
            url: 'backend/fetchskuska.php',
            type: 'POST',
            success: function(response) {
                // Handle the success response if needed
                console.log(response);
            },
            error: function(xhr, status, error) {
                // Handle the error response if needed
                console.log(error);
            }
        });
    }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
