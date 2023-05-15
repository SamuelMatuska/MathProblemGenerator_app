<?php
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

    if ($user) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edit User</title>
            <link rel="stylesheet" href="style_form.css?v=<?php echo time(); ?>">

        </head>
        <body>
        <nav>
            <a href="admin.php">Admin home page</a>   
            <a href="studentslist.php">List of your Students</a> 
            <a href="logout.php"> LOG OUT</a>
        </nav>
            <h1>Edit User</h1>
            <?php if (!empty($message)) : ?>
                <p><?php echo $message; ?></p>
            <?php endif; ?>
            <form method="POST">
                <label for="first_name">First Name:</label>
                <span><?php echo $user['first_name']; ?></span><br>
                <label for="last_name">Last Name:</label>
                <span><?php echo $user['last_name']; ?></span><br>
                <label for="type">Type:</label>
                <input type="text" name="type" value="<?php echo $user['type']; ?>"><br>
                <button type="submit">Update</button>
            </form>
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
