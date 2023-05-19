<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require_once 'connection.php';

// Update Scores in user_math_problems Table
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['isCorrect'], $_POST['problemId'])) {
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
    
        // Check if a row already exists with the user_id and problem_id
        $stmt = $db->prepare("SELECT COUNT(*) FROM user_math_problems WHERE user_id = :userId AND problem_id = :problemId");
        $stmt->bindParam(":userId", $userId, PDO::PARAM_INT);
        $stmt->bindParam(":problemId", $problemId, PDO::PARAM_STR, 250);
        $stmt->execute();
        $rowExists = ($stmt->fetchColumn() > 0);
    
        if ($rowExists) {
            // Update the existing row
            $stmt = $db->prepare("UPDATE user_math_problems SET answered_correctly = :isCorrect WHERE user_id = :userId AND problem_id = :problemId");
            $stmt->bindParam(":isCorrect", $isCorrect, PDO::PARAM_INT);
        } else {
            // Insert a new row
            $stmt = $db->prepare("INSERT INTO user_math_problems (user_id, problem_id, answered_correctly) VALUES (:userId, :problemId, :isCorrect)");
            $stmt->bindParam(":isCorrect", $isCorrect, PDO::PARAM_INT);
        }
    
        $stmt->bindParam(":userId", $userId, PDO::PARAM_INT);
        $stmt->bindParam(":problemId", $problemId, PDO::PARAM_STR, 250);
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
}

// Update Scores in users Table
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['isCorrect'])) {
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
}
?>
