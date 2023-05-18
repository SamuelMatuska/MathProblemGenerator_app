<?php
require_once 'connection.php';

$username = $_POST['username'];
$isCorrect = $_POST['isCorrect'];
$problemId = $_POST['problemId'];

// Fetch the user's ID based on the username
$stmt = $db->prepare("SELECT id FROM users WHERE username = :username");
$stmt->bindParam(":username", $username, PDO::PARAM_STR);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    $userId = $user['id'];

    // Update the user_math_problems table
    $stmt = $db->prepare("INSERT INTO user_math_problems (user_id, problem_id, answered_correctly) VALUES (:userId, :problemId, :isCorrect)
                         ON DUPLICATE KEY UPDATE answered_correctly = VALUES(answered_correctly)");
    $stmt->bindParam(":userId", $userId, PDO::PARAM_INT);
    $stmt->bindParam(":problemId", $problemId, PDO::PARAM_INT);
    $stmt->bindParam(":isCorrect", $isCorrect, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $response = array('status' => 'success', 'message' => 'Score updated successfully');
        echo json_encode($response);
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to update score');
        echo json_encode($response);
    }
} else {
    $response = array('status' => 'error', 'message' => 'User not found');
    echo json_encode($response);
}
?>
