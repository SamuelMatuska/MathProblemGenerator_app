<?php

require_once 'connection.php';
// Assuming your table is named "users" and you want to fetch the two integers for the current user
$stmt = $db->prepare("SELECT right_answer, answered FROM users WHERE studentID = :studentID");
$stmt->execute(['studentID' => $_SESSION['studentID']]);

if ($stmt->rowCount() > 0) {
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $rightAnswer = $row['right_answer'];
    $answered = $row['answered'];
} else {
    // Handle the case when user data is not found
    $rightAnswer = 0;
    $answered = 0;
}
?>
