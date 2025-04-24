<?php
session_start();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user'])) {
    header("Location: login.php");  // Rediriger vers la page de login si non connecté
    exit();
}

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $date_event = $_POST['date_event'];
    $lieu = $_POST['lieu'];
    $description = $_POST['description'];  // Récupérer la description

    // Connexion à la base de données
    $conn = new mysqli('localhost', 'root', '', 'event');  // Remplace par tes propres identifiants

    if ($conn->connect_error) {
        die("Échec de la connexion : " . $conn->connect_error);
    }

    // Requête SQL pour insérer l'événement dans la base de données
    $sql = "INSERT INTO evenements (nom, date_event, lieu, description) 
            VALUES ('$nom', '$date_event', '$lieu', '$description')";

    if ($conn->query($sql) === TRUE) {
        echo "Événement ajouté avec succès !";
    } else {
        echo "Erreur : " . $conn->error;
    }

    $conn->close();
}
?>
