<?php
require_once '../backend/connection.php';

session_start();

// Check if the user is logged in as an admin
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    if (basename($_SERVER['PHP_SELF']) !== 'admin_sk.php') {
        header("Location: admin_sk.php");
        exit();
    }
} elseif (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // Check if the user is logged in as a basic user
    if (basename($_SERVER['PHP_SELF']) !== 'student_sk.php') {
        header("Location: student_sk.php");
        exit();
    }
} else {
    // If the user is not logged in, redirect them to the index.php page
    if (basename($_SERVER['PHP_SELF']) !== 'index_sk.php') {
        header("Location: index_sk.php");
        exit();
    }
}

require_once '../backend/fetch_user_data.php';
?>

<!DOCTYPE html> 
<html lang="sk">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Študentský profil</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link rel="stylesheet" href="../final.css">
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
                            <a class="nav-link active" href="student_sk.php">Profil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="readme_student_sk.php">Návod</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../math_problems_sk.php">Príklady</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../backend/logout.php">Odhlásiť sa</a>
                        </li>
                        <li class="nav-item">
                            <a href="../student.php">
                                <img src="../Flag_of_the_United_Kingdom.svg" alt="English Flag" style="height:30px; width:45px;">
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div id="container">
        <h1 style="font-size:60px">Vitaj, <?php echo htmlspecialchars($_SESSION['firstname'] . ' ' . $_SESSION['lastname']); ?>!</h1>
        <?php
        if($rightAnswer == 0 && $answered == 0) {
        ?>
            <h3 style="font-size:30px">Zaťiaľ si neurobil žiadne tebe priradené úlohy. Ak chceš začať choď do časti "Úlohy".</h3>
            
        <?php
        } else {
        ?>
            <h3 style="font-size:30px">Zodpovedal si <?php echo $rightAnswer; ?>/<?php echo $answered; ?> otázok správne!</h3>
        <?php
        }
        ?>
        </div>
        <audio autoplay>
        <source src="welcome.mp3" type="audio/mpeg">
        Your browser does not support the audio element.
        </audio>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    </body>
</html>