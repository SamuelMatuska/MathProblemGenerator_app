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
    <title>Math App</title>
    <link rel="stylesheet" href="style_form.css">
    <script src="backend/script.js"></script>
</head>
<body>
    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form action="backend/register.php" method="POST">
                <h1>Create Account</h1>
                <span>or use your email for registration</span>
                <input type="text" name="firstname" id="firstname" placeholder="First name" required/>
                <input type="text" name="lastname" id="lastname" placeholder="Last name" required/>
                <input type="text" name="username" id="username" placeholder="Username" required/>
                <input type="number" name="studentID" id="studentID" placeholder="Student ID" required/>
                <input type="password" name="password" id="password" placeholder="Password" required/>
                <button type="submit" name="register">Sign Up</button>
            </form>
        </div>
        <div class="form-container sign-in-container">
            <form action="backend/login.php" method="POST">
                <h1>Sign in</h1>
                <span>or use your account</span>
                <input type="text" name="username" id="username" placeholder="Username" required/>
                <input type="password" name="password" id="password" placeholder="Password" required/>
                <button type="submit" name="login">Sign In</button>
            </form>
        </div>
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Welcome Back!</h1>
                    <p>To keep connected with us please login with your personal info</p>
                    <button class="ghost" id="signIn">Sign In</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Hello, Friend!</h1>
                    <p>Enter your personal details and start journey with us</p>
                    <button class="ghost" id="signUp">Sign Up</button>
                </div>
            </div>
        </div>     
    </div>
</body>
</html>