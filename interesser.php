<?php
session_start();
if (!isset($_SESSION['user'])) {
    echo json_encode(['status' => 'error', 'message' => 'Non connecté']);
    exit;
}

$user_email = $_SESSION['user'];
$event_id = $_POST['event_id'];

$conn = new mysqli('localhost', 'root', '', 'event');
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Connexion échouée']);
    exit;
}

// Empêcher les doublons
$sql_check = "SELECT * FROM interested_users WHERE event_id = ? AND user_email = ?";
$stmt = $conn->prepare($sql_check);
$stmt->bind_param("is", $event_id, $user_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $sql = "INSERT INTO interested_users (event_id, user_email) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $event_id, $user_email);
    $stmt->execute();
}

echo json_encode(['status' => 'success']);
