<?php
session_start();

// Connexion à la base de données
$conn = new mysqli('localhost', 'root', '', 'event');
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: connexion.php");
    exit();
}

// Vérifier si l'ID de l'événement est passé en paramètre
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$eventId = $_GET['id'];

// Récupérer l'événement depuis la base de données
$sql = "SELECT * FROM evenements WHERE id = $eventId";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Événement non trouvé.";
    exit();
}

$event = $result->fetch_assoc();

// Traitement du formulaire de modification
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $conn->real_escape_string($_POST['nom']);
    $date = $conn->real_escape_string($_POST['date_event']);
    $lieu = $conn->real_escape_string($_POST['lieu']);
    $desc = $conn->real_escape_string($_POST['description']);

    if (!empty($nom) && !empty($date) && !empty($lieu)) {
        $updateSql = "UPDATE evenements SET nom = '$nom', date_event = '$date', lieu = '$lieu', description = '$desc' WHERE id = $eventId";
        if ($conn->query($updateSql)) {
            header("Location: index.php");
            exit();
        } else {
            echo "Erreur lors de la mise à jour de l'événement.";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier l'événement</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 600px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        h1 { text-align: center; }
        input, textarea { width: 100%; padding: 10px; margin: 10px 0; }
        button { padding: 10px 15px; background-color: #007BFF; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>
<div class="container">
    <h1>Modifier l'événement</h1>
    <form method="POST">
        <input type="text" name="nom" placeholder="Nom de l'événement" value="<?= htmlspecialchars($event['nom']) ?>" required><br>
        <input type="date" name="date_event" value="<?= htmlspecialchars($event['date_event']) ?>" required><br>
        <input type="text" name="lieu" placeholder="Lieu" value="<?= htmlspecialchars($event['lieu']) ?>" required><br>
        <textarea name="description" placeholder="Description" rows="4"><?= htmlspecialchars($event['description']) ?></textarea><br>
        <button type="submit">Mettre à jour l'événement</button>
    </form>
</div>
</body>
</html>
