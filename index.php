<?php
session_start();

// Connexion à la base de données
$conn =new mysqli('localhost', 'root', 'password', 'event');

if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Traitement du formulaire d'ajout d'événement
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION['user']) && isset($_POST['ajouter'])) {
    $nom = $conn->real_escape_string($_POST['nom']);
    $date = $conn->real_escape_string($_POST['date_event']);
    $lieu = $conn->real_escape_string($_POST['lieu']);
    $desc = $conn->real_escape_string($_POST['description']);

    if (!empty($nom) && !empty($date) && !empty($lieu)) {
        $insertSql = "INSERT INTO evenements (nom, date_event, lieu, description)
                      VALUES ('$nom', '$date', '$lieu', '$desc')";
        $conn->query($insertSql);
        header("Location: index.php");
        exit();
    }
}

// Récupérer les événements depuis la base de données
$sql = "SELECT * FROM evenements ORDER BY date_event DESC";
$result = $conn->query($sql);

$events = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil - Événements</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f9f9f9;
            color: #007bff;
        }
            .navbar {
    display: flex;
    justify-content: space-between;
    align-items: center; /* Garde cette ligne */
    background-color:rgb(240, 241, 243);
}



        .navbar .logo {
            font-size: 22px;
            font-weight: bold;
        }

        .navbar .menu a {
            color:#002db1;
            text-decoration: none;
            margin-left: 20px;
            font-weight: 500;
            transition: color 0.3s;
        }

        .navbar .menu a:hover {
            color: #00bcd4;
        }

        .header {
    background-image: url('event.jpg'); /* ton image */
    background-size: cover;
    background-position: center;
    padding: 60px 20px;
    color: white;
    text-align: center;
    position: relative;
    height: 300px; /* tu peux ajuster la hauteur ici */
}


.header::after {
    content: '';
    background: rgba(0, 0, 0, 0.4); /* assombrit l’image */
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    z-index: 0;
}

.header-content {
    position: relative;
    z-index: 1;
}



        .header h1 {
            font-size: 48px;
            margin: 0;
        }

        .header p {
            font-size: 20px;
            margin-top: 10px;
            color: #eee;
        }

        .container {
            padding: 40px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .cards-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 25px;
        }

        .card {
            background-color: white;
            border-radius: 15px;
            width: 300px;
            padding: 20px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .card h3 {
            margin-top: 0;
            color:#002db1;;
        }

        .card p {
            color: #555;
            font-size: 15px;
            margin-bottom: 10px;
        }

        .card .date {
            font-weight: bold;
            color: #007BFF;
        }

        .card .button {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 20px;
            transition: background-color 0.3s ease;
        }

        .card .button:hover {
            background-color: #0056b3;
        }

        .login-message {
            text-align: center;
            margin-top: 20px;
            font-size: 18px;
            color:rgb(9, 12, 86) ;
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

        form input, form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 20px;
            font-size: 14px;
        }
        .logo-img {
    height: 100px; /* Ajuste la taille à ta convenance */
    width: auto;
    display: block;
}


        form button {
            background-color:rgb(13, 52, 145);
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 20px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        form button:hover {
            background-color:rgb(13, 52, 145);
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <img src="monlogo.png "alt="Logo" class="logo-img">
        </div>


        <div class="menu">
            <?php if (!isset($_SESSION['user'])): ?>
                <a href="inscription.php">Inscription</a>
                <a href="connexion.php">Se connecter</a>
            <?php else: ?>
                <span>Bonjour, <?= htmlspecialchars($_SESSION['user']) ?></span>
                <a href="logout.php">Déconnexion</a>
            <?php endif; ?>
            <a href="index.php">Accueil</a>
        </div>
    </div>

    <div class="header">
        <div class="header-content">
            <h1>Bienvenue sur UniHub</h1>
            <p>Créez, découvrez et réservez des événements en toute simplicité.</p>
        </div>
    </div>

    <div class="container">
        <h2>Les Événements</h2>

        <?php if (isset($_SESSION['user'])): ?>
            <form method="POST">
                <input type="text" name="nom" placeholder="Nom de l'événement" required>
                <input type="date" name="date_event" required>
                <input type="text" name="lieu" placeholder="Lieu" required>
                <textarea name="description" placeholder="Description" rows="4"></textarea>
                <button type="submit" name="ajouter">Ajouter l'événement</button>
            </form>
        <?php endif; ?>

        <?php if (count($events) > 0): ?>
            <div class="cards-container">
                <?php foreach ($events as $event): ?>
                    <div class="card">
                        <h3><?= htmlspecialchars($event['nom']) ?></h3>
                        <p class="date"><?= htmlspecialchars($event['date_event']) ?></p>
                        <p><?= htmlspecialchars($event['lieu']) ?></p>
                        <p><?= htmlspecialchars($event['description']) ?></p>

                        <?php if (isset($_SESSION['user'])): ?>
                            <a href="reservation.php?event_id=<?= $event['id'] ?>" class="button">Réserver</a>
                            <a href="modifier_evenement.php?id=<?= $event['id'] ?>" class="button">Modifier</a>
                            <form action="liste_reservations.php" method="GET" style="margin-top: 10px;">
                                <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                                <button type="submit" class="button" style="background-color: #6c757d;">Voir les réservations</button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Aucun événement n'a été ajouté pour le moment.</p>
        <?php endif; ?>

        <?php if (!isset($_SESSION['user'])): ?>
            <div class="login-message">
                <p>Vous devez être connecté pour ajouter un événement.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
