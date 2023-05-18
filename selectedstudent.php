<?php
session_start();

// Check if the user is logged in as an admin
if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    if (basename($_SERVER['PHP_SELF']) !== 'selectedstudent.php') {
        header("Location: selectedstudent.php");
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

// Define a variable to store any success or error messages
$message = '';

// Get the user ID from the URL
if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Check if the form is submitted for updating user data
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve the submitted data
        $type = $_POST['type'];

        // Update the user data in the database
        $stmt = $db->prepare("UPDATE users SET type = :type WHERE id = :id");
        $stmt->bindParam(":type", $type, PDO::PARAM_STR);
        $stmt->bindParam(":id", $userId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $message = "User type updated successfully.";
        } else {
            $message = "Failed to update user type.";
        }
    }

    // Fetch the user data for the given ID
    $stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->bindParam(":id", $userId, PDO::PARAM_INT);
    $stmt->execute(); 

    // Retrieve the user data
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch the folder names from the math_problems table
    $stmtFolders = $db->prepare("SELECT DISTINCT folder_name FROM math_problems");
    $stmtFolders->execute();
    $folders = $stmtFolders->fetchAll(PDO::FETCH_COLUMN);

    if ($user) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edit Student</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <style>
                .round-btn {
                    border-radius: 20px;
                    border: 1px solid #FF4B2B;
                    background: linear-gradient(to right, rgb(255, 75, 43), #FF416C);
                    color: white;  /* Make the button text white */
                }
                .boxy-div {
                    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                    padding: 20px;
                    border-radius: 5px;
                }
            </style>
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
                            <a href="slovak/selectedstudent_sk.php?id=<?php echo $user['id']; ?>">
                                <img src="Flag_of_Slovakia.png" alt="Slovak Flag" style="height:30px; width:45px;">
                            </a>
                        </li>
                </ul>
            </div>
        </div>
        </nav>
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-6 boxy-div text-center">
                    <h1 class="mb-4">Edit User</h1>
                    <?php if (!empty($message)) : ?>
                    <p><?php echo $message; ?></p>
                    <?php endif; ?>
                    <form method="POST" class="my-4">
                    <label for="first_name">First Name:</label>
                        <span><?php echo $user['first_name']; ?></span><br>
                        <label for="last_name">Last Name:</label>
                        <span><?php echo $user['last_name']; ?></span><br>
                        <label for="type">Type:</label>
                        <select name="type">
                            <?php foreach ($folders as $folder) : ?>
                            <option value="<?php echo $folder; ?>" <?php if ($user['type'] === $folder) echo 'selected'; ?>><?php echo $folder; ?></option>
                            <?php endforeach; ?>
                        </select><br>
                        <button type="submit" class="round-btn btn-block">Update</button>
                    </form>
                </div>
            </div>
        </div>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
        </body>
        </html>
        <?php
    } else {
        // Handle case where user is not found
        echo "User not found.";
    }
} else {
    // Handle case where ID is not provided
    echo "Invalid user ID.";
}
// Close the database connection
$db = null;
?>
