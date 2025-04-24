<?php
session_start();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Connexion à la base
$conn = new mysqli("localhost", "root", "", "event");
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifiant = trim($_POST['username']);
    $password = $_POST['password'];

    // Recherche de l'utilisateur par username ou mail
    $stmt = $conn->prepare("SELECT username, password FROM user WHERE username = ? OR mail = ?");
    $stmt->bind_param("ss", $identifiant, $identifiant);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user['username'];
            header("Location: index.php");
            exit;
        } else {
            $error = "❌ Mot de passe incorrect.";
        }
    } else {
        $error = "❌ Utilisateur non trouvé.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url('event.jpg'); /* Remplace avec ton image */
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        body::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: url('connexion-bg.jpg'); /* Ton image de fond */
            background-size: cover;
            background-position: center;
            filter: blur(8px);
            z-index: -1;
        }

        .center {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            z-index: 1;
            position: relative;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 30px 40px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 90%;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        form label {
            display: block;
            margin-bottom: 10px;
            color: #555;
        }

        form input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 20px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color:  #0056b3;
            color: white;
            border: none;
            border-radius: 20px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color:rgb(30, 92, 126);
        }

        p.error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="center">
        <div class="card">
            <h2>Connexion</h2>
            <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
            <form method="post">
                <label>Nom d'utilisateur ou mail :
                    <input type="text" name="username" required>
                </label>
                <label>Mot de passe :
                    <input type="password" name="password" required>
                </label>
                <button type="submit">Se connecter</button>
            </form>
        </div>
    </div>
</body>
</html>
