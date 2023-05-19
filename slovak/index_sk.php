<?php
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Math App</title>
    <link rel="stylesheet" href="../style_form.css">
    <script src="../backend/script.js"></script>
</head>
<body>
    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form action="../backend/register.php" method="POST">
                <h1>Vytvoriť Účet</h1>
                <span>prosím zadaj svoje osobné údaje</span>
                <input type="text" name="firstname" id="firstname" placeholder="Meno" required/>
                <input type="text" name="lastname" id="lastname" placeholder="Priezvisko" required/>
                <input type="text" name="username" id="username" placeholder="Užívateľské meno" required/>
                <input type="number" name="studentID" id="studentID" placeholder="Číslo Študenta" required/>
                <input type="password" name="password" id="password" placeholder="Heslo" required/>
                <button type="submit" name="register">Zaregistrovať sa</button>
            </form>
        </div>
        <div class="form-container sign-in-container">
            <form action="../backend/login.php" method="POST">
                <h1>Prihlás sa</h1>
                <span>alebo použi svoj účet</span>
                <input type="text" name="username" id="username" placeholder="Užívateľské meno" required/>
                <input type="password" name="password" id="password" placeholder="Heslo" required/>
                <button type="submit" name="login">Prihlásiť Sa</button>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Vitaj Späť!</h1>
                    <p>Aby si ostal s nami v kontakte prosím vyplň svoje údaje</p>
                    <button class="ghost" id="signIn">Prihlásiť sa</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Ahoj, Kamoš!</h1>
                    <p>Zadaj svoje osobné údaje a vydaj sa na cestu s nami!</p>
                    <button class="ghost" id="signUp">Zaregistrovať Sa!</button>
                </div>
            </div>
        </div>     
    </div>
</body>
</html>