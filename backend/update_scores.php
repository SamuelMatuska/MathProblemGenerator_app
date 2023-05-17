<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require_once('connection.php');

$username = $_POST['username'];
$isCorrect = $_POST['isCorrect'];

if ($isCorrect) {
    $query = "UPDATE users SET right_answer = right_answer + 1, answered = answered + 1 WHERE username = ?";
} else {
    $query = "UPDATE users SET answered = answered + 1 WHERE username = ?";
}

// Prepare and execute the query
$stmt = $db->prepare($query);
$stmt->execute([$username]);

if ($stmt->rowCount() > 0) {
    $response = array('status' => 'success', 'message' => 'Score updated successfully');
    echo json_encode($response);
} else {
    $response = array('status' => 'error', 'message' => 'Failed to update score');
    echo json_encode($response);
}
?>
