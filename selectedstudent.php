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
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $username = $_POST['username'];
        $studentID = $_POST['studentID'];

        // Update the user data in the database
        $stmt = $db->prepare("UPDATE users SET first_name = :first_name, last_name = :last_name, username = :username, studentID = :studentID WHERE id = :id");
        $stmt->bindParam(":first_name", $firstName, PDO::PARAM_STR);
        $stmt->bindParam(":last_name", $lastName, PDO::PARAM_STR);
        $stmt->bindParam(":username", $username, PDO::PARAM_STR);
        $stmt->bindParam(":studentID", $studentID, PDO::PARAM_STR);
        $stmt->bindParam(":id", $userId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $message = "User information updated successfully.";
        } else {
            $message = "Failed to update user information.";
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
        </head>
        <body>
            <h1>Edit User</h1>
            <?php if (!empty($message)) : ?>
                <p><?php echo $message; ?></p>
            <?php endif; ?>
            <form method="POST">
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" value="<?php echo $user['first_name']; ?>"><br>
                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" value="<?php echo $user['last_name']; ?>"><br>
                <label for="username">Username:</label>
                <input type="text" name="username" value="<?php echo $user['username']; ?>"><br>
                <label for="studentID">Student ID:</label>
                <input type="text" name="studentID" value="<?php echo $user['studentID']; ?>"><br>
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

