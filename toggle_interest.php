<?php
session_start();
if (!isset($_SESSION['user'])) {
    echo "non connecté";
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'event');
if ($conn->connect_error) {
    die("Erreur de connexion: " . $conn->connect_error);
}

$user = $_SESSION['user'];
$event_id = $_POST['event_id'];

// Récupérer l’ID de l’utilisateur connecté
$userResult = $conn->query("SELECT id FROM user WHERE username = '$user'");
if ($userResult->num_rows == 0) {
    echo "Utilisateur introuvable";
    exit;
}
$userRow = $userResult->fetch_assoc();
$user_id = $userRow['id'];

// Vérifier si l'utilisateur a déjà marqué son intérêt
$checkSql = "SELECT * FROM interested_users WHERE user_id = $user_id AND event_id = $event_id";
$checkResult = $conn->query($checkSql);

if ($checkResult->num_rows > 0) {
    // Il a déjà marqué intérêt => on le retire
    $conn->query("DELETE FROM interested_users WHERE user_id = $user_id AND event_id = $event_id");
    echo "removed";
} else {
    // Il ne l’a pas encore fait => on l’ajoute
    $conn->query("INSERT INTO interested_users (user_id, event_id) VALUES ($user_id, $event_id)");
    echo "added";
}

$conn->close();
?>
