<?php
require_once 'backend/connection.php';

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

// Establish the database connection
$connection = mysqli_connect($hostname, $username, $password, $dbname);

// Check if the connection was successful
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
 
// Assuming your table is named "users" and you want to fetch the two integers for the current user
$query = "SELECT right_answer, answered FROM users WHERE studentID = {$_SESSION['studentID']}";
$result = mysqli_query($connection, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $rightAnswer = $row['right_answer'];
    $answered = $row['answered'];
} else {
    // Handle the case when user data is not found
    $rightAnswer = 0;
    $answered = 0;
}

// Remember to close the database connection
mysqli_close($connection);
?>

<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="style_form.css">
</head>
<body>
<nav>
    <a href="student.php">Student home page</a> 
    <a href="backend/math_problems.php">Excersises</a>   
    <a href="backend/logout.php"> LOG OUT</a>
</nav>
<h1 style="font-size:60px">Welcome, <?php echo htmlspecialchars($_SESSION['firstname'] . ' ' . $_SESSION['lastname']); ?>!</h1>
<?php
if($rightAnswer == 0 && $answered == 0) {
?>
    <h3 style="font-size:30px">You havent done any excercises yet. To start, head into the "Excersises" section please.</h3>
<?php
} else {
?>
    <h3 style="font-size:30px">You answered <?php echo $rightAnswer; ?>/<?php echo $answered; ?> questions correctly!</h3>
<?php
}
?>


<audio autoplay>
  <source src="welcome.mp3" type="audio/mpeg">
  Your browser does not support the audio element.
</audio>
</body>
</html>