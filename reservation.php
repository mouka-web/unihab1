<?php
session_start();

// Connexion à la base de données
$conn = new mysqli('localhost', 'root', '', 'event');

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

$reservationStatus = "";

if (isset($_SESSION['user'])) {
    if (isset($_GET['event_id'])) {
        $eventId = $_GET['event_id'];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $faculte = $_POST['faculte'];
            $email = $_POST['email'];
            $telephone = $_POST['telephone'];

            $check = $conn->query("SELECT * FROM user WHERE mail = '$email'");

            if (!$check) {
                die("Erreur SQL : " . $conn->error);
            }

            if ($check->num_rows > 0) {
                $userRow = $check->fetch_assoc();
                $userId = $userRow['id'];
            } else {
                echo "<script>
                        alert('L\\'email n\\'est pas enregistré dans notre système. Veuillez vous inscrire avant de réserver.');
                        window.location.href = 'inscription.php';
                      </script>";
                exit;
            }

            $insertReservation = $conn->query("INSERT INTO reservations (nom, prenom, faculte, mail, telephone, event_id, user_id) 
                                              VALUES ('$nom', '$prenom', '$faculte', '$email', '$telephone', $eventId, $userId)");

            if ($insertReservation) {
                // Récupération du nom de l'événement
                $eventRes = $conn->query("SELECT nom FROM evenements WHERE id = $eventId");
                $eventData = $eventRes->fetch_assoc();
                $eventName = $eventData['nom'];

                // Affichage confirmation et QR code
                echo "<!DOCTYPE html><html lang='fr'><head><meta charset='UTF-8'><title>Confirmation</title></head><body>";
                echo "<div class='confirmation-container'>";
                echo "<h2>Réservation réussie !</h2>";
                echo "<p>Merci pour votre réservation à l'événement : <strong>$eventName</strong>.</p>";
                echo "<img src='payer.png' alt='QR Code' width='200'>";
                echo "<br><a href='reservation.php?event_id=$eventId' class='back-link'>Retour</a>";
                echo "</div></body></html>";
                exit;
            } else {
                $reservationStatus = "Erreur lors de la réservation : " . $conn->error;
            }
        }
    } else {
        $reservationStatus = "Événement non trouvé.";
    }
} else {
    $reservationStatus = "Vous devez être connecté pour réserver.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réserver un événement</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #007BFF;
        }

        form {
            background-color: #fff;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            margin-bottom: 30px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        form input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 20px;
            font-size: 14px;
        }

        button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 20px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 16px;
            width: 100%;
        }

        button:hover {
            background-color: #0056b3;
        }

        .confirmation-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            max-width: 700px;
            margin: 50px auto;
            text-align: center;
        }

        .confirmation-container h2 {
            color: #007BFF;
            margin-bottom: 20px;
        }

        .confirmation-container p {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }

        .confirmation-container img {
            margin-bottom: 20px;
            border-radius: 10px;
        }

        .confirmation-container .back-link {
            display: inline-block;
            padding: 12px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            transition: background-color 0.3s;
            font-size: 16px;
        }

        .confirmation-container .back-link:hover {
            background-color: #0056b3;
        }

        .confirmation-container .button-container {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Réserver un événement</h1>

    <?php
    if (isset($_GET['status']) && $_GET['status'] == 'success') {
        echo "<p>Réservation réussie !</p>";
    } elseif ($reservationStatus != "") {
        echo "<p>$reservationStatus</p>";
    }
    ?>

    <?php if (isset($_SESSION['user'])): ?>
        <form action="" method="POST">
            <label for="nom">Nom :</label>
            <input type="text" id="nom" name="nom" required><br>

            <label for="prenom">Prénom :</label>
            <input type="text" id="prenom" name="prenom" required><br>

            <label for="faculte">Faculté :</label>
            <input type="text" id="faculte" name="faculte" required><br>

            <label for="email">Email :</label>
            <input type="email" id="email" name="email" required><br>

            <label for="telephone">Téléphone :</label>
            <input type="text" id="telephone" name="telephone" required><br>

            <button type="submit">Réserver</button>
        </form>
    <?php else: ?>
        <p><a href="connexion.php">Se connecter</a> pour réserver.</p>
    <?php endif; ?>
</body>
</html>
