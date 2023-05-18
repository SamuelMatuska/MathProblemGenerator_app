<?php
session_start();

// Check if the user is logged in as an admin
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    if (basename($_SERVER['PHP_SELF']) !== 'studentslist.php') {
        header("Location: studentslist.php");
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

require_once 'backend/connection.php'; 

// Fetch data from the "users" table
$stmt = $db->query("SELECT id, first_name, last_name, username, studentID, right_answer, answered FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of students</title> 
    <link rel="stylesheet" href="list.css?v=<?php echo time(); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                        <a class="nav-link active" href="studentslist.php">List of students</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/backend/logout.php">Log out</a>
                    </li>
                    <li class="nav-item">
                            <a href="slovak/studentslist_sk.php">
                                <img src="Flag_of_Slovakia.png" alt="Slovak Flag" style="height:30px; width:45px;">
                            </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <table class="table table-striped table-hover">
            <thead>
                <tr class="text-center">
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Username</th>
                    <th>Student ID</th>
                    <th>Right answers</th>
                    <th>From total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user) : 
                    if ($user['studentID'] == 187) {
                        continue;}?>
                    <tr class="text-center">
                        <td><a href="selectedstudent.php?id=<?php echo $user['id']; ?>"><?php echo $user['first_name']; ?></a></td>
                        <td><?php echo $user['last_name']; ?></a></td>
                        <td><?php echo $user['username']; ?></a></td>
                        <td><?php echo $user['studentID']; ?></a></td>
                        <td><?php echo $user['right_answer']; ?></a></td>
                        <td><?php echo $user['answered']; ?></a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>


