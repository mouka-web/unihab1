<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: login.php");  // Rediriger vers la page de connexion
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un événement</title>
</head>
<body>
    <h1>Ajouter un événement</h1>
    <form action="traitement_evenement.php" method="POST">
    <input type="text" name="nom" placeholder="Nom de l'événement" required>
    <input type="date" name="date_event" required>
    <input type="text" name="lieu" placeholder="Lieu" required>
    <textarea name="description" placeholder="Description de l'événement" required></textarea>
    <button type="submit">Ajouter</button>
</form>

</body>
</html>
