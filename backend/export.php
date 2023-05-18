<?php
require_once 'connection.php';

$stmt = $db->query("SELECT first_name, last_name, username, studentID, right_answer, answered FROM users WHERE studentID != 187");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($users);
?>