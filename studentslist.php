<?php
require_once 'backend/connection.php';

// Fetch data from the "users" table
$stmt = $db->query("SELECT id, first_name, last_name, username, studentID FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
</head>
<body>
    <table>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Username</th>
            <th>Student ID</th>
        </tr>
        <?php foreach ($users as $user) : 
            if ($user['studentID'] == 187) {
                continue;}?>
            <tr>
                <td><a href="selectedstudent.php?id=<?php echo $user['id']; ?>"><?php echo $user['first_name']; ?></a></td>
                <td><a href="selectedstudent.php?id=<?php echo $user['id']; ?>"><?php echo $user['last_name']; ?></a></td>
                <td><a href="selectedstudent.php?id=<?php echo $user['id']; ?>"><?php echo $user['username']; ?></a></td>
                <td><a href="selectedstudent.php?id=<?php echo $user['id']; ?>"><?php echo $user['studentID']; ?></a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>


